<?php
session_start();
$host = 'localhost';
$dbname = 'boutique';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les champs
    $civilite = $_POST['civilite'] ?? '';
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $societe = trim($_POST['societe'] ?? '');
    $nif = trim($_POST['nif'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? '';

    // Valider les champs requis
    if (empty($civilite) || empty($prenom) || empty($nom) || empty($email) || empty($password) || empty($password_confirm) || empty($date_naissance)) {
        die("Tous les champs marqués par * sont obligatoires.");
    }

    // Valider l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email invalide.");
    }

    // Valider la date de naissance
    if (!preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/", $date_naissance)) {
        die("Format de date invalide. Utilisez JJ/MM/AAAA.");
    }

    // Convertir la date en format SQL (YYYY-MM-DD)
    list($jour, $mois, $annee) = explode('/', $date_naissance);
    $date_naissance_sql = "$annee-$mois-$jour";

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        die("Cet email est déjà utilisé.");
    }

    // Vérifier que le mot de passe est valide
    if ($password !== $password_confirm) {
        die("Les mots de passe ne correspondent pas.");
    }

    if (strlen($password) < 6) {
        die("Le mot de passe doit faire au moins 6 caractères.");
    }

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer l'utilisateur dans la base
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (civilite, nom, prenom, societe, nif, email, mot_de_passe, date_naissance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$civilite, $nom, $prenom, $societe, $nif, $email, $hashedPassword, $date_naissance_sql]);

    // Rediriger vers la connexion
    $_SESSION['message'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
    header("Location: connexion.php");
    exit;
}
?>