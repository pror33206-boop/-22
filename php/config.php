<?php
// Конфигурация базы данных
define('DB_HOST', 'localhost');
define('DB_NAME', 'sto_database');
define('DB_USER', 'root');
define('DB_PASS', '');

// Подключение к базе данных
function getDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Ошибка подключения: " . $e->getMessage());
    }
}

// Функция для отправки JSON ответа
function sendJsonResponse($success, $message, $data = []) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
?>