<?php

require_once __DIR__ . "/auth.php";

studenthub_start_session();

header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$user = currentUser();

echo json_encode([
    "authenticated" => $user !== null,
    "userType" => $user["type"] ?? null,
    "csrfToken" => csrfToken(),
]);
