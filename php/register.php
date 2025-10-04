<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    // Валидация
    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        sendJsonResponse(false, 'Все поля обязательны для заполнения');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse(false, 'Некорректный email');
    }
    
    try {
        $pdo = getDB();
        
        // Проверка существования пользователя
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            sendJsonResponse(false, 'Пользователь с таким email уже существует');
        }
        
        // Регистрация пользователя
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $hashedPassword]);
        
        sendJsonResponse(true, 'Регистрация прошла успешно!');
        
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Ошибка базы данных: ' . $e->getMessage());
    }
}
?>