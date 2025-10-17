<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "auto_future_block";

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_errno) {
    die("Lidhja me databazën dështoi: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
