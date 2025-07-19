<?php
session_start();

if (empty($_SESSION['user']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

// Récupérer les données du formulaire
$nom = $_POST['nom'] ?? '';
$email = $_POST['email'] ?? '';
$adresse = $_POST['adresse'] ?? '';
$telephone = $_POST['telephone'] ?? '';

if (!$nom || !$email || !$adresse || !$telephone) {
    die("Veuillez remplir tous les champs.");
}

// Enregistrer les informations de commande
$_SESSION['order'] = [
    'user' => $_SESSION['user'],
    'items' => $_SESSION['cart'],
    'total' => array_reduce($_SESSION['cart'], function($sum, $item) {
        return $sum + ($item['price'] * $item['quantity']);
    }, 0),
    'adresse' => $adresse,
    'telephone' => $telephone,
    'date' => date("Y-m-d H:i:s")
];

// Vider le panier après commande
unset($_SESSION['cart']);

// Rediriger vers la page de confirmation
header("Location: confirmation.php");
exit;