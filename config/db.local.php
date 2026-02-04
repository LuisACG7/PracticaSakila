<?php
$host = "localhost";
// Servidor
$db   = "sakila";
$user = "db_20031609";
$pass = "20031609";
// Local
//$db   = "sakilapelis";
//$user = "sakilapelis_user";
//$pass = "sakila1234";
$charset = "utf8mb4";

$pdo = new PDO(
    "mysql:host=$host;dbname=$db;charset=$charset",
    $user,
    $pass,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);
