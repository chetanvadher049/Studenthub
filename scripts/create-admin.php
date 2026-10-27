<?php

if (PHP_SAPI !== "cli") {
    http_response_code(404);
    exit;
}

require_once __DIR__ . "/../php/dbconnect.php";

function prompt(string $label): string
{
    fwrite(STDOUT, $label);
    $value = fgets(STDIN);
    return $value === false ? "" : trim($value);
}

function promptSecret(string $label): string
{
    if (PHP_OS_FAMILY === "Windows") {
        $script = '$secure = Read-Host -Prompt "'
            . addcslashes($label, '"')
            . '" -AsSecureString; $ptr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secure); '
            . 'try { $plain = [Runtime.InteropServices.Marshal]::PtrToStringBSTR($ptr); '
            . '[Console]::Out.Write([Convert]::ToBase64String([Text.Encoding]::UTF8.GetBytes($plain))) } '
            . 'finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($ptr) }';
        $process = proc_open(
            ["powershell.exe", "-NoLogo", "-NoProfile", "-Command", $script],
            [0 => STDIN, 1 => ["pipe", "w"], 2 => STDERR],
            $pipes
        );
        if (!is_resource($process)) {
            throw new RuntimeException("Could not open a secure password prompt.");
        }
        $encoded = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $exitCode = proc_close($process);
        $decoded = is_string($encoded) ? base64_decode(trim($encoded), true) : false;
        if ($exitCode !== 0 || $decoded === false) {
            throw new RuntimeException("Secure password entry failed.");
        }
        return $decoded;
    }

    exec("stty -echo");
    try {
        fwrite(STDOUT, $label);
        $password = fgets(STDIN);
        fwrite(STDOUT, PHP_EOL);
        return $password === false ? "" : rtrim($password, "\r\n");
    } finally {
        exec("stty echo");
    }
}

function stopWithError(string $message, int $status = 1): never
{
    fwrite(STDERR, $message . PHP_EOL);
    exit($status);
}

try {
    fwrite(STDOUT, "StudentHub administrator setup (additional accounts default to admin)." . PHP_EOL);
    $fullName = prompt("Full name: ");
    $username = prompt("Username (3-50 letters, numbers, underscores): ");
    $email = prompt("Email: ");
    $password = promptSecret("Password: ");
    $confirmation = promptSecret("Confirm password: ");

    if (!preg_match("/^[\\p{L}\\p{M}][\\p{L}\\p{M}\\s.'’\\-]{1,99}$/u", $fullName)) {
        stopWithError("Enter a valid name between 2 and 100 characters.");
    }
    if (!preg_match("/^[A-Za-z0-9_]{3,50}$/", $username)) {
        stopWithError("Username must be 3-50 letters, numbers, or underscores.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
        stopWithError("Enter a valid email address of no more than 255 characters.");
    }
    if (
        strlen($password) < 12 ||
        strlen($password) > 255 ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[0-9]/", $password) ||
        !preg_match("/[^A-Za-z0-9]/", $password)
    ) {
        stopWithError("Use at least 12 characters with uppercase, lowercase, a number, and a symbol.");
    }
    if (!hash_equals($password, $confirmation)) {
        stopWithError("The password confirmation did not match.");
    }

    $connection = studenthub_db();
    $connection->begin_transaction();
    $adminLock = $connection->query(
        "SELECT id FROM admins ORDER BY id LIMIT 1 FOR UPDATE"
    );
    $existingAdmins = $adminLock->num_rows;
    $adminLock->free();
    $statement = $connection->prepare(
        "SELECT id FROM admins WHERE username = ? OR email = ? LIMIT 1"
    );
    $statement->bind_param("ss", $username, $email);
    $statement->execute();
    $statement->store_result();
    if ($statement->num_rows > 0) {
        $statement->close();
        $connection->rollback();
        $connection->close();
        stopWithError("An administrator already uses that username or email.");
    }
    $statement->close();

    $role = $existingAdmins === 0 ? "super_admin" : "admin";
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $insert = $connection->prepare(
        "INSERT INTO admins (full_name, username, email, password_hash, role, status)
         VALUES (?, ?, ?, ?, ?, 'active')"
    );
    $insert->bind_param("sssss", $fullName, $username, $email, $passwordHash, $role);
    $insert->execute();
    $insert->close();
    $connection->commit();
    $connection->close();
    $password = $confirmation = $passwordHash = "";

    fwrite(STDOUT, "Administrator created successfully with role " . $role . "." . PHP_EOL);
    if ($role === "admin") {
        fwrite(STDOUT, "Additional admins are regular admins; only a trusted database operator should grant super_admin." . PHP_EOL);
    }
} catch (mysqli_sql_exception $exception) {
    if (isset($connection) && $connection instanceof mysqli) {
        try {
            $connection->rollback();
        } catch (Throwable $rollbackException) {
            error_log("StudentHub admin setup rollback failed: " . $rollbackException->getMessage());
        }
    }
    error_log("StudentHub CLI admin setup failed: " . $exception->getMessage());
    stopWithError((int) $exception->getCode() === 1062
        ? "That username or email is already assigned."
        : "Admin setup could not complete. Check the database connection and try again.");
} catch (RuntimeException $exception) {
    stopWithError($exception->getMessage());
}
