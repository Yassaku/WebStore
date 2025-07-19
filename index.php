<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Multi Confection - Boutique en ligne</title>
  <script src="https://cdn.tailwindcss.com "></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
  <style>
    .account-dropdown {
      transition: all 0.2s ease-in-out;
      animation: fadeIn 0.2s ease-in forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Style des options de couleur */
    .color-option {
      width: 1.5rem;
      height: 1.5rem;
      border-radius: 999px;
      border: 1px solid #ccc;
      cursor: pointer;
      transition: transform 0.2s ease;
    }

    .color-option:hover {
      transform: scale(1.1);
    }

    .color-option.selected {
      border: 2px solid #000;
      transform: scale(1.1);
    }

    /* Style du logo dans l'en-tête */
    header .logo {
      height: 4rem;
      width: auto;
    }

    @media (max-width: 728px) {
      header .logo {
        height: 4rem;
      }
    }

    /* Transition pour la modale produit */
    .product-modal {
      transition: all 0.3s ease;
    }

    /* Style des options de taille */
    .size-option {
      transition: background-color 0.2s, color 0.2s;
    }

    .size-option:hover,
    .size-option.selected {
      background-color: #3b82f6;
      color: white;
    }

    /* Style du dropdown du panier */
    .cart-dropdown {
      max-height: 70vh;
      overflow-y: auto;
    }

    /* Animation d'apparition des produits */
    @keyframes slideIn {
      from {
        transform: translateY(20px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .animate-slideIn {
      animation: slideIn 0.3s ease-out forwards;
    }

    /* Style de l'image dans la modale */
    #modal-product-image {
      width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: cover;
      border-radius: 0.5rem;
      transition: transform 0.3s ease;
    }

    #modal-product-image:hover {
      transform: scale(1.05);
    }

    @media (max-width: 768px) {
      #modal-product-image {
        max-height: 300px;
      }
    }

    /* Centrage vertical dans la modale produit */
    #product-modal .grid-cols-1.md\:grid-cols-2 {
      align-items: center;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

<?php
session_start();

$host = 'localhost';
$dbname = 'boutique';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Header/Navigation -->
<header class="bg-white shadow-md sticky top-0 z-50">
  <div class="container mx-auto px-4 py-3 flex justify-between items-center">
    <div class="flex items-center space-x-4">
      <!-- Logo -->
      <a href="#" class="text-2xl font-bold text-indigo-600">
        <img src="images/Nouveau projet.png" alt="Multi Confection" class="logo h-10 w-auto">
      </a>
      <a href="#" class="text-gray-700 hover:text-indigo-600">Accueil</a>
      <a href="#products" class="text-gray-700 hover:text-indigo-600">Produits</a>
      <a href="#" class="text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
      <a href="#" class="text-gray-700 hover:text-indigo-600">Restauration</a>
      <a href="#" class="text-gray-700 hover:text-indigo-600">Santé</a>
      <a href="#" class="text-gray-700 hover:text-indigo-600">Contact</a>
    </div>

    <div class="flex items-center space-x-4">
      <!-- Icône du compte avec menu déroulant -->
      <div class="relative inline-block text-left">
        <button id="account-btn" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
          <i class="fas fa-user text-xl"></i>
        </button>
        <div id="account-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
          <div class="py-1">
            <?php if (isset($_SESSION['user'])): ?>
              <a href="mon_compte.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mon compte</a>
              <a href="deconnexion.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Se déconnecter</a>
            <?php else: ?>
              <a href="connexion.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Se connecter</a>
              <a href="inscription.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">S'inscrire</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Panier -->
      <div class="relative">
        <button id="cart-btn" class="text-gray-700 hover:text-indigo-600 relative">
          <i class="fas fa-shopping-cart text-xl"></i>
          <span id="cart-count" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
        </button>
        <div id="cart-dropdown" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg p-4 cart-dropdown">
          <div id="cart-items" class="space-y-3">
            <p class="text-gray-500 text-center py-4">Votre panier est vide</p>
          </div>
          <div class="border-t mt-3 pt-3">
            <div class="flex justify-between font-semibold">
              <span>Total:</span>
              <span id="cart-total">0 MAD</span>
            </div>
            <a href="commande.php" class="block mt-4 bg-indigo-600 text-white text-center py-2 rounded-md hover:bg-indigo-700">
    Passer la commande
</a>
          </div>
        </div>
      </div>

      <!-- Bouton menu mobile -->
      <button id="mobile-menu-btn" class="md:hidden text-gray-700">
        <i class="fas fa-bars text-xl"></i>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="hidden container mx-auto px-4 md:hidden">
  <div class="flex flex-col space-y-4 py-4">
    <a href="#" class="text-gray-700 hover:text-indigo-600">Accueil</a>
    <a href="#products" class="text-gray-700 hover:text-indigo-600">Produits</a>
    <a href="#" class="text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
    <a href="#" class="text-gray-700 hover:text-indigo-600">Restauration</a>
    <a href="#" class="text-gray-700 hover:text-indigo-600">Santé</a>
    <a href="#" class="text-gray-700 hover:text-indigo-600">Contact</a>
    <a href="connexion.php" class="text-gray-700 hover:text-indigo-600">Compte</a>
    <a href="inscription.php" class="text-gray-700 hover:text-indigo-600">Inscription</a>
  </div>
</div>

<!-- Script pour les menus déroulants -->
<script>
  
  
  if (accountBtn && accountDropdown) {
    accountBtn.addEventListener('click', (e) => {
      e.preventDefault();
      accountDropdown.classList.toggle('hidden');
    });

    // Fermer le menu si clic à l'extérieur
    document.addEventListener('click', (e) => {
      if (!accountDropdown.contains(e.target) && !accountBtn.contains(e.target)) {
        accountDropdown.classList.add('hidden');
      }
    });
  }

  
</script>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-2 flex flex-col space-y-2">
                <a href="#" class="py-2 text-gray-700 hover:text-indigo-600">Accueil</a>
                <a href="#products" class="py-2 text-gray-700 hover:text-indigo-600">Produits</a>
                <a href="#" class="py-2 text-gray-700 hover:text-indigo-600">BTP/INDUSTRIE</a>
                <a href="#" class="py-2 text-gray-700 hover:text-indigo-600">Restauration</a>
                <a href="#" class="py-2 text-gray-700 hover:text-indigo-600">Santé</a>
                <a href="#" class="py-2 text-gray-700 hover:text-indigo-600">Contact</a>
                
            </div>
        </div>
    </header>

    <!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-16">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-8 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Votre Métier, Notre Priorité  Habillez-vous comme un Pro</h1>
            <p class="text-xl mb-6">Découvrez une large gamme de vêtements de travail robustes, confortables et stylés, conçus pour répondre aux exigences des vrais professionnels.</p>
            <a href="#products" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 inline-block">Voir les produits</a>
        </div>
        <div class="md:w-1/2 hero-image-container">
            <img src="images/JL6873M002.webp" class="hero-image">
        </div>
    </div>
</section>
    <!-- Featured Categories -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Nos Catégories</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="#" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="images/tenues-pro-artisanat-btp-industrie.jpg" alt="BTP/INDUSTRIE" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">BTP/INDUSTRIE</h3>
                    </div>
                </a>
                <a href="#" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="images/129.jpg" alt="Restauration" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Restauration</h3>
                    </div>
                </a>
                <a href="#" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="images/hopital-medical-sante.jpg" alt="Santé" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Santé</h3>
                    </div>
                </a>
                <a href="#" class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="Promotions" class="w-full h-64 object-cover group-hover:scale-105 transition-transform">
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <h3 class="text-white text-2xl font-bold">Promotions</h3>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Nos Produits</h2>
                <div class="flex space-x-2">
                    <select class="border rounded-md px-3 py-2">
                        <option>Trier par</option>
                        <option>Prix croissant</option>
                        <option>Prix décroissant</option>
                        <option>Nouveautés</option>
                        <option>Populaires</option>
                    </select>
                    <select class="border rounded-md px-3 py-2">
                        <option>Toutes catégories</option>
                        <option>BTP/INDUSTRIE</option>
                        <option>Restauration</option>
                        <option>Santé</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80" alt="Chemise homme" class="w-full h-64 object-cover">
                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-20%</div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">T-shirt basique(Hotel)</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-gray-600 text-sm ml-2">(24)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-gray-500 line-through text-sm">299 MAD</span>
                                <span class="text-indigo-600 font-bold ml-2">239 MAD</span>
                            </div>
                            <button class="view-product-btn" data-id="1" data-name="Chemise élégante" data-price="239" data-colors='[{"name":"Blanc","image":"https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1469&q=80"}]' data-description="Chemise élégante pour homme en coton de haute qualité. Parfaite pour les occasions spéciales ou le bureau.">
                                <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
                            </button>
                        </div>
                    </div>
                </div>
                  
<!-- T-shirt de travail bicolore AMBITION Clique -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/clique-029376.jpg" alt="T-shirt de travail bicolore AMBITION Clique" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">T-shirt de travail bicolore AMBITION Clique</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(15)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">140 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="47"
              data-name="T-shirt de travail bicolore AMBITION Clique"
              data-price="140"
              data-colors='[
                {"name":"Gris","image":"images/clique-029376.jpg"},
                {"name":"Jaune","image":"images/clique-029376 (1).jpg"},
                {"name":"Orange","image":"images/clique-029376 (2).jpg"}
              ]'
              data-description="T-shirt de travail bicolore AMBITION Clique, robuste et élégant. Idéal pour les environnements professionnels. Disponible en gris, jaune et orange. Design moderne et confortable.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Gilet de travail matelassé vert foncé Molinel -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/molinel-1150.jpg" alt="Gilet de travail matelassé vert foncé Molinel" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Gilet de travail matelassé vert foncé Molinel</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(6)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">120 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="48"
              data-name="Gilet de travail matelassé vert foncé Molinel"
              data-price="120"
              data-colors='[
                {"name":"Gris","image":"images/molinel-1150.jpg"}
              ]'
              data-description="Gilet de travail matelassé vert foncé Molinel, léger, chaud et confortable. Idéal pour les environnements professionnels froids. Conçu pour une utilisation intensive.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
               
<!-- Pantalon de Cuisine noir mixte MARMITON LMA -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/lma-marmiton.jpg" alt="Pantalon de Cuisine noir mixte MARMITON LMA" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Pantalon de Cuisine noir mixte MARMITON LMA</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(9)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">200 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="27"
              data-name="Pantalon de Cuisine noir mixte MARMITON LMA"
              data-price="200"
              data-size-type="numeric"
              data-colors='[{"name":"Noir","image":"images/lma-marmiton.jpg"}]'
              data-description="Pantalon de cuisine noir mixte MARMITON LMA, confortable et résistant. Idéal pour les environnements de restauration. Disponible en tailles numériques de 36 à 60.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>


<!-- Veste de Cuisine Mixte NERO Robur -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/robur-nero-manches-courtes.jpg" alt="Veste de Cuisine Mixte NERO Robur" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Veste de Cuisine Mixte NERO Robur</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(10)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">330 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="28"
              data-name="Veste de Cuisine Mixte NERO Robur"
              data-price="330"
              data-colors='[
                {"name":"Noir","image":"images/robur-nero-manches-courtes.jpg"},
                {"name":"Bleu ciel","image":"images/robur-nero-manches-courtes (2).jpg"},
                {"name":"Rouge","image":"images/robur-nero-manches-courtes (4).jpg"},
                {"name":"Jaune","image":"images/robur-nero-manches-courtes (3).jpg"},
                {"name":"Blanc","image":"images/robur-inox-mc (1).jpg"}
              ]'
              data-description="Veste de cuisine mixte NERO Robur. Design professionnel, confortable et respirant. Parfaite pour les environnements de restauration. Disponible en plusieurs couleurs vives.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Veste de cuisine Ripstop® camouflage NERO Robur -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/robur-nero-mc-camouflage.jpg" alt="Veste de cuisine Ripstop® camouflage NERO Robur" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-6%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Veste de cuisine Ripstop® camouflage NERO Robur</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(19)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">436.20 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">410 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="29"
              data-name="Veste de cuisine Ripstop® camouflage NERO Robur"
              data-price="410"
              data-colors='[{"name":"Vert","image":"images/robur-nero-mc-camouflage.jpg"}]'
              data-description="Veste de cuisine Ripstop® camouflage NERO Robur, ultra résistante et respirante. Parfaite pour les environnements professionnels exigeants. Design militaire moderne et confortable.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Pantalon de cuisine TIMEO Robur -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/robur-timeo.jpg" alt="Pantalon de cuisine TIMEO Robur" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Pantalon de cuisine TIMEO Robur</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(19)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">270 MAD</span>
      </div>
      <button class="view-product-btn"
        data-id="30"
        data-name="Pantalon de cuisine TIMEO Robur"
        data-price="270"
        data-size-type="numeric"
        data-colors='[
          {"name":"Gris","image":"images/robur-timeo.jpg"},
          {"name":"Noir","image":"images/robur-timeo (2).jpg"},
          {"name":"Beige","image":"images/robur-timeo (1).jpg"}
        ]'
        data-description="Pantalon de cuisine TIMEO Robur, confortable et résistant. Idéal pour les environnements de restauration. Disponible en plusieurs couleurs élégantes et sobres.">
  <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
</button>
    </div>
  </div>
</div>
<!-- Pantalon de cuisine ARENAL Robur -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/robur-arenal.jpg" alt="Pantalon de cuisine ARENAL Robur" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-2%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Pantalon de cuisine ARENAL Robur</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(19)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">612 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">600 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="31"
              data-name="Pantalon de cuisine ARENAL Robur"
              data-price="600"
              data-size-type="numeric" 
              data-colors='[
                {"name":"Blanc","image":"images/robur-arenal.jpg"},
                {"name":"Beige","image":"images/robur-arenal (2).jpg"},
                {"name":"Brown","image":"images/robur-arenal (1).jpg"},
                {"name":"Noir","image":"images/robur-arenal (4).jpg"},
                {"name":"Gris","image":"images/robur-arenal (3).jpg"}
              ]'
              data-description="Pantalon de cuisine ARENAL Robur, confortable, résistant et élégant. Idéal pour les environnements de restauration. Disponible en plusieurs couleurs sobres et professionnelles.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Combinaison Professionnelle double fermeture LMA -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/lma-fusible-crocq-rondelle.jpg" alt="Combinaison Professionnelle double fermeture LMA" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Combinaison Professionnelle double fermeture LMA</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(9)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">220 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="32"
              data-name="Combinaison Professionnelle double fermeture LMA"
              data-price="220"
              data-colors='[
                {"name":"Vert Gras","image":"images/lma-fusible-crocq-rondelle.jpg"},
                {"name":"Gris","image":"images/lma-fusible-crocq-rondelle (2).jpg"},
                {"name":"Bleu","image":"images/lma-fusible-crocq-rondelle (1).jpg"}
              ]'
              data-description="Combinaison professionnelle LMA avec double fermeture. Conçue pour offrir confort et résistance dans les environnements exigeants. Idéale pour le travail industriel ou artisanal. Disponible en plusieurs couleurs robustes et fonctionnelles.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Baskets de sécurité homme S3L CREMORNE New Balance -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/new-balance-cremorne.jpg" alt="Baskets de sécurité homme S3L CREMORNE New Balance" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Baskets de sécurité homme S3L CREMORNE New Balance</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(5)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">1000 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="33"
              data-name="Baskets de sécurité homme S3L CREMORNE New Balance"
              data-price="1000"
              data-size-type="numeric"
              data-colors='[
                {"name":"Gris","image":"images/new-balance-cremorne.jpg"},
                {"name":"Noir","image":"images/new-balance-cremorne (1).jpg"}
              ]'
              data-description="Baskets de sécurité homme S3L CREMORNE New Balance, conformes aux normes de protection élevées. Légères, respirantes et antidérapantes. Idéales pour les environnements industriels ou de construction. Disponibles en gris et noir.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Bermuda de travail renforcé LMA -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/lma-calcaire.jpg" alt="Bermuda de travail renforcé LMA" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Bermuda de travail renforcé LMA</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(13)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">360 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="34"
              data-name="Bermuda de travail renforcé LMA"
              data-price="360"
              data-size-type="numeric"
              data-colors='[
                {"name":"Noir","image":"images/lma-calcaire.jpg"},
                {"name":"Vert Gras","image":"images/lma-calcaire (1).jpg"}
              ]'
              data-description="Bermuda de travail renforcé LMA, conçu pour les environnements professionnels exigeants. Tissu résistant et confortable, idéal pour le travail quotidien. Disponible en noir et vert gras.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweatshirt de Travail mixte BASIC ROUNDNECK Clique -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/clique-basic-roundneck.jpg" alt="Sweatshirt de Travail mixte BASIC ROUNDNECK Clique" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweatshirt de Travail mixte BASIC ROUNDNECK Clique</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(6)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">180 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="35"
              data-name="Sweatshirt de Travail mixte BASIC ROUNDNECK Clique"
              data-price="180"
              data-colors='[
                {"name":"Blanc","image":"images/clique-basic-roundneck (1).jpg"},
                {"name":"Bleu ciel","image":"images/clique-basic-roundneck (2).jpg"},
                {"name":"Rouge","image":"images/clique-basic-roundneck (3).jpg"},
                {"name":"Noir","image":"images/clique-basic-roundneck (11).jpg"},
                {"name":"Gris","image":"images/clique-basic-roundneck (9).jpg"},
                {"name":"Jaune","image":"images/clique-basic-roundneck (10).jpg"}
              ]'
              data-description="Sweatshirt de travail en coton, idéal pour tous les jours. Coupe ajustée et confortable. Disponible en plusieurs couleurs élégantes et professionnelles.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Pantalon de travail 100% Coton ESSENTIELS Cepovett -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/cepovett-9027-9062.jpg" alt="Pantalon de travail 100% Coton ESSENTIELS Cepovett" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-2%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Pantalon de travail 100% Coton ESSENTIELS Cepovett</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(11)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">132.60 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">130 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="36"
              data-name="Pantalon de travail 100% Coton ESSENTIELS Cepovett"
              data-price="130"
              data-size-type="numeric"
              data-colors='[
                {"name":"Blanc","image":"images/cepovett-9027-9062.jpg"},
                {"name":"Bleu","image":"images/cepovett-9027-9062 (1).jpg"},
                {"name":"Bleu ciel","image":"images/cepovett-9027-9062 (2).jpg"},
                {"name":"Gris","image":"images/cepovett-9027-9062 (3).jpg"},
                {"name":"Vert","image":"images/cepovett-9027-9062 (4).jpg"}
              ]'
              data-description="Pantalon de travail 100% coton ESSENTIELS Cepovett, confortable, résistant et respirant. Idéal pour les environnements professionnels. Disponible en plusieurs couleurs sobres et fonctionnelles.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Polo pro manches longues CLASSIC LINCOLN Clique -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/clique-classic-lincoln-ml-028245 (7).jpg" alt="Polo pro manches longues CLASSIC LINCOLN Clique" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Polo pro manches longues CLASSIC LINCOLN Clique</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(9)</span>
    </div>
    <div class="flex items-center justify-between">
      <div><span class="text-indigo-600 font-bold">209.99 MAD</span></div>
      <button class="view-product-btn"
              data-id="11"
              data-name="Polo pro manches longues CLASSIC LINCOLN Clique"
              data-price="209.99"
              data-colors='[
                {"name":"Noir","image":"images/clique-classic-lincoln-ml-028245 (7).jpg"},
                {"name":"Blanc","image":"images/clique-classic-lincoln-ml-028245.jpg"},
                {"name":"Vert","image":"images/clique-classic-lincoln-ml-028245 (10).jpg"},
                {"name":"Vert Gras","image":"images/clique-classic-lincoln-ml-028245 (9).jpg"},
                {"name":"Rouge","image":"images/clique-classic-lincoln-ml-028245 (8).jpg"},
                {"name":"Gris","image":"images/clique-classic-lincoln-ml-028245 (5).jpg"},
                {"name":"Bleu","image":"images/clique-classic-lincoln-ml-028245 (3).jpg"}
              ]'
              data-description="Polo professionnel manches longues, idéal pour le travail en extérieur. Tissu résistant et respirant, disponible en plusieurs couleurs vives.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweat de travail à capuche BASIC HOODY Clique -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/clique-021031 (3).jpg" alt="Sweat de travail à capuche BASIC HOODY Clique" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-5%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweat de travail à capuche BASIC HOODY Clique</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(8)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">230 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">218.50 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="12"
              data-name="Sweat de travail à capuche BASIC HOODY Clique"
              data-price="218.50"
              data-colors='[
                {"name":"Noir","image":"images/clique-021031 (3).jpg"},
                {"name":"Blanc","image":"images/clique-021031.jpg"},
                {"name":"Vert","image":"images/clique-021031 (7).jpg"},
                {"name":"Jaune","image":"images/clique-021031 (2).jpg"},
                {"name":"Rouge","image":"images/clique-021031 (6).jpg"},
                {"name":"Rose","image":"images/clique-021031 (5).jpg"},
                {"name":"Bleu ciel","image":"images/clique-021031 (1).jpg"},
                {"name":"Orange","image":"images/clique-021031 (4).jpg"}
              ]'
              data-description="Sweat de travail résistant et confortable avec capuche, idéal pour les environnements professionnels. Tissu épais et respirant, disponible en plusieurs couleurs vives.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- T-Shirt professionnel homme en coton ARGO Herocket -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/herock-argo (3).jpg" alt="T-Shirt professionnel homme en coton ARGO Herocket" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-20%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">T-Shirt professionnel homme en coton ARGO Herocket</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(12)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">180 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">144 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="13"
              data-name="T-Shirt professionnel homme en coton ARGO Herocket"
              data-price="144"
              data-colors='[
                {"name":"Noir","image":"images/herock-argo (3).jpg"},
                {"name":"Blanc","image":"images/herock-argo.jpg"},

                {"name":"Gris","image":"images/herock-argo (2).jpg"}
              ]'
              data-description="T-shirt professionnel en coton résistant et respirant, idéal pour le travail quotidien. Coupe ajustée et confortable. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweat professionnel zippé CLASSIC Helly Hansen -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/helly-hansen-79326 (3).jpg" alt="Sweat professionnel zippé CLASSIC Helly Hansen" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-27%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweat professionnel zippé CLASSIC Helly Hansen</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(15)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">520 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">379.60 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="14"
              data-name="Sweat professionnel zippé CLASSIC Helly Hansen"
              data-price="379.60"
              data-colors='[
                {"name":"Rouge","image":"images/helly-hansen-79326 (3).jpg"},
                {"name":"Blanc","image":"images/helly-hansen-79326.jpg"},
                {"name":"Gris","image":"images/helly-hansen-79326 (2).jpg"},
                {"name":"Bleu ciel","image":"images/helly-hansen-79326 (1).jpg"}
              ]'
              data-description="Sweat professionnel zippé résistant et chaud, idéal pour les environnements de travail extérieurs. Coupe ajustée, tissu respirant et thermorégulateur. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Doudoune professionnelle femme recyclée IDAHO Clique -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/clique-0200977 (2).jpg" alt="Doudoune professionnelle femme recyclée IDAHO Clique" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-4%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Doudoune professionnelle femme recyclée IDAHO Clique</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(1)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">660 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">632.40 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="15"
              data-name="Doudoune professionnelle femme recyclée IDAHO Clique"
              data-price="632.40"
              data-colors='[
                {"name":"Noir","image":"images/clique-0200977 (2).jpg"},
                {"name":"Violet","image":"images/clique-0200977.jpg"},
                {"name":"Bleu","image":"images/clique-0200977 (1).jpg"}
              ]'
              data-description="Doudoune professionnelle en matériau recyclé, idéale pour les environnements extérieurs. Coupe ajustée et thermorégulatrice. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Casque de chantier ventilé EVO 2 JSP -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/jsp-evo2.jpg" alt="Casque de chantier ventilé EVO 2 JSP" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Casque de chantier ventilé EVO 2 JSP</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(14)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">60 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="38"
              data-name="Casque de chantier ventilé EVO 2 JSP"
              data-price="60"
              data-colors='[
                {"name":"Blanc","image":"images/jsp-evo2.jpg"},
                {"name":"Bleu","image":"images/jsp-evo2 (1).jpg"},
                {"name":"Jaune","image":"images/jsp-evo2 (2).jpg"},
                {"name":"Noir","image":"images/jsp-evo2 (3).jpg"},
                {"name":"Rouge","image":"images/jsp-evo2 (4).jpg"}
              ]'
              data-description="Casque de chantier ventilé EVO 2 JSP, léger, robuste et confortable. Idéal pour les environnements industriels et de construction. Disponible en plusieurs couleurs résistantes et fonctionnelles.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
    <!-- Bermuda de travail peintre bicolore PASTEL LMA -->
     <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/lma-pastel.jpg" alt="Bermuda de travail peintre bicolore PASTEL LMA" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-2%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Bermuda de travail peintre bicolore PASTEL LMA</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(3)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">173.50 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">170 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="39"
              data-name="Bermuda de travail peintre bicolore PASTEL LMA"
              data-price="170"
              data-size-type="numeric"
              data-colors='[
                {"name":"Blanc","image":"images/lma-pastel.jpg"}
              ]'
              data-description="Bermuda de travail peintre bicolore PASTEL LMA, léger, confortable et résistant. Idéal pour les travaux de peinture ou les environnements industriels. Disponible en tailles numériques de 36 à 60.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweatshirt polaire professionnel ATLANTA LMA -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/lma-atlanta.jpg" alt="Sweatshirt polaire professionnel ATLANTA LMA" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweatshirt polaire professionnel ATLANTA LMA</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(9)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">177 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="40"
              data-name="Sweatshirt polaire professionnel ATLANTA LMA"
              data-price="177"
              data-colors='[
                {"name":"Gris","image":"images/lma-atlanta.jpg"},
                {"name":"Noir","image":"images/lma-atlanta (1).jpg"}
              ]'
              data-description="Sweatshirt polaire professionnel ATLANTA LMA, idéal pour les environnements froids. Tissu doux, chaud et résistant. Parfait pour l'hiver ou les environnements professionnels. Disponible en gris et noir.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/cepovett-9b54-8680.jpg" alt="Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-3%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(11)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">500 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">485 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="41"
              data-name="Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett"
              data-price="485"
              data-size-type="numeric"
              data-colors='[
                {"name":"Bleu","image":"images/cepovett-9b54-8680.jpg"}
              ]'
              data-description="Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett, conçu pour les environnements dangereux et explosifs. Résistant, confortable et conforme aux normes de sécurité les plus élevées. Disponible en bleu.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Combinaison de travail double zip LMA -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/lma-fusible-crocq-rondelle.jpg" alt="Combinaison de travail double zip LMA" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Combinaison de travail double zip LMA</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(13)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">330 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="42"
              data-name="Combinaison de travail double zip LMA"
              data-price="330"
              data-colors='[
                {"name":"Vert Gras","image":"images/lma-fusible-crocq-rondelle.jpg"},
                {"name":"Bleu","image":"images/lma-fusible-crocq-rondelle (1).jpg"},
                {"name":"Gris","image":"images/lma-fusible-crocq-rondelle (2).jpg"}
              ]'
              data-description="Combinaison de travail double zip LMA, robuste et confortable, idéale pour les environnements professionnels. Fermeture pratique et résistante. Disponible en plusieurs couleurs fonctionnelles et sobres.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- T-shirt haute visibilité classe 2 SUZE Singer Safety -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/singer-safety-suze.jpg" alt="T-shirt haute visibilité classe 2 SUZE Singer Safety" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">T-shirt haute visibilité classe 2 SUZE Singer Safety</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(10)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">120 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="43"
              data-name="T-shirt haute visibilité classe 2 SUZE Singer Safety"
              data-price="120"
              data-colors='[
                {"name":"Orange","image":"images/singer-safety-suze.jpg"},
                {"name":"Jaune","image":"images/singer-safety-suze (1).jpg"}
              ]'
              data-description="T-shirt haute visibilité classe 2 SUZE Singer Safety, conforme aux normes de sécurité élevées. Idéal pour les environnements industriels ou de construction. Disponible en orange et jaune pour une visibilité optimale.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
               
                
                <!-- Product 5 -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=684&q=80" alt="T-shirt homme" class="w-full h-64 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">T-shirt basique</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="text-gray-600 text-sm ml-2">(15)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-indigo-600 font-bold">129 MAD</span>
                            </div>
                            <button class="view-product-btn" data-id="5" data-name="T-shirt basique" data-price="129" data-colors='[{"name":"Blanc","image":"https://images.unsplash.com/photo-1527719327859-c6ce80353573?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=684&q=80"}]' data-description="T-shirt basique en coton pour homme. Coupe classique et confortable pour un usage quotidien.">
                                <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Product 6 -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1373&q=80" alt="Jupe femme" class="w-full h-64 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-1">Jupe plissée</h3>
                        <div class="flex items-center mb-2">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="text-gray-600 text-sm ml-2">(12)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-indigo-600 font-bold">199 MAD</span>
                            </div>
                            <button class="view-product-btn" data-id="6" data-name="Jupe plissée" data-price="199" data-colors='[{"name":"Noir","image":"https://images.unsplash.com/photo-1585487000160-6ebcfceb0d03?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1373&q=80"}]' data-description="Jupe plissée élégante pour femme. Longueur mi-mollet pour un style chic et moderne.">
                                <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Sweat professionnel à capuche logotypé Carhartt -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-100074.jpg" alt="Sweat professionnel à capuche logotypé Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-31%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweat professionnel à capuche logotypé Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(6)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">700 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">483 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="19"
              data-name="Sweat professionnel à capuche logotypé Carhartt"
              data-price="483"
              data-colors='[
                {"name":"Gris","image":"images/carhartt-100074 (2).jpg"},
                {"name":"Noir","image":"images/carhartt-100074.jpg"},
                {"name":"Bleu ciel","image":"images/carhartt-100074 (1).jpg"},
                {"name":"Marron","image":"images/carhartt-100074 (3).jpg"}
              ]'
              data-description="Sweat professionnel à capuche de la marque Carhartt. Design robuste et élégant, idéal pour les environnements de travail extérieurs. Disponible en plusieurs couleurs résistantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweat de travail zippé à capuche Carhartt -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-k122 (1).jpg" alt="Sweat de travail zippé à capuche Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-21%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweat de travail zippé à capuche Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(24)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">700 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">553 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="20"
              data-name="Sweat de travail zippé à capuche Carhartt"
              data-price="553"
              data-colors='[
                {"name":"Gris","image":"images/carhartt-k122 (1).jpg"},
                {"name":"Blanc","image":"images/carhartt-k122 (2).jpg"},
                {"name":"Noir","image":"images/carhartt-k122.jpg"}
              ]'
              data-description="Sweat zippé à capuche robuste et chaud, conçu pour les environnements de travail extérieurs. Coupe ajustée, tissu respirant et thermorégulateur. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweat de travail à col rond Carhartt -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-k124.jpg" alt="Sweat de travail à col rond Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweat de travail à col rond Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(7)</span>
    </div>
    <div class="flex items-center justify-between">
      <div><span class="text-indigo-600 font-bold">550 MAD</span></div>
      <button class="view-product-btn"
              data-id="21"
              data-name="Sweat de travail à col rond Carhartt"
              data-price="550"
              data-colors='[
                {"name":"Violet","image":"images/carhartt-k124.jpg"},
                {"name":"Noir","image":"images/carhartt-k124 (1).jpg"}
              ]'
              data-description="Sweat de travail robuste et confortable à col rond, conçu pour résister à l'usure quotidienne. Idéal pour les environnements professionnels. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
        <!-- Tee shirt professionnel manches longues avec logo Carhartt -->
         <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-ek231.jpg" alt="Tee shirt professionnel manches longues avec logo Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-5%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Tee shirt professionnel manches longues avec logo Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(12)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">300 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">285 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="22"
              data-name="Tee shirt professionnel manches longues avec logo Carhartt"
              data-price="285"
              data-colors='[
                {"name":"Blanc","image":"images/carhartt-ek231.jpg"},
                {"name":"Gris","image":"images/carhartt-ek231 (1).jpg"},
                {"name":"Marron","image":"images/carhartt-ek231 (2).jpg"},
                {"name":"Bleu","image":"images/carhartt-ek231 (3).jpg"}
              ]'
              data-description="Tee shirt professionnel manches longues avec logo Carhartt. Tissu résistant et confortable, idéal pour les environnements de travail. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
                
                <!-- Bermuda de travail bicolore LMA -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
    <div class="relative">
        <img src="images/lma-fondeur-iridium.jpg" alt="Bermuda de travail bicolore LMA" class="w-full h-64 object-cover">
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-1">Bermuda de travail bicolore LMA</h3>
        <div class="flex items-center mb-2">
            <div class="flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <i class="far fa-star"></i>
            </div>
            <span class="text-gray-600 text-sm ml-2">(8)</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-indigo-600 font-bold">150 MAD</span>
            </div>
            <button class="view-product-btn" data-id="9" 
                    data-name="Bermuda de travail bicolore LMA"
                    data-price="150"
                    data-colors='[
                        {"name":"Brown","image":"images/lma-fondeur-iridium.jpg"},
                        {"name":"Green","image":"images/lma-fondeur-iridium (2).jpg"},
                        {"name":"Blue","image":"images/lma-fondeur-iridium (1).jpg"}
                    ]'
                    data-description="Bermuda de travail bicolore LMA, conçu pour offrir confort et durabilité sur les chantiers. Disponible en plusieurs couleurs élégantes adaptées à tous les environnements professionnels.">
                <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
            </button>
        </div>
    </div>
</div>
<!-- Bermuda Haute Visibilité DIGGER Lafont -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/bermuda-lafont-hs1236m5-digger.jpg" alt="Bermuda Haute Visibilité DIGGER Lafont" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Bermuda Haute Visibilité DIGGER Lafont</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(5)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">100 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="44"
              data-name="Bermuda Haute Visibilité DIGGER Lafont"
              data-price="100"
              data-size-type="numeric"
              data-colors='[
                {"name":"Jaune","image":"images/bermuda-lafont-hs1236m5-digger.jpg"}
              ]'
              data-description="Bermuda haute visibilité DIGGER Lafont, conçu pour les environnements industriels ou de construction. Tissu résistant et léger, avec bandes réfléchissantes pour une sécurité optimale. Disponible en jaune.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Pantalon anticoupure classe 1A AUTHENTIC Solidur -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/solidur-aupa1a.jpg" alt="Pantalon anticoupure classe 1A AUTHENTIC Solidur" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Pantalon anticoupure classe 1A AUTHENTIC Solidur</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(19)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">740 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="45"
              data-name="Pantalon anticoupure classe 1A AUTHENTIC Solidur"
              data-price="740"
              data-size-type="numeric"
              data-colors='[
                {"name":"Gris","image":"images/solidur-aupa1a.jpg"},
                {"name":"Rouge","image":"images/solidur-aupa1a (1).jpg"}
              ]'
              data-description="Pantalon anticoupure classe 1A AUTHENTIC Solidur, conçu pour les environnements dangereux. Protection élevée, confort et résistance optimale. Idéal pour les professionnels de l’industrie ou de la construction. Disponible en gris et rouge.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Tee Shirt pro homme manches longues NOET Herock -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/herock-noet.jpg" alt="Tee Shirt pro homme manches longues NOET Herock" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-yellow-400 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Tee Shirt pro homme manches longues NOET Herock</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(2)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">168 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="46"
              data-name="Tee Shirt pro homme manches longues NOET Herock"
              data-price="168"
              data-colors='[
                {"name":"Bleu Gras","image":"images/herock-noet.jpg"},
                {"name":"Gris","image":"images/herock-noet (1).jpg"},
                {"name":"Noir","image":"images/herock-noet (2).jpg"}
              ]'
              data-description="Tee-shirt professionnel manches longues NOET Herock, conçu pour un usage intensif. Tissu épais, résistant et confortable. Idéal pour les environnements de travail. Disponible en bleu gras, gris et noir.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Sweatshirt de Travail mixte BASIC ROUNDNECK Clique -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/clique-basic-roundneck.jpg" alt="Sweatshirt de Travail mixte BASIC ROUNDNECK Clique" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-10%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Sweatshirt de Travail mixte BASIC ROUNDNECK Clique</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(24)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">200 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">180 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="17"
              data-name="Sweatshirt de Travail mixte BASIC ROUNDNECK Clique"
              data-price="180"
              data-colors='[
                {"name":"Grenat","image":"images/clique-basic-roundneck.jpg"},
                {"name":"Rouge","image":"images/clique-basic-roundneck (3).jpg"},
                {"name":"Noir","image":"images/clique-basic-roundneck (4).jpg"},
                {"name":"Bleu ciel","image":"images/clique-basic-roundneck (2).jpg"},
                {"name":"Blanc","image":"images/clique-basic-roundneck (1).jpg"}
              ]'
              data-description="Sweatshirt de travail en coton, idéal pour tous les jours. Coupe ajustée et confortable. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- T-Shirt de travail avec logo Carhartt -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-103361.jpg" alt="T-Shirt de travail avec logo Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-9%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">T-Shirt de travail avec logo Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(2)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">250 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">227.50 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="18"
              data-name="T-Shirt de travail avec logo Carhartt"
              data-price="227.50"
              data-colors='[
                {"name":"Gris","image":"images/carhartt-103361.jpg"},
                {"name":"Noir","image":"images/carhartt-103361 (1).jpg"},
                {"name":"Bleu","image":"images/carhartt-103361 (2).jpg"},
                {"name":"Blanc","image":"images/clique-basic-roundneck (1).jpg"}
              ]'
              data-description="T-shirt de travail robuste avec logo Carhartt, idéal pour les environnements professionnels. Coupe classique et tissu résistant. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
                <!-- Product 9 - T-shirt FIGI U-Power -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
    <div class="relative">
        <img src="images/u-power-figi.jpg" alt="T-shirt de travail FIGI U-Power" class="w-full h-64 object-cover">
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-1">T-shirt de travail 100% coton FIGI U-Power</h3>
        <div class="flex items-center mb-2">
            <div class="flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="far fa-star"></i>
            </div>
            <span class="text-gray-600 text-sm ml-2">(10)</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-indigo-600 font-bold">50 MAD</span>
            </div>
            <button class="view-product-btn" 
                    data-id="9" 
                    data-name="T-shirt de travail 100% coton FIGI U-Power" 
                    data-price="50" 
                    data-colors='[
                        {"name":"Blanc","image":"images/u-power-figi.jpg", "color":"#ffffff"},
                        {"name":"Noire","image":"images/u-power-figi (4).jpg", "color":"#000000"},
                        {"name":"Gris","image":"images/u-power-figi (3).jpg", "color":"#808080"},
                        {"name":"Bleu ciel","image":"images/u-power-figi (2).jpg", "color":"#87CEEB"},
                        {"name":"Bleu gras","image":"images/u-power-figi (1).jpg", "color":"#1E3A8A"}
                    ]' 
                    data-description="T-shirt de travail haute qualité 100% coton de la marque FIGI U-Power. Confortable et résistant, parfait pour le travail au quotidien. Disponible en plusieurs couleurs.">
                <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
            </button>
        </div>
    </div>
</div>
<!-- Tee shirt de travail manches longues avec logo Carhartt -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-104891 (1).jpg" alt="Tee shirt de travail manches longues avec logo Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Nouveau</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Tee shirt de travail manches longues avec logo Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="far fa-star"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(1)</span>
    </div>
    <div class="flex items-center justify-between">
      <div><span class="text-indigo-600 font-bold">310 MAD</span></div>
      <button class="view-product-btn"
              data-id="23"
              data-name="Tee shirt de travail manches longues avec logo Carhartt"
              data-price="310"
              data-colors='[
                {"name":"Blanc","image":"images/carhartt-104891 (1).jpg"},
                {"name":"Bleu","image":"images/carhartt-104891 (2).jpg"},
                {"name":"Marron","image":"images/carhartt-104891.jpg"}
              ]'
              data-description="Tee shirt de travail manches longues avec logo Carhartt. Tissu résistant et confortable, idéal pour les environnements professionnels. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- T-shirt de travail avec poche poitrine Carhartt -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/carhartt-103296.jpg" alt="T-shirt de travail avec poche poitrine Carhartt" class="w-full h-64 object-cover">
    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">-7%</div>
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">T-shirt de travail avec poche poitrine Carhartt</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(5)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-gray-500 line-through text-sm">236.50 MAD</span>
        <span class="text-indigo-600 font-bold ml-2">220 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="24"
              data-name="T-shirt de travail avec poche poitrine Carhartt"
              data-price="220"
              data-colors='[
                {"name":"Blanc","image":"images/carhartt-103296.jpg"},
                {"name":"Bleu","image":"images/carhartt-103296 (1).jpg"},
                {"name":"Noir","image":"images/carhartt-103296 (2).jpg"},
                {"name":"Rouge","image":"images/carhartt-103296 (3).jpg"},
                {"name":"Jaune","image":"images/carhartt-103296 (4).jpg"}
              ]'
              data-description="T-shirt de travail avec poche poitrine Carhartt. Tissu résistant et confortable, idéal pour les environnements professionnels. Disponible en plusieurs couleurs élégantes.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Gilet haute visibilité respirant multipoches MADRID Portwest -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
  <div class="relative">
    <img src="images/portwest-madrid.jpg" alt="Gilet haute visibilité respirant multipoches MADRID Portwest" class="w-full h-64 object-cover">
  </div>
  <div class="p-4">
    <h3 class="font-semibold text-lg mb-1">Gilet haute visibilité respirant multipoches MADRID Portwest</h3>
    <div class="flex items-center mb-2">
      <div class="flex text-yellow-400">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star-half-alt"></i>
      </div>
      <span class="text-gray-600 text-sm ml-2">(17)</span>
    </div>
    <div class="flex items-center justify-between">
      <div>
        <span class="text-indigo-600 font-bold">100 MAD</span>
      </div>
      <button class="view-product-btn"
              data-id="37"
              data-name="Gilet haute visibilité respirant multipoches MADRID Portwest"
              data-price="100"
              data-colors='[
                {"name":"Orange","image":"images/portwest-madrid.jpg"},
                {"name":"Jaune","image":"images/portwest-madrid (1).jpg"}
              ]'
              data-description="Gilet haute visibilité respirant multipoches MADRID Portwest, idéal pour les environnements industriels ou de construction. Confortable, léger et très résistant. Disponible en orange et jaune pour une visibilité optimale.">
        <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
      </button>
    </div>
  </div>
</div>
<!-- Pantalon de Travail SCIE LMA -->
 <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn"
     data-price="100"
     data-date="2024-07-10"
     data-rating="4.5">
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow animate-slideIn">
    <div class="relative">
        <img src="images/lma-scie-truelle-perceuse (1).jpg" alt="Pantalon de Travail SCIE LMA" class="w-full h-64 object-cover">
    </div>
    <div class="p-4">
        <h3 class="font-semibold text-lg mb-1">Pantalon de Travail SCIE LMA</h3>
        <div class="flex items-center mb-2">
            <div class="flex text-yellow-400">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <i class="far fa-star"></i>
            </div>
            <span class="text-gray-600 text-sm ml-2">(7)</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <span class="text-indigo-600 font-bold">210 MAD</span>
            </div>
            <button class="view-product-btn" data-id="9"
                    data-name="Pantalon de Travail SCIE LMA"
                    data-price="210"
                    data-colors='[
                        {"name":"Brown","image":"images/lma-scie-truelle-perceuse (1).jpg"},
                        {"name":"Vert","image":"images/lma-scie-truelle-perceuse (3).jpg"},
                        {"name":"Bleu","image":"images/lma-scie-truelle-perceuse (2).jpg"},
                        {"name":"Gris","image":"images/lma-scie-truelle-perceuse.jpg"}
                    ]'
                    data-description="Pantalon de travail robuste et confortable, conçu pour les professionnels exigeants. Disponible en plusieurs couleurs résistantes et adaptées aux environnements difficiles.">
                <i class="fas fa-eye text-indigo-600 hover:text-indigo-800"></i>
            </button>
        </div>
    </div>
</div>
                
                
            </div>
            
            <div class="mt-8 text-center">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700">Voir plus de produits</button>
            </div>
        </div>
    </section>

    <!-- Pagination -->
<div class="flex justify-center mt-8">
  <div id="pagination" class="inline-flex items-center space-x-2"></div>
</div>
    

    <!-- Product Modal -->
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
  <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 product-modal">
    <div class="relative">
      <button id="close-modal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times text-2xl"></i>
      </button>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
        <div>
          <img id="modal-product-image" src="" alt="Product" class="w-full rounded-lg">
          <div class="flex mt-4 space-x-2" id="color-options">
            <!-- Color options will be added here dynamically -->
          </div>
        </div>
        <div>
          <h2 id="modal-product-name" class="text-2xl font-bold mb-2"></h2>

          <div class="mb-4">
            <span id="modal-product-price" class="text-2xl font-bold text-indigo-600"></span>
          </div>

          <p id="modal-product-description" class="text-gray-700 mb-6"></p>

          <!-- Tailles S à XXL -->
          <div class="mb-6">
            <h3 class="font-semibold mb-2">Taille:</h3>
            <div class="flex flex-wrap gap-2" id="size-options">
              <!-- Les tailles seront générées dynamiquement -->
            </div>
          </div>

          <!-- Quantité et bouton Ajouter au panier -->
          <div class="flex items-center mb-6">
            <div class="flex items-center border rounded-md mr-4">
              <button id="decrease-qty" class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
              <span id="product-qty" class="px-4 py-2">1</span>
              <button id="increase-qty" class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
            </div>
            <button id="add-to-cart-btn" class="bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 flex-1">
              Ajouter au panier
            </button>
          </div>

          <!-- Informations de livraison -->
          <div class="border-t pt-4">
            <div class="flex items-center text-gray-600 mb-2">
              <i class="fas fa-truck mr-2"></i>
              <span>Livraison gratuite à partir de 500 MAD</span>
            </div>
            <div class="flex items-center text-gray-600">
              <i class="fas fa-undo mr-2"></i>
              <span>Retours gratuits sous 14 jours</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- Features Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-truck text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Livraison Rapide</h3>
                    <p class="text-gray-600">Livraison express dans tout le Maroc en 2-3 jours ouvrables.</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Paiement Sécurisé</h3>
                    <p class="text-gray-600">Transactions 100% sécurisées avec cryptage SSL.</p>
                </div>
                <div class="text-center">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Support 24/7</h3>
                    <p class="text-gray-600">Notre équipe est disponible pour répondre à vos questions.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Abonnez-vous à notre newsletter</h2>
            <p class="text-gray-600 mb-6 max-w-2xl mx-auto">Recevez les dernières tendances, offres spéciales et réductions directement dans votre boîte de réception.</p>
            <div class="max-w-md mx-auto flex">
                <input type="email" placeholder="Votre email" class="flex-1 px-4 py-3 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button class="bg-indigo-600 text-white px-6 py-3 rounded-r-md font-semibold hover:bg-indigo-700">S'abonner</button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Multi-Confection</h3>
                    <p class="text-gray-400">La meilleure destination pour la mode marocaine en ligne. Qualité, style et confort à des prix abordables.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Accueil</a></li>
                        <li><a href="#products" class="text-gray-400 hover:text-white">Produits</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Nouveautés</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Promotions</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Informations</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">À propos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Livraison</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Politique de retour</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Conditions générales</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> 123 Rue de la Mode, Casablanca</li>
                        <li class="flex items-center"><i class="fas fa-phone mr-2"></i> +212 6 12 34 56 78</li>
                        <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> contact@modemaroc.com</li>
                    </ul>
                    <div class="mt-4 flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 Multi-Confection. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
    // Fonction pour obtenir la couleur en hexadécimal
    function getColorHex(colorName) {
        const colors = {
            'Blanc': '#ffffff',
            'Noir': '#000000',
            'Bleu': '#3b82f6',
            'Bleu ciel': '#38bdf8',
            'Rouge': '#ef4444',
            'Vert': '#10b981',
            'Jaune': '#f59e0b',
            'Orange': '#f97316',
            'Gris': '#6b7280',
            'Marron': '#78350f',
            'Violet': '#8a2be2',
            'Beige': '#f5f5dc',
            'Vert Gras': '#0a7856'
        };
        return colors[colorName] || '#cccccc';
    }

    // Variables globales
    let currentProduct = null;
    let currentColor = null;
    let selectedSize = null;
    let cart = [];

    const productsPerPage = 12;
    let currentPage = 1;
    let allProducts = [];

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Cart dropdown toggle
    const cartBtn = document.getElementById('cart-btn');
    const cartDropdown = document.getElementById('cart-dropdown');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');

    if (cartBtn && cartDropdown) {
        cartBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            cartDropdown.classList.toggle('hidden');
        });

        // Close cart dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!cartDropdown.contains(e.target) && e.target !== cartBtn) {
                cartDropdown.classList.add('hidden');
            }
        });
    }

    // Product modal elements
    const productModal = document.getElementById('product-modal');
    const closeModal = document.getElementById('close-modal');
    const modalProductImage = document.getElementById('modal-product-image');
    const modalProductName = document.getElementById('modal-product-name');
    const modalProductPrice = document.getElementById('modal-product-price');
    const modalProductDescription = document.getElementById('modal-product-description');
    const colorOptionsContainer = document.getElementById('color-options');
    const decreaseQty = document.getElementById('decrease-qty');
    const increaseQty = document.getElementById('increase-qty');
    const productQty = document.getElementById('product-qty');
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    // Generate size buttons (S to XXL or 36 to 60)
    function generateSizeButtons(sizeType = 'alpha') {
        const sizeContainer = document.getElementById('size-options');
        sizeContainer.innerHTML = '';
        let sizes = [];

        if (sizeType === 'numeric') {
            // Pantalons : 36 à 60 (par 2)
            for (let i = 36; i <= 60; i += 2) {
                sizes.push(i.toString());
            }
        } else {
            // T-shirts : S, M, L, XL, XXL
            sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        }

        sizes.forEach(size => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = size;
            btn.className = 'px-4 py-2 border rounded-md hover:bg-gray-100 size-option';
            btn.dataset.size = size;
            btn.addEventListener('click', () => {
                document.querySelectorAll('#size-options button').forEach(b => {
                    b.classList.remove('bg-indigo-600', 'text-white');
                });
                btn.classList.add('bg-indigo-600', 'text-white');
                selectedSize = size;
            });
            sizeContainer.appendChild(btn);
        });

        if (sizes.length > 0) {
            selectedSize = sizes[0];
            sizeContainer.querySelector('button').classList.add('bg-indigo-600', 'text-white');
        }
    }

    // Load products per page
    function loadProductsPage(page) {
        const productContainer = document.querySelector('#products .grid');
        if (!productContainer) return;

        productContainer.innerHTML = '';

        const start = (page - 1) * productsPerPage;
        const end = start + productsPerPage;
        const productsToShow = allProducts.slice(start, end);

        productsToShow.forEach(product => {
            productContainer.appendChild(product.element);
        });

        updatePagination();
    }

    // Update pagination
    function updatePagination() {
        const paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) return;

        paginationContainer.innerHTML = '';

        const totalPages = Math.ceil(allProducts.length / productsPerPage);

        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.textContent = '←';
        prevBtn.className = 'px-3 py-1 rounded-md border hover:bg-gray-100';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadProductsPage(currentPage);
            }
        });
        paginationContainer.appendChild(prevBtn);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded-md border hover:bg-gray-100 ${i === currentPage ? 'bg-indigo-600 text-white' : ''}`;
            btn.addEventListener('click', () => {
                currentPage = i;
                loadProductsPage(currentPage);
            });
            paginationContainer.appendChild(btn);
        }

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.textContent = '→';
        nextBtn.className = 'px-3 py-1 rounded-md border hover:bg-gray-100';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                loadProductsPage(currentPage);
            }
        });
        paginationContainer.appendChild(nextBtn);
    }

    // Register all products
    document.querySelectorAll('.view-product-btn').forEach(btn => {
        const productElement = btn.closest('.bg-white');
        const productId = btn.getAttribute('data-id');
        const productName = btn.getAttribute('data-name');
        const productPrice = btn.getAttribute('data-price');
        const productDescription = btn.getAttribute('data-description');
        const colors = JSON.parse(btn.getAttribute('data-colors'));
        const sizeType = btn.getAttribute('data-size-type') || 'alpha';

        allProducts.push({
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            description: productDescription,
            colors: colors,
            sizeType: sizeType,
            element: productElement.cloneNode(true)
        });

        // Hide product initially for pagination
        productElement.style.display = 'none';
    });

    // Load first page
    loadProductsPage(currentPage);

    // Open product modal
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.view-product-btn');
        if (!btn) return;

        const productId = btn.getAttribute('data-id');
        const productName = btn.getAttribute('data-name');
        const productPrice = btn.getAttribute('data-price');
        const productDescription = btn.getAttribute('data-description');
        const colors = JSON.parse(btn.getAttribute('data-colors'));
        const sizeType = btn.getAttribute('data-size-type') || 'alpha';

        currentProduct = {
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            description: productDescription,
            colors: colors
        };

        currentColor = colors[0];

        // Update modal content
        modalProductName.textContent = productName;
        modalProductPrice.textContent = productPrice + ' MAD';
        modalProductDescription.textContent = productDescription;
        modalProductImage.src = colors[0].image;
        productQty.textContent = '1';

        // Generate sizes
        generateSizeButtons(sizeType);

        // Create color options
        colorOptionsContainer.innerHTML = '';
        colors.forEach((color, index) => {
            const colorOption = document.createElement('div');
            colorOption.className = `w-10 h-10 rounded-full cursor-pointer color-option ${index === 0 ? 'selected' : ''}`;
            colorOption.style.backgroundColor = getColorHex(color.name);
            colorOption.setAttribute('data-color-index', index);
            colorOption.setAttribute('title', color.name);
            colorOption.addEventListener('click', () => {
                document.querySelectorAll('.color-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                colorOption.classList.add('selected');
                currentColor = colors[index];
                modalProductImage.src = color.image;
            });
            colorOptionsContainer.appendChild(colorOption);
        });

        // Show modal
        if (productModal) productModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Close product modal
    if (closeModal) {
        closeModal.addEventListener('click', () => {
            if (productModal) productModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
    }

    // Quantity controls
    if (decreaseQty && increaseQty && productQty) {
        decreaseQty.addEventListener('click', () => {
            let qty = parseInt(productQty.textContent);
            if (qty > 1) {
                productQty.textContent = qty - 1;
            }
        });

        increaseQty.addEventListener('click', () => {
            let qty = parseInt(productQty.textContent);
            productQty.textContent = qty + 1;
        });
    }

    // Add to cart
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
            if (!currentProduct || !selectedSize) return;

            const qty = parseInt(productQty.textContent);
            const itemKey = `${currentProduct.id}-${currentColor.name}-${selectedSize}`;

            const existingItem = cart.find(item => item.key === itemKey);

            if (existingItem) {
                existingItem.quantity += qty;
            } else {
                cart.push({
                    key: itemKey,
                    id: currentProduct.id,
                    name: currentProduct.name,
                    price: currentProduct.price,
                    color: currentColor.name,
                    colorHex: getColorHex(currentColor.name),
                    image: currentColor.image,
                    size: selectedSize,
                    quantity: qty
                });
            }

            updateCartUI();
            if (productModal) productModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            alert('Produit ajouté au panier !');
        });
    }

    // Update cart UI
    function updateCartUI() {
        if (!cartItemsContainer || !cartCount || !cartTotal) return;
        cartItemsContainer.innerHTML = '';
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Votre panier est vide</p>';
            cartCount.textContent = '0';
            cartTotal.textContent = '0 MAD';
            return;
        }

        let total = 0;
        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            const cartItem = document.createElement('div');
            cartItem.className = 'flex items-center py-2 border-b';
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                <div class="ml-4 flex-1">
                    <h4 class="font-medium">${item.name}</h4>
                    <p class="text-sm text-gray-600">Couleur: ${item.color}</p>
                    <p class="text-sm text-gray-600">Taille: ${item.size}</p>
                    <div class="flex justify-between mt-1">
                        <span class="text-gray-800">${item.price} MAD x ${item.quantity}</span>
                        <span class="font-semibold">${itemTotal.toFixed(2)} MAD</span>
                    </div>
                </div>
                <button class="remove-item ml-2 text-red-500 hover:text-red-700" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            cartItemsContainer.appendChild(cartItem);
        });

        cartCount.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartTotal.textContent = total.toFixed(2) + ' MAD';

        // Remove item
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = parseInt(btn.getAttribute('data-index'));
                cart.splice(index, 1);
                updateCartUI();
            });
        });
    }

    // Function to sort products
    function sortProducts(criteria) {
        const productContainer = document.querySelector('#products .grid');
        const products = Array.from(productContainer.children);

        let sorted = [];

        if (criteria === 'price-asc') {
            sorted = [...products].sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.text-indigo-600')?.textContent);
                const priceB = parseFloat(b.querySelector('.text-indigo-600')?.textContent);
                return priceA - priceB;
            });
        } else if (criteria === 'price-desc') {
            sorted = [...products].sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.text-indigo-600')?.textContent);
                const priceB = parseFloat(b.querySelector('.text-indigo-600')?.textContent);
                return priceB - priceA;
            });
        } else if (criteria === 'newest') {
            sorted = [...products].sort((a, b) => {
                const dateA = a.getAttribute('data-date') || '1970-01-01';
                const dateB = b.getAttribute('data-date') || '1970-01-01';
                return new Date(dateB) - new Date(dateA);
            });
        } else if (criteria === 'popular') {
            sorted = [...products].sort((a, b) => {
                const ratingA = parseFloat(a.querySelector('.text-sm.ml-2')?.textContent.replace(/[^\d.]/g, '') || 0);
                const ratingB = parseFloat(b.querySelector('.text-sm.ml-2')?.textContent.replace(/[^\d.]/g, '') || 0);
                return ratingB - ratingA;
            });
        } else {
            sorted = products;
        }

        productContainer.innerHTML = '';
        sorted.forEach(p => productContainer.appendChild(p));
    }

    // Event listener for sort dropdown
    const sortSelect = document.getElementById('sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            sortProducts(e.target.value);
        });
    }

    // Update cart UI on load
    updateCartUI();
    // Gestion du formulaire de commande
const cartForm = document.getElementById('cart-form');
if (cartForm) {
    cartForm.addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('cart-data').value = JSON.stringify(cart);
        this.submit();
    });
}
</script>
    <!-- Charger le JS du produit -->
<script src="mocassins-s2-primo.js" defer></script>
</body>
</html>