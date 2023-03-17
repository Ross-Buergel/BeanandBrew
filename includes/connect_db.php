<?php
//assigns appropriate values to variables
$host = "localhost";
$dbname = "db_beanandbrew";
$username = "root";
$password = "";

//creates database connection
$dbc = new mysqli($host, $username, $password, $dbname);

//outputs error if one occurs
if ($dbc->connect_errno) {
    die("Connection error: " . $dbc->connect_error);
}

//returns connection
return $dbc;
?>