<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['cart'])) {
        $_SESSION['cart'] = $data['cart'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Données du panier manquantes']);
    }
    exit;
}