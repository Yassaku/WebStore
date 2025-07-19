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
    <title>Changer mon mot de passe</title>
    <script src="https://cdn.tailwindcss.com "></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Changer mon mot de passe</h2>
        <form action="traitement_changer_mdp.php" method="POST" class="space-y-4">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                <input type="password" name="current_password" id="current_password" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full border border-gray-300 rounded-md p-2" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md">Enregistrer</button>
        </form>
    </div>
</body>
</html>