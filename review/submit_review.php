<?php

session_start();
include "../config/db.php";
header('Content-Type: application/json');

$name        = trim($_POST['name']        ?? '');
$email       = trim($_POST['email']       ?? '');
$rating      = (int)($_POST['rating']     ?? 0);
$category    = trim($_POST['category']    ?? 'General');
$review_text = trim($_POST['review_text'] ?? '');
$user_id     = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

if (!$name || !$email || !$review_text) {
    echo json_encode(['success' => false, 'error' => 'Name, email and review are required.']); exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']); exit;
}
if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'error' => 'Please select a star rating.']); exit;
}

$stmt = $conn->prepare("INSERT INTO reviews (user_id, name, email, rating, category, review_text) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ississ", $user_id, $name, $email, $rating, $category, $review_text);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'review'  => [
            'name'        => htmlspecialchars($name),
            'rating'      => $rating,
            'category'    => htmlspecialchars($category),
            'review_text' => htmlspecialchars($review_text),
            'created_at'  => date('d M Y'),
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save review. Please try again.']);
}
