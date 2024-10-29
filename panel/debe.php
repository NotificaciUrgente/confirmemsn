<?php
$db = new PDO('sqlite:base-login.db');
$query = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$db->exec($query);
?>
