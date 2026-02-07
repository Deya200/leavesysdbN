<?php
try {
    $dsn = "pgsql:host=127.0.0.1;port=5432;dbname=leavesysdb;";
    $username = "postgres";
    $password = "postgres";
    $pdo = new PDO($dsn, $username, $password);
    echo "Connected successfully to PostgreSQL database 'leavesysdb'!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
