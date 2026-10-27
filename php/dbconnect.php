<?php

function studenthub_db(?string $databaseOverride = null): mysqli
{
    static $connections = [];

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $host = getenv("STUDENTHUB_DB_HOST") ?: "127.0.0.1";
    $username = getenv("STUDENTHUB_DB_USER") ?: "root";
    $password = getenv("STUDENTHUB_DB_PASSWORD") ?: "";
    $database = $databaseOverride
        ?? (getenv("STUDENTHUB_DB_NAME") ?: "studenthub");
    $port = (int) (getenv("STUDENTHUB_DB_PORT") ?: 3306);
    $connectionKey = $host . ":" . $port . "/" . $database . "/" . $username;

    if (isset($connections[$connectionKey])) {
        try {
            $connections[$connectionKey]->query("SELECT 1");
            return $connections[$connectionKey];
        } catch (mysqli_sql_exception $exception) {
            if (!in_array((int) $exception->getCode(), [2006, 2013, 2055], true)) {
                throw $exception;
            }
            unset($connections[$connectionKey]);
        }
    }

    try {
        $connection = new mysqli(
            $host,
            $username,
            $password,
            $database,
            $port
        );
    } catch (mysqli_sql_exception $exception) {
        error_log("StudentHub database connection failed: " . $exception->getMessage());
        throw new mysqli_sql_exception("StudentHub database connection failed.");
    }

    $connection->set_charset("utf8mb4");
    $connections[$connectionKey] = $connection;

    return $connection;
}