<?php
session_start();
// Si le panier est vide, rediriger vers l'accueil
if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    header("Location: index.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer la commande - Multi-Confection</title>
    <script src="https://cdn.tailwindcss.com "></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Header -->
<header class="bg-white shadow-sm">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-700">Passer la commande</h1>
        <a href="index.php" class="text-indigo-600 hover:text-indigo-800"><i class="fas fa-arrow-left mr-2"></i> Retour aux produits</a>
    </div>
</header>

<!-- Formulaire de commande -->
<main class="container mx-auto py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-md shadow-md">
        <h2 class="text-xl font-bold mb-6">Résumé de votre commande</h2>

        <!-- Liste des produits dans le panier -->
        <div class="space-y-4 mb-6">
            <?php foreach ($cart as $item): 
                $total += $item['price'] * $item['quantity'];
            ?>
                <div class="flex items-center justify-between border-b pb-4">
                    <div class="flex items-center">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-16 h-16 object-cover rounded">
                        <div class="ml-4">
                            <h3 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-sm text-gray-500">Taille: <?= htmlspecialchars($item['size']) ?> | Couleur: <?= htmlspecialchars($item['color']) ?></p>
                        </div>
                    </div>
                    <p class="font-semibold"><?= number_format($item['price'] * $item['quantity'], 2, ',', ' ') ?> MAD</p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Total -->
        <div class="flex justify-between font-bold text-lg mb-6">
            <span>Total</span>
            <span><?= number_format($total, 2, ',', ' ') ?> MAD</span>
        </div>

        <h2 class="text-lg font-semibold mb-4">Coordonnées de livraison</h2>
        <form action="traitement_commande.php" method="POST" class="space-y-4">
            <?php if (!isset($_SESSION['user'])): ?>
                <p class="text-sm text-red-500">Vous devez être connecté pour passer une commande. <a href="connexion.php" class="text-indigo-600 hover:underline">Se connecter</a></p>
            <?php else: ?>
                <div>
                    <label for="nom" class="block text-gray-700 mb-1">Nom complet</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?>" class="w-full border border-gray-300 p-2 rounded" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>" class="w-full border border-gray-300 p-2 rounded" required>
                </div>
                <div>
                    <label for="adresse" class="block text-gray-700 mb-1">Adresse de livraison</label>
                    <textarea id="adresse" name="adresse" rows="3" class="w-full border border-gray-300 p-2 rounded" placeholder="Exemple : 123 Rue Mohamed V, Casablanca, Maroc" required></textarea>
                </div>
                <div>
                    <label for="telephone" class="block text-gray-700 mb-1">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" class="w-full border border-gray-300 p-2 rounded" required>
                </div>
            <?php endif; ?>

            <h2 class="text-lg font-semibold mt-6">Méthode de paiement</h2>
            <div class="border border-gray-300 p-4 rounded-md">
                <label class="block mb-2">
                    <input type="radio" name="payment" value="cash" checked class="mr-2">
                    Paiement à la livraison (Cash)
                </label>
                <!-- Tu peux ajouter d'autres méthodes de paiement plus tard -->
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-md font-semibold transition">
                Confirmer la commande
            </button>
        </form>
    </div>
</main>

<!-- Footer -->
<footer class="bg-white shadow-inner mt-12 py-6">
    <div class="container mx-auto px-4 text-center text-gray-600 text-sm">
        &copy; <?= date('Y') ?> Multi-Confection. Tous droits réservés.
    </div>
</footer>

</body>
</html>