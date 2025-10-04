<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = trim($_POST['client_name']);
    $client_phone = trim($_POST['client_phone']);
    $car_model = trim($_POST['car_model']);
    $service_type = trim($_POST['service_type']);
    $booking_date = $_POST['booking_date'];
    
    // Валидация
    if (empty($client_name) || empty($client_phone) || empty($car_model) || empty($service_type) || empty($booking_date)) {
        sendJsonResponse(false, 'Все поля обязательны для заполнения');
    }
    
    try {
        $pdo = getDB();
        
        // Создание записи
        $stmt = $pdo->prepare("INSERT INTO bookings (client_name, client_phone, car_model, service_type, booking_date, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$client_name, $client_phone, $car_model, $service_type, $booking_date]);
        
        sendJsonResponse(true, 'Запись успешно создана! Мы свяжемся с вами для подтверждения.');
        
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Ошибка базы данных: ' . $e->getMessage());
    }
}
?>