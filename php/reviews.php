<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Добавление отзыва
    $review_name = trim($_POST['review_name']);
    $review_rating = (int)$_POST['review_rating'];
    $review_text = trim($_POST['review_text']);
    
    // Валидация
    if (empty($review_name) || empty($review_text) || $review_rating < 1 || $review_rating > 5) {
        sendJsonResponse(false, 'Все поля обязательны для заполнения');
    }
    
    try {
        $pdo = getDB();
        
        $stmt = $pdo->prepare("INSERT INTO reviews (client_name, rating, review_text) VALUES (?, ?, ?)");
        $stmt->execute([$review_name, $review_rating, $review_text]);
        
        sendJsonResponse(true, 'Отзыв успешно добавлен!');
        
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Ошибка базы данных: ' . $e->getMessage());
    }
} else {
    // Получение отзывов
    try {
        $pdo = getDB();
        
        $stmt = $pdo->query("SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC LIMIT 10");
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode($reviews);
        
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode([]);
    }
}
?>