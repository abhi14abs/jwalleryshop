<?php
require_once __DIR__ . '/../config.php';

function get_db_connection() {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    return $conn;
}
