<?php
session_start();

if (!isset($_SESSION['order'])) {
    header("Location: index.php");
    exit;
}

$order = $_SESSION['order'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande confirmée - Multi-Confection</title>
    <script src="https://cdn.tailwindcss.com "></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
        <i class="fas fa-check-circle text-green-500 text-5xl mb-4"></i>
        <h2 class="text-2xl font-bold mb-2">Commande confirmée !</h2>
        <p class="text-gray-700 mb-4">Merci pour votre commande. Elle est en cours de traitement.</p>

        <div class="text-left mt-4">
            <p><strong>Total : </strong><?= number_format($order['total'], 2, ',', ' ') ?> MAD</p>
            <p><strong>Livraison à : </strong><?= htmlspecialchars($order['adresse']) ?></p>
            <p><strong>Téléphone : </strong><?= htmlspecialchars($order['telephone']) ?></p>
        </div>

        <div class="mt-6">
            <a href="index.php" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded inline-block">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>