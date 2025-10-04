<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Валидация
    if (empty($email) || empty($password)) {
        sendJsonResponse(false, 'Все поля обязательны для заполнения');
    }
    
    try {
        $pdo = getDB();
        
        // Поиск пользователя
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            sendJsonResponse(true, 'Вход выполнен успешно!', ['user_id' => $user['id']]);
        } else {
            sendJsonResponse(false, 'Неверный email или пароль');
        }
        
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Ошибка базы данных: ' . $e->getMessage());
    }
}
?>