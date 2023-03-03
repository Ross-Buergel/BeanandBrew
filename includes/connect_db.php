<?php
$host = "localhost";
$dbname = "db_beanandbrew";
$username = "root";
$password = "";

$dbc = new mysqli($host, $username, $password, $dbname);

if ($dbc->connect_errno) {
    die("Connection error: " . $dbc->connect_error);
}

return $dbc;
?>