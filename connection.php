<?php

$serverName = "localhost";
$database = "decadhen";

$conn = sqlsrv_connect($serverName, [
    "Database" => $database,
    "CharacterSet" => "UTF-8",
    "Authentication" => "ActiveDirectoryIntegrated",
    "Encrypt" => 0
]);

if ($conn === false) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
} 
?>