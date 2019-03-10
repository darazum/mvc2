<?php
require '../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

$dbType = $_ENV['DB_TYPE'];
$dbHost = $_ENV['DB_HOST_1'];
$dbUser = $_ENV['DB_USER'];
$dbName = $_ENV['DB_NAME_1'];
$dbPassword = $_ENV['DB_PASSWORD'];

$pdo = new PDO("$dbType:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);
$pdo->exec("INSERT INTO users (`name`, password_hash, created_at) VALUES
('Dima', '123', '2017-05-01 12:00:05')");

var_dump($pdo->errorInfo());
