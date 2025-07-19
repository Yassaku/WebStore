<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte - Multi-Confection</title>
    <script src="https://cdn.tailwindcss.com "></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Navigation -->
<header class="bg-white shadow-sm">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-700">Mon compte</h1>
        <a href="index.php" class="text-indigo-600 hover:text-indigo-800"><i class="fas fa-home mr-2"></i>Retour à l'accueil</a>
    </div>
</header>

<!-- Contenu du compte -->
<main class="container mx-auto py-10 px-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6">Informations du compte</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations utilisateur -->
            <div>
                <h3 class="font-bold text-gray-700 mb-2">Profil</h3>
                <p><strong>Nom : </strong><?= htmlspecialchars($_SESSION['user']['nom'] ?? 'Non renseigné') ?></p>
                <p><strong>Email : </strong><?= htmlspecialchars($_SESSION['user']['email'] ?? 'Non renseigné') ?></p>

                <!-- Affichage conditionnel de la date -->
                <?php if (!empty($_SESSION['user']['date_inscription'])): ?>
                    <p><strong>Date d'inscription : </strong>
                        <?= date("d/m/Y", strtotime($_SESSION['user']['date_inscription'])) ?>
                    </p>
                <?php else: ?>
                    <p><strong>Date d'inscription : </strong>Non disponible</p>
                <?php endif; ?>

                <p><strong>Rôle : </strong>
                    <?= $_SESSION['user']['role'] === 'admin' ? 'Administrateur' : 'Client' ?>
                </p>
            </div>

            <!-- Adresse -->
            <div>
                <h3 class="font-bold text-gray-700 mb-2">Adresse</h3>
                <p>Aucune adresse enregistrée.</p>
                <a href="#" class="mt-4 inline-block text-indigo-600 hover:underline text-sm">Ajouter une adresse</a>
            </div>

            <!-- Commandes -->
            <div class="mt-6">
                <h3 class="font-bold text-gray-700 mb-2">Mes commandes</h3>
                <p class="text-gray-600">Aucune commande trouvée.</p>
                <a href="#" class="mt-2 inline-block text-indigo-600 hover:underline text-sm">Voir mes commandes</a>
            </div>

            <!-- Sécurité -->
            <div class="mt-6">
                <h3 class="font-bold text-gray-700 mb-2">Sécurité</h3>
                <a href="#" class="inline-block text-indigo-600 hover:underline text-sm">Changer mon mot de passe</a>
                <a href="#" class="inline-block text-indigo-600 hover:underline text-sm ml-4">Changer mon email</a>
            </div>
        </div>

        <!-- Bouton de déconnexion -->
        <div class="mt-8 text-center">
            <a href="deconnexion.php" class="bg-red-600 hover:bg-red-700 text-white py-2 px-6 rounded-md inline-block text-sm">
                <i class="fas fa-sign-out-alt mr-1"></i> Se déconnecter
            </a>
        </div>
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