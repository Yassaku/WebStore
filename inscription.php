<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Multi-Confection</title>
    <script src="https://cdn.tailwindcss.com "></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Créer un compte</h2>

        <form id="register-form" action="traitement_inscription.php" method="POST" class="space-y-4">
            <!-- Civilité -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Civilité *</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="civilite" value="homme" class="mr-2 text-indigo-600" required>
                        Homme
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="civilite" value="femme" class="mr-2 text-indigo-600">
                        Femme
                    </label>
                </div>
            </div>

            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-gray-700 font-medium mb-1">Prénom *</label>
                <input type="text" id="prenom" name="prenom" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-gray-700 font-medium mb-1">Nom *</label>
                <input type="text" id="nom" name="nom" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Société -->
            <div>
                <label for="societe" class="block text-gray-700 font-medium mb-1">Société</label>
                <input type="text" id="societe" name="societe" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- NIF -->
            <div>
                <label for="nif" class="block text-gray-700 font-medium mb-1">Numéro d'identification fiscale</label>
                <input type="text" id="nif" name="nif" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Email *</label>
                <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Mot de passe -->
            <div class="relative">
                <label for="password" class="block text-gray-700 font-medium mb-1">Mot de passe *</label>
                <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <i id="toggle-password" class="fas fa-eye absolute right-3 top-9 text-gray-400 cursor-pointer" onclick="togglePassword()"></i>
            </div>

            <!-- Confirmer le mot de passe -->
            <div class="relative">
                <label for="password_confirm" class="block text-gray-700 font-medium mb-1">Confirmer le mot de passe *</label>
                <input type="password" id="password_confirm" name="password_confirm" class="w-full border border-gray-300 rounded-md p-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <i id="toggle-password-confirm" class="fas fa-eye absolute right-3 top-9 text-gray-400 cursor-pointer" onclick="togglePasswordConfirm()"></i>
            </div>

            <!-- Date de naissance -->
            <div>
                <label for="date_naissance" class="block text-gray-700 font-medium mb-1">Date de naissance (JJ/MM/AAAA) *</label>
                <input type="text" id="date_naissance" name="date_naissance" placeholder="ex: 15/08/1990" class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <!-- Bouton d'inscription -->
            <div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                    S'inscrire
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Déjà un compte ? <a href="connexion.php" class="text-indigo-600 hover:underline">Connectez-vous</a>
        </p>
    </div>

    <!-- Script pour afficher/masquer le mot de passe -->
    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            const icon = document.getElementById('toggle-password');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pass.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function togglePasswordConfirm() {
            const pass = document.getElementById('password_confirm');
            const icon = document.getElementById('toggle-password-confirm');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pass.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Vérification côté client (optionnel mais utile)
        document.getElementById("register-form").addEventListener("submit", function(e) {
            const password = document.getElementById("password").value;
            const password_confirm = document.getElementById("password_confirm").value;
            if (password !== password_confirm) {
                e.preventDefault();
                alert("Les mots de passe ne correspondent pas.");
            }
        });
    </script>

</body>
</html>