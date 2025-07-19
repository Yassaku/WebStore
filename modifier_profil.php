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
    <title>Modifier mon profil</title>
    <script src="https://cdn.tailwindcss.com "></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Modifier mon profil</h2>
        <form action="traitement_modifier_profil.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($_SESSION['user']['nom']) ?>" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="<?= $_SESSION['user']['email'] ?>" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Société</label>
                <input type="text" name="societe" value="<?= $_SESSION['user']['societe'] ?? '' ?>" class="w-full border border-gray-300 p-2 rounded">
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded">
                Enregistrer les modifications
            </button>
        </form>
    </div>
</body>
</html>