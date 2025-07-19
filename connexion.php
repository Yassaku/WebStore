<!-- connexion.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Multi-Confection</title>
    <script src="https://cdn.tailwindcss.com "></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
        <form action="traitement_connexion.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Email</label>
                <input type="email" name="email" id="email" class="w-full border rounded-md p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="w-full border rounded-md p-2" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Se connecter
            </button>
            <p class="mt-4 text-center">
                <a href="inscription.php" class="text-indigo-600 hover:underline">Pas encore de compte ? S'inscrire</a>
            </p>
        </form>
    </div>
</body>
</html>