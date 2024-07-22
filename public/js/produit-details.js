document.addEventListener('DOMContentLoaded', function() {
    const productDetailsContainer = document.getElementById('product-details');
    const quantityInput = document.getElementById('quantity-input');
    const lengthInput = document.getElementById('length-input');
    const priceContainer = document.getElementById('price-container');

    let selectedVariant = null; // Variable pour stocker la variante sélectionnée

    // Récupère les paramètres d'URL
    const urlParams = new URLSearchParams(window.location.search);
    const nomProduit = urlParams.get('nomProduit');
    const variants = JSON.parse(urlParams.get('variants'));

    // Affiche les détails du produit
    displayProductDetails(nomProduit, variants);

    function displayProductDetails(nomProduit, variants) {
        // Vérifie si les données ont été correctement récupérées
        if (!nomProduit || !variants || variants.length === 0) {
            console.error('Erreur: Données du produit non disponibles.');
            return;
        }

        // Crée l'image à droite
        const productImageContainer = document.createElement('div');
        productImageContainer.classList.add('product-image-container');
        const productImage = document.createElement('img');
        productImage.src = `/pictures/${variants[0].image}`; // On suppose que toutes les variantes ont la même image
        productImage.alt = nomProduit;
        productImage.classList.add('product-image');
        productImageContainer.appendChild(productImage);
        productDetailsContainer.appendChild(productImageContainer);

        // Crée le tableau à gauche
        const detailsTableContainer = document.createElement('div');
        detailsTableContainer.classList.add('details-table-container');
        const detailsTable = document.createElement('table');
        detailsTable.id = 'details-table';
        const tableHeader = document.createElement('thead');
        const tableBody = document.createElement('tbody');

        const tableHeaderRow = document.createElement('tr');
        const headers = ['Épaisseur', 'Hauteur', 'Largeur', 'Masse Kg/m', 'Sélectionner'];
        headers.forEach(headerText => {
            const headerCell = document.createElement('th');
            headerCell.textContent = headerText;
            tableHeaderRow.appendChild(headerCell);
        });
        tableHeader.appendChild(tableHeaderRow);

        variants.forEach(variant => {
            const dataRow = document.createElement('tr');

            // Assurez-vous que les clés correspondent aux données JSON
            const values = [
                variant.epaisseurProduit,
                variant.hauteurProduit,
                variant.largeurProduit,
                variant.masseProduit // Correction pour afficher la masseProduit
            ];

            // Ajoutez chaque valeur à une cellule de données
            values.forEach(value => {
                const dataCell = document.createElement('td');
                dataCell.textContent = value || ''; // Affiche la valeur ou une chaîne vide si non définie
                dataRow.appendChild(dataCell);
            });

            // Ajoute une case à cocher à chaque ligne de détail
            const checkboxCell = document.createElement('td');
            const checkboxContainer = document.createElement('div');
            checkboxContainer.className = 'checkbox-container';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'variant-checkbox'; // Ajoutez un nom pour le groupe de checkboxes
            checkbox.value = JSON.stringify(variant); // Stocker les données du variant dans la valeur de la checkbox
            checkboxContainer.appendChild(checkbox);
            dataRow.appendChild(checkboxCell).appendChild(checkboxContainer);

            // Écouteur d'événement pour la sélection de la variante
            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    selectedVariant = JSON.parse(checkbox.value);
                    // Décoche les autres checkboxes
                    document.querySelectorAll('input[name="variant-checkbox"]').forEach(cb => {
                        if (cb !== checkbox) {
                            cb.checked = false;
                        }
                    });
                    // Mettre à jour l'image du produit sélectionné
                    productImage.src = `/pictures/${selectedVariant.image}`;
                    calculatePrice(); // Recalculer le prix lorsque la variante est sélectionnée
                } else {
                    selectedVariant = null;
                    priceContainer.textContent = ''; // Efface le prix si aucune variante n'est sélectionnée
                }
            });

            tableBody.appendChild(dataRow);
        });

        detailsTable.appendChild(tableHeader);
        detailsTable.appendChild(tableBody);
        detailsTableContainer.appendChild(detailsTable);
        productDetailsContainer.appendChild(detailsTableContainer);
    }

    // Fonction pour recalculer le prix localement
    function calculatePrice() {
        if (!selectedVariant) {
            priceContainer.textContent = 'Veuillez sélectionner une variante.';
            return;
        }

        const longueur = parseFloat(lengthInput.value);
        const quantite = parseInt(quantityInput.value);

        if (isNaN(longueur) || isNaN(quantite)) {
            priceContainer.textContent = 'Veuillez entrer une longueur et une quantité valides.';
            return;
        }

        // Calcul du prix similaire à celui de votre API Symfony
        const prixUnitaire = selectedVariant.prixML;
        const longueurMetres = longueur / 100;
        const prixTTC = (selectedVariant.masseProduit * 0.3) * (longueurMetres * prixUnitaire) * quantite;

        // Affichage du prix calculé
        priceContainer.textContent = `Prix TTC: ${prixTTC.toFixed(2)} €`;

        // Stocker les détails du produit sélectionné dans le localStorage
        const selectedProduct = {
            nomProduit: nomProduit,
            variante: selectedVariant,
            longueur: longueur,
            quantite: quantite,
            image: selectedVariant.image // Ajoute l'image sélectionnée
        };

        // Enregistrement dans le localStorage
        localStorage.setItem('selectedProduct', JSON.stringify(selectedProduct));
    }

    // Fonction pour encoder les données
    function encodeForHTML(str) {
        return str.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
            return '&#'+i.charCodeAt(0)+';';
        });
    }

    // Écouteurs d'événements pour recalculer le prix
    quantityInput.addEventListener('input', calculatePrice);
    lengthInput.addEventListener('input', calculatePrice);

    // Écouteur d'événement pour ajouter au panier
    const addToCartButton = document.getElementById('add-to-cart-btn');
    addToCartButton.addEventListener('click', () => {
        calculatePrice(); // Appel de la fonction pour vérifier les données avant d'ajouter au panier

        if (!selectedVariant) {
            alert('Veuillez sélectionner une variante.');
            return;
        }

        const longueur = parseFloat(lengthInput.value);
        const quantite = parseInt(quantityInput.value);

        if (isNaN(longueur) || isNaN(quantite)) {
            alert('Veuillez entrer une longueur et une quantité valides.');
            return;
        }

        // Créer un objet avec les détails du produit
        const productDetails = {
            nomProduit: encodeForHTML(nomProduit),
            variante: selectedVariant,
            longueur: longueur,
            quantite: quantite,
            image: selectedVariant.image // Ajoute l'image sélectionnée
        };

        // Récupérer le panier actuel depuis le localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Ajouter le produit au panier
        cart.push(productDetails);

        // Stocker le panier mis à jour dans le localStorage
        localStorage.setItem('cart', JSON.stringify(cart));

        // Réinitialisation des inputs après ajout au panier (optionnel)
        quantityInput.value = '';
        lengthInput.value = '';
        alert('Produit ajouté au panier avec succès !');
    });
});
