// Fonction pour obtenir la couleur hexadécimale selon le nom
function getColorHex(colorName) {
  const colors = {
    'Blanc': '#ffffff',
    'Noir': '#000000',
    'Bleu': '#3b82f6',
    'Rouge': '#ef4444',
    'Vert': '#10b981',
    'Jaune': '#f59e0b',
    'Orange': '#f97316',
    'Gris': '#6b7280',
    'Marron': '#78350f'
  };
  return colors[colorName] || '#cccccc';
}

// Variables globales
let currentProduct = null;
let currentColor = null;
let selectedSize = null;

// Éléments DOM
const productModal = document.getElementById('product-modal');
const closeModalBtn = document.getElementById('close-modal');
const modalProductName = document.getElementById('modal-product-name');
const modalProductDescription = document.getElementById('modal-product-description');
const modalProductPrice = document.getElementById('modal-product-price');
const modalProductImage = document.getElementById('modal-product-image');
const colorOptionsContainer = document.getElementById('color-options');
const sizeOptionsContainer = document.getElementById('size-options');
const decreaseQty = document.getElementById('decrease-qty');
const increaseQty = document.getElementById('increase-qty');
const productQty = document.getElementById('product-qty');
const addToCartBtn = document.getElementById('add-to-cart-btn');

// Charger les pointures dynamiquement (35 à 43)
function generateSizeButtons() {
  sizeOptionsContainer.innerHTML = '';
  for (let i = 35; i <= 43; i++) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = i;
    btn.className = 'px-3 py-1 border rounded-md hover:bg-gray-100 text-sm m-1';
    btn.dataset.size = i;

    btn.addEventListener('click', () => {
      // Supprimer la sélection précédente
      document.querySelectorAll('#size-options button').forEach(b => b.classList.remove('bg-indigo-600', 'text-white'));
      // Ajouter la nouvelle sélection
      btn.classList.add('bg-indigo-600', 'text-white');
      selectedSize = i;
    });

    sizeOptionsContainer.appendChild(btn);
  }
}

// Ouvrir le modal pour ce produit spécifique
document.querySelectorAll('.view-product-btn-mocassins').forEach(btn => {
  btn.addEventListener('click', () => {
    const productId = btn.getAttribute('data-id');
    const productName = btn.getAttribute('data-name');
    const productPrice = btn.getAttribute('data-price');
    const productDescription = btn.getAttribute('data-description');
    const colors = JSON.parse(btn.getAttribute('data-colors'));

    currentProduct = {
      id: productId,
      name: productName,
      price: productPrice,
      description: productDescription
    };
    currentColor = colors[0];

    // Remplir les champs du modal
    modalProductName.textContent = productName;
    modalProductDescription.textContent = productDescription;
    modalProductPrice.textContent = `${productPrice} MAD`;
    modalProductImage.src = currentColor.image;

    // Charger les couleurs
    colorOptionsContainer.innerHTML = '';
    colors.forEach((color, index) => {
      const chip = document.createElement('button');
      chip.className = 'w-6 h-6 rounded-full border border-gray-300 cursor-pointer color-option';
      chip.style.backgroundColor = getColorHex(color.name);
      chip.title = color.name;
      chip.dataset.colorName = color.name;
      chip.dataset.colorImage = color.image;
      if (index === 0) chip.classList.add('selected'); // Couleur initiale
      colorOptionsContainer.appendChild(chip);
    });

    // Charger les pointures
    generateSizeButtons();

    // Réinitialiser la quantité
    productQty.textContent = '1';

    // Afficher le modal
    productModal.classList.remove('hidden');

    // Bloquer scroll arrière
    document.body.style.overflow = 'hidden';
  });
});

// Fermer le modal
closeModalBtn.addEventListener('click', () => {
  productModal.classList.add('hidden');
  document.body.style.overflow = 'auto';
});

// Changer l’image selon la couleur sélectionnée
colorOptionsContainer.addEventListener('click', e => {
  if (e.target.classList.contains('color-option')) {
    document.querySelectorAll('.color-option').forEach(c => c.classList.remove('selected'));
    e.target.classList.add('selected');
    const newColorName = e.target.dataset.colorName;
    const newColorImage = e.target.dataset.colorImage;
    currentColor = { name: newColorName, image: newColorImage };
    modalProductImage.src = newColorImage;
  }
});

// Gestion de la quantité
let qty = 1;
decreaseQty.addEventListener('click', () => {
  if (qty > 1) {
    qty--;
    productQty.textContent = qty;
  }
});
increaseQty.addEventListener('click', () => {
  qty++;
  productQty.textContent = qty;
});

// Ajouter au panier
addToCartBtn.addEventListener('click', () => {
  if (!currentProduct || !selectedSize) {
    alert("Veuillez choisir une pointure.");
    return;
  }

  const item = {
    id: currentProduct.id,
    name: currentProduct.name,
    price: currentProduct.price,
    color: currentColor.name,
    size: selectedSize,
    image: currentColor.image,
    quantity: parseInt(productQty.textContent)
  };

  console.log('Produit ajouté au panier:', item);
  alert(`${item.quantity} x ${item.name} (${item.color}, Pointure: ${item.size}) ajouté(e)(s) au panier.`);

  productModal.classList.add('hidden');
  document.body.style.overflow = 'auto';
});