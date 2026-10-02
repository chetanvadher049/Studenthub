<?php

function studenthub_db(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $host = getenv("STUDENTHUB_DB_HOST") ?: "localhost";
    $username = getenv("STUDENTHUB_DB_USER") ?: "root";
    $password = getenv("STUDENTHUB_DB_PASSWORD");
    $database = getenv("STUDENTHUB_DB_NAME") ?: "studenthub";
    $port = (int) (getenv("STUDENTHUB_DB_PORT") ?: 3306);

    if ($password === false) {
        $password = "";
    }

    $connection = new mysqli(
        $host,
        $username,
        $password,
        $database,
        $port
    );
    $connection->set_charset("utf8mb4");

    return $connection;
}