document.addEventListener('DOMContentLoaded', function() {
    const formAjoutFournisseur = document.getElementById('addSupplierForm');
    const btnAjoutFournisseur = document.getElementById('btnAjoutFournisseur');
    const btnAnnuler = document.getElementById('btnAnnuler');
    const tableFournisseurs = document.getElementById('supplierTableBody');
    const tableauFournisseurs = document.getElementById('tableauFournisseurs');
    const ajoutFournisseur = document.getElementById('ajoutFournisseur');
    const filterType = document.getElementById('filterType');
    const filterPriceMin = document.getElementById('filterPriceMin');
    const filterPriceMax = document.getElementById('filterPriceMax');
    const applyFilters = document.getElementById('applyFilters');
    const resetFilters = document.getElementById('resetFilters');
    const searchName = document.getElementById('searchName'); // Nouvelle barre de recherche

    if (!tableFournisseurs) {
        console.error('Erreur : élément avec ID supplierTableBody non trouvé');
        return;
    }

    // Fonction pour récupérer et afficher les fournisseurs depuis l'API
    function fetchAndDisplaySuppliers() {
        fetch('/api/fournisseurs')
            .then(response => response.json())
            .then(data => {
                // Effacer le contenu actuel du tableau
                tableFournisseurs.innerHTML = '';

                // Appliquer les filtres aux données
                const filteredData = data.filter(fournisseur => {
                    const priceMin = parseFloat(filterPriceMin.value) || 0;
                    const priceMax = parseFloat(filterPriceMax.value) || Number.MAX_VALUE;
                    const type = filterType.value;
                    const price = parseFloat(fournisseur.prixHTFournisseur);
                    const searchQuery = searchName.value.toLowerCase(); // Obtenir la valeur de la barre de recherche

                    return (type === "" || fournisseur.typeFourniture === type) &&
                           (price >= priceMin) &&
                           (price <= priceMax) &&
                           fournisseur.nomFournisseur.toLowerCase().includes(searchQuery); // Filtrer par nom de fournisseur
                });

                // Boucler à travers les données filtrées des fournisseurs et les afficher dans le tableau
                filteredData.forEach(fournisseur => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${fournisseur.nomFournisseur}</td>
                        <td>${fournisseur.typeFourniture}</td>
                        <td>${fournisseur.prixHTFournisseur}</td>
                        <td>${fournisseur.email}</td>
                        <td>${fournisseur.telephone}</td>
                        <td>
                            <button class="btnModifier" data-id="${fournisseur.id}">Modifier</button>
                            <button class="btnSupprimer" data-id="${fournisseur.id}">Supprimer</button>
                        </td>
                    `;
                    tableFournisseurs.appendChild(tr);
                });

                // Mettre à jour les options du filtre de type de fourniture
                updateTypeFilterOptions(data);
            })
            .catch(error => console.error('Erreur lors de la récupération des fournisseurs :', error));
    }

    // Fonction pour mettre à jour les options du filtre de type de fourniture
    function updateTypeFilterOptions(data) {
        const uniqueTypes = getUniqueTypes(data);
        const currentType = filterType.value; // Conserver la valeur actuelle du filtre

        filterType.innerHTML = '<option value="">Tous</option>'; // Réinitialiser les options

        uniqueTypes.forEach(type => {
            const option = document.createElement('option');
            option.textContent = type;
            option.value = type;
            filterType.appendChild(option);
        });

        filterType.value = currentType; // Rétablir la sélection précédente
    }

    // Fonction pour récupérer les types de fourniture uniques à partir des données des fournisseurs
    function getUniqueTypes(data) {
        const types = data.map(fournisseur => fournisseur.typeFourniture);
        return Array.from(new Set(types)); // Retourner un tableau avec des valeurs uniques
    }

    // Écouteur d'événement pour soumettre le formulaire d'ajout/modification de fournisseur
    formAjoutFournisseur.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = {
            nomFournisseur: document.getElementById('supplierName').value,
            typeFourniture: document.getElementById('supplierType').value,
            prixHTFournisseur: parseFloat(document.getElementById('supplierPrice').value),
            email: document.getElementById('supplierEmail').value,
            telephone: document.getElementById('supplierPhone').value
        };

        let apiUrl = '/api/fournisseurs/nouveau';
        let method = 'POST';

        const supplierId = document.getElementById('supplierId').value;
        if (supplierId) {
            apiUrl = `/api/fournisseurs/${supplierId}/modifier`;
            method = 'PUT';
        }

        fetch(apiUrl, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (method === 'POST') {
                    alert('Fournisseur ajouté avec succès');
                } else {
                    alert('Fournisseur modifié avec succès');
                }
                formAjoutFournisseur.reset();
                document.getElementById('supplierId').value = '';
                fetchAndDisplaySuppliers();
                tableauFournisseurs.scrollIntoView({ behavior: 'smooth', block: 'start' }); // Faire défiler vers le tableau
            })
            .catch(error => console.error('Erreur lors de l\'ajout/modification du fournisseur :', error));
    });

    // Fonction pour afficher ou masquer le formulaire et le tableau
    function toggleFormAndTable(show) {
        if (show) {
            ajoutFournisseur.style.display = 'block';
            tableauFournisseurs.style.display = 'block';
        } else {
            ajoutFournisseur.style.display = 'none';
            tableauFournisseurs.style.display = 'none';
        }
    }

    // Écouteur d'événement pour le clic sur le bouton Ajout Fournisseur
    btnAjoutFournisseur.addEventListener('click', function() {
        const isCurrentlyVisible = ajoutFournisseur.style.display === 'block';
        toggleFormAndTable(!isCurrentlyVisible);
    });

    // Écouteur d'événement pour le clic sur le bouton Annuler
    btnAnnuler.addEventListener('click', function() {
        toggleFormAndTable(false); // Masquer le formulaire et le tableau
    });

    // Écouteurs d'événement pour les boutons Modifier et Supprimer dans le tableau
    tableFournisseurs.addEventListener('click', function(event) {
        if (event.target.classList.contains('btnModifier')) {
            const fournisseurId = event.target.getAttribute('data-id');
            fetch(`/api/fournisseurs/${fournisseurId}`)
                .then(response => response.json())
                .then(fournisseur => {
                    document.getElementById('supplierName').value = fournisseur.nomFournisseur;
                    document.getElementById('supplierType').value = fournisseur.typeFourniture;
                    document.getElementById('supplierPrice').value = fournisseur.prixHTFournisseur;
                    document.getElementById('supplierEmail').value = fournisseur.email;
                    document.getElementById('supplierPhone').value = fournisseur.telephone;
                    document.getElementById('supplierId').value = fournisseur.id;

                    toggleFormAndTable(true); // Afficher le formulaire et le tableau
                })
                .catch(error => console.error('Erreur lors de la récupération du fournisseur :', error));
        } else if (event.target.classList.contains('btnSupprimer')) {
            const fournisseurId = event.target.getAttribute('data-id');
            if (confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')) {
                fetch(`/api/fournisseurs/${fournisseurId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.status === 204) {
                            alert('Fournisseur supprimé avec succès');
                            fetchAndDisplaySuppliers(); // Rafraîchir la liste des fournisseurs après la suppression
                        } else {
                            throw new Error('Erreur lors de la suppression du fournisseur');
                        }
                    })
                    .catch(error => console.error('Erreur lors de la suppression du fournisseur :', error));
            }
        }
    });

    // Écouteur d'événement pour appliquer les filtres
    applyFilters.addEventListener('click', function() {
        fetchAndDisplaySuppliers();
    });

    // Écouteur d'événement pour réinitialiser les filtres
    resetFilters.addEventListener('click', function() {
        filterType.value = '';
        filterPriceMin.value = '';
        filterPriceMax.value = '';
        searchName.value = ''; // Réinitialiser la barre de recherche
        fetchAndDisplaySuppliers();
    });

    // Écouteur d'événement pour la barre de recherche
    searchName.addEventListener('input', function() {
        fetchAndDisplaySuppliers();
    });

    // Charger et afficher les fournisseurs au chargement de la page
    fetchAndDisplaySuppliers();
});



//code entrepot 
document.addEventListener('DOMContentLoaded', function() {
    const cardEntrepots = document.getElementById('tableauEntrepots'); // La card qui contient tout
    const btnGestionEntrepots = document.getElementById('btnAjoutEntrepot');
    const ajoutEntrepot = document.getElementById('ajoutEntrepot');
    const addEntrepotForm = document.getElementById('addEntrepotForm');
    const tableEntrepots = document.getElementById('entrepotTableBody');

    let isHidden = true; // Variable pour suivre l'état actuel d'affichage

    // Écouteur d'événement pour le clic sur le bouton Gestion Des Entrepots
    btnGestionEntrepots.addEventListener('click', function() {
        isHidden = !isHidden; // Inverser l'état actuel d'affichage

        if (isHidden) {
            cardEntrepots.style.display = 'none'; // Masquer la card
            ajoutEntrepot.style.display = 'none'; // Masquer le formulaire
        } else {
            cardEntrepots.style.display = 'block'; // Afficher la card
            ajoutEntrepot.style.display = 'block'; // Afficher le formulaire

            // Scroller jusqu'au formulaire d'ajout/modification
            ajoutEntrepot.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        addEntrepotForm.reset(); // Réinitialiser le formulaire
        document.getElementById('entrepotId').value = ''; // Réinitialiser l'ID caché
    });

    // Fonction pour afficher les entrepôts depuis l'API
    function fetchAndDisplayEntrepots() {
        fetch('/api/entrepots')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur HTTP ' + response.status);
                }
                return response.json(); // Convertir la réponse en JSON
            })
            .then(data => {
                tableEntrepots.innerHTML = '';

                data.forEach(entrepot => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${entrepot.nom}</td>
                        <td>${entrepot.ville}</td>
                        <td>${entrepot.codePostale}</td>
                        <td>${entrepot.rue}</td>
                        <td>
                            <button class="btnModifierEntrepot" data-id="${entrepot.id}">Modifier</button>
                            <button class="btnSupprimerEntrepot" data-id="${entrepot.id}">Supprimer</button>
                        </td>
                    `;
                    tableEntrepots.appendChild(tr);
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des entrepôts :', error));
    }

    // Écouteur d'événement pour soumettre le formulaire d'ajout/modification
    addEntrepotForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = {
            nom: document.getElementById('entrepotName').value,
            ville: document.getElementById('entrepotVille').value,
            codePostale: document.getElementById('entrepotCodePostal').value,
            rue: document.getElementById('entrepotRue').value
        };

        let apiUrl = '/api/entrepots/create';
        let method = 'POST';

        const entrepotId = document.getElementById('entrepotId').value;
        if (entrepotId) {
            apiUrl = `/api/entrepots/${entrepotId}`;
            method = 'PUT';
        }

        fetch(apiUrl, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Erreur lors de la sauvegarde de l\'entrepôt');
                }
            })
            .then(data => {
                if (method === 'POST') {
                    alert('Entrepôt ajouté avec succès');
                } else {
                    alert('Entrepôt modifié avec succès');
                }
                // Ne pas masquer le formulaire après l'action
                // Réinitialiser le formulaire, mais ne pas masquer ici

                fetchAndDisplayEntrepots(); // Rafraîchir la liste des entrepôts après l'ajout/modification

                // Scroller jusqu'au tableau des entrepôts
                cardEntrepots.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(error => console.error('Erreur lors de l\'ajout/modification de l\'entrepôt :', error));
    });

    // Écouteur d'événement pour les boutons Modifier et Supprimer dans le tableau
    tableEntrepots.addEventListener('click', function(event) {
        if (event.target.classList.contains('btnModifierEntrepot')) {
            const entrepotId = event.target.getAttribute('data-id');
            fetch(`/api/entrepots/${entrepotId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur HTTP ' + response.status);
                    }
                    return response.json(); // Convertir la réponse en JSON
                })
                .then(entrepot => {
                    // Pré-remplir le formulaire avec les données de l'entrepôt sélectionné
                    document.getElementById('entrepotName').value = entrepot.nom;
                    document.getElementById('entrepotVille').value = entrepot.ville;
                    document.getElementById('entrepotCodePostal').value = entrepot.codePostale;
                    document.getElementById('entrepotRue').value = entrepot.rue;
                    document.getElementById('entrepotId').value = entrepot.id;

                    // Afficher la card et le formulaire
                    cardEntrepots.style.display = 'block';
                    ajoutEntrepot.style.display = 'block';

                    // Scroller jusqu'au formulaire d'ajout/modification
                    ajoutEntrepot.scrollIntoView({ behavior: 'smooth', block: 'start' });
                })
                .catch(error => console.error('Erreur lors de la récupération de l\'entrepôt :', error));
        } else if (event.target.classList.contains('btnSupprimerEntrepot')) {
            const entrepotId = event.target.getAttribute('data-id');
            if (confirm('Êtes-vous sûr de vouloir supprimer cet entrepôt ?')) {
                fetch(`/api/entrepots/${entrepotId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (response.ok) {
                            alert('Entrepôt supprimé avec succès');
                            fetchAndDisplayEntrepots(); // Rafraîchir la liste des entrepôts après la suppression
                        } else {
                            throw new Error('Erreur lors de la suppression de l\'entrepôt');
                        }
                    })
                    .catch(error => console.error('Erreur lors de la suppression de l\'entrepôt :', error));
            }
        }
    });

    // Charger et afficher les entrepôts au chargement de la page
    fetchAndDisplayEntrepots();
});
//code produit 
// app.js

document.getElementById('addProductForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le comportement par défaut du formulaire

    // Créer un objet FormData
    const formData = new FormData();

    // Récupérer les valeurs du formulaire et les ajouter à l'objet FormData
    formData.append('nomProduit', document.getElementById('nomProduit').value);
    formData.append('image', document.getElementById('image').files[0]);
    formData.append('largeurProduit', parseFloat(document.getElementById('largeurProduit').value));
    formData.append('epaisseurProduit', parseFloat(document.getElementById('epaisseurProduit').value));
    formData.append('masseProduit', parseFloat(document.getElementById('masseProduit').value));
    formData.append('formeProduit', document.getElementById('formeProduit').value);
    formData.append('hauteurProduit', parseFloat(document.getElementById('hauteurProduit').value));
    formData.append('sectionProduit', parseFloat(document.getElementById('sectionProduit').value));
    formData.append('marge', parseFloat(document.getElementById('marge').value));
    formData.append('prixML', parseFloat(document.getElementById('prixML').value));

    console.log([...formData.entries()]); // Afficher les entrées de formData pour déboguer

    // Envoyer la requête POST à l'API Symfony avec fetch
    fetch('http://localhost:8080/api/produits', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de l\'ajout du produit');
        }
        return response.json();
    })
    .then(data => {
        // Succès : Afficher la réponse
        document.getElementById('responseMessage').innerHTML = `<p>Produit ajouté avec succès !</p>`;
    })
    .catch(error => {
        // Erreur : Afficher l'erreur
        console.error('Erreur lors de l\'ajout du produit :', error);
        document.getElementById('responseMessage').innerHTML = `<p>Erreur : ${error.message}</p>`;
    });
});
document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost:8080/api/produits')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des produits');
            }
            return response.json();
        })
        .then(data => {
            const productsByName = groupProductsByName(data);
            const productsTable = document.getElementById('productsTable');

            for (const [productName, products] of Object.entries(productsByName)) {
                const row = createProductRow(productName, products);
                productsTable.appendChild(row);
            }
        })
        .catch(error => console.error('Erreur lors de la récupération des produits :', error));
});

function groupProductsByName(products) {
    return products.reduce((acc, product) => {
        if (!acc[product.nomProduit]) {
            acc[product.nomProduit] = [];
        }
        acc[product.nomProduit].push(product);
        return acc;
    }, {});
}

function createProductRow(productName, products) {
    const row = document.createElement('tr');

    const nomProduitCell = document.createElement('td');
    const card = document.createElement('div');
    card.classList.add('product-card');

    const image = document.createElement('img');
    image.src = `http://localhost:8080/pictures/${products[0].image}`;
    image.alt = productName;
    image.style.cursor = 'pointer';
    image.style.maxWidth = '100px';
    image.addEventListener('click', () => {
        toggleProductDetails(products, row);
    });

    const productNameText = document.createElement('p');
    productNameText.textContent = productName;
    productNameText.style.fontWeight = 'bold';
    card.appendChild(image);
    card.appendChild(productNameText);

    nomProduitCell.appendChild(card);
    row.appendChild(nomProduitCell);

    return row;
}

function toggleProductDetails(products, row) {
    let detailsRow = row.nextElementSibling;

    // Check if the details row already exists
    if (detailsRow && detailsRow.classList.contains('details-row')) {
        detailsRow.remove();
    } else {
        detailsRow = document.createElement('tr');
        detailsRow.classList.add('details-row');
        const detailsCell = document.createElement('td');
        detailsCell.colSpan = 1;

        const detailsTable = document.createElement('table');
        detailsTable.classList.add('details-table');
        const headerRow = document.createElement('tr');
        
        const headers = [
            'Largeur', 'Épaisseur', 'Masse', 'Forme', 'Hauteur', 'Section', 'Marge', 'Prix ML'
        ];
        
        headers.forEach(headerText => {
            const th = document.createElement('th');
            th.textContent = headerText;
            headerRow.appendChild(th);
        });
        detailsTable.appendChild(headerRow);

        products.forEach(product => {
            const detailRow = document.createElement('tr');

            const largeurCell = document.createElement('td');
            largeurCell.textContent = product.largeurProduit;
            detailRow.appendChild(largeurCell);

            const epaisseurCell = document.createElement('td');
            epaisseurCell.textContent = product.epaisseurProduit;
            detailRow.appendChild(epaisseurCell);

            const masseCell = document.createElement('td');
            masseCell.textContent = product.masseProduit;
            detailRow.appendChild(masseCell);

            const formeCell = document.createElement('td');
            formeCell.textContent = product.formeProduit;
            detailRow.appendChild(formeCell);

            const hauteurCell = document.createElement('td');
            hauteurCell.textContent = product.hauteurProduit || 'N/A';
            detailRow.appendChild(hauteurCell);

            const sectionCell = document.createElement('td');
            sectionCell.textContent = product.sectionProduit || 'N/A';
            detailRow.appendChild(sectionCell);

            const margeCell = document.createElement('td');
            margeCell.textContent = product.marge;
            detailRow.appendChild(margeCell);

            const prixMLCell = document.createElement('td');
            prixMLCell.textContent = product.prixML;
            detailRow.appendChild(prixMLCell);

            detailsTable.appendChild(detailRow);
        });

        detailsCell.appendChild(detailsTable);
        detailsRow.appendChild(detailsCell);
        row.parentNode.insertBefore(detailsRow, row.nextSibling);
    }
}

// Récupérer les fournisseurs depuis l'API Symfony
document.addEventListener('DOMContentLoaded', function() {
    const apiUrl = 'http://localhost:8080/api/produit-fournisseur';

    async function fetchSuppliers() {
        try {
            const response = await fetch('/api/fournisseurs');
            const fournisseurs = await response.json();

            const fournisseurSelect = document.getElementById('fournisseurId');
            fournisseurs.forEach(fournisseur => {
                const option = document.createElement('option');
                option.value = fournisseur.id;
                option.textContent = fournisseur.nomFournisseur;
                fournisseurSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Erreur lors de la récupération des fournisseurs:', error);
            alert('Erreur lors de la récupération des fournisseurs. Veuillez réessayer.');
        }
    }

    async function fetchProducts() {
        try {
            const response = await fetch('/api/produits');
            const produits = await response.json();
            const productsList = document.getElementById('productsList');

            produits.forEach(produit => {
                const productDiv = document.createElement('div');

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'product';
                checkbox.value = produit.id;

                const label = document.createElement('label');
                label.innerHTML = `<i class="fas fa-info-circle" onclick="showProductDetails(${produit.id})"></i> ${produit.nomProduit} (ID: ${produit.id})`;

                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.name = `quantiteCommande[${produit.id}]`;
                quantityInput.placeholder = 'Quantité';
                quantityInput.required = true;

                productDiv.appendChild(checkbox);
                productDiv.appendChild(label);
                productDiv.appendChild(quantityInput);

                productsList.appendChild(productDiv);
            });
        } catch (error) {
            console.error('Erreur lors de la récupération des produits:', error);
            alert('Erreur lors de la récupération des produits. Veuillez réessayer.');
        }
    }

    document.getElementById('productForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const fournisseurId = document.getElementById('fournisseurId').value;
        const produits = [];

        document.querySelectorAll('input[name="product"]:checked').forEach(checkbox => {
            const productId = checkbox.value;
            const quantiteCommande = document.querySelector(`input[name="quantiteCommande[${productId}]"]`).value;
            produits.push({ id: productId, quantiteCommande: quantiteCommande });
        });

        try {
            const response = await fetch('/api/produit-fournisseur', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    fournisseurId: fournisseurId,
                    produits: produits,
                    dateCommande: new Date().toISOString()
                })
            });

            const data = await response.json();
            alert(data.message);
            loadProduits(); // Recharger les produits après l'ajout
        } catch (error) {
            console.error('Erreur lors de l\'ajout de la commande fournisseur:', error);
            alert('Erreur lors de l\'ajout de la commande fournisseur. Veuillez réessayer.');
        }
    });

    async function loadProduits() {
        try {
            const response = await fetch(apiUrl);
            const data = await response.json();
            const produitsBody = document.getElementById('produits-body');
            produitsBody.innerHTML = '';

            data.forEach(produit => {
                const row = document.createElement('tr');
                row.id = `row_${produit.id}`;
                row.innerHTML = `
                    <td>${produit.id}</td>
                    <td>${produit.produit.nom}</td>
                    <td>${produit.fournisseur.nom}</td>
                    <td>${produit.quantiteCommande}</td>
                    <td>${new Date(produit.dateCommande).toLocaleDateString()}</td>
                    <td>${produit.etatCommande}</td>
                    <td>${produit.etatLivraison}</td>
                    <td>${produit.dateLivraison ? new Date(produit.dateLivraison).toLocaleDateString() : ''}</td>
                    <td>
                        <button onclick="updateProduit(${produit.id}, 'arrivé')">Arrivé</button>
                        <button onclick="updateProduit(${produit.id}, 'retardé')">Retardé</button>
                        <button onclick="showProductDetailsInTable(${produit.id})">Détails</button>
                    </td>
                `;
                produitsBody.appendChild(row);
            });
        } catch (error) {
            console.error('Erreur lors du chargement des produits :', error);
            alert('Une erreur est survenue lors du chargement des produits.');
        }
    }

    window.updateProduit = async function(id, etatLivraison) {
        const confirmation = confirm(`Êtes-vous sûr de vouloir marquer ce produit comme "${etatLivraison}" ?`);
        if (!confirmation) return;

        const updateData = {
            etatLivraison: etatLivraison,
            dateLivraison: new Date().toISOString()
        };

        try {
            const response = await fetch(`${apiUrl}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updateData)
            });

            const data = await response.json();
            alert(data.message);

            if (etatLivraison === 'arrivé') {
                // Appel à la nouvelle route pour mettre à jour le stock
                const updateStockResponse = await fetch(`/api/produit-fournisseur/${id}/livraison-arrivee/stock`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        produitId: id,
                        quantite: data.quantite  // Vous pouvez récupérer cette valeur de la réponse si nécessaire
                    })
                });

                const stockData = await updateStockResponse.json();
                alert(stockData.message);  // Afficher un message de succès pour la mise à jour du stock
            }

            loadProduits(); // Recharger les produits après l'ajout
        } catch (error) {
            console.error('Erreur lors de la mise à jour du produit :', error);
            alert('Une erreur est survenue lors de la mise à jour du produit.');
        }
    }

    window.showProductDetails = async function(productId) {
        try {
            const response = await fetch(`/api/produits/${productId}`);
            const produit = await response.json();
            alert(`
                Nom: ${produit.nomProduit}
                Largeur: ${produit.largeur}
                Masse: ${produit.masse}
                Épaisseur: ${produit.epaisseur}
                Forme: ${produit.forme || 'Non spécifiée'}
                Hauteur: ${produit.hauteur}
                Section: ${produit.section}
            `);
        } catch (error) {
            console.error('Erreur lors du chargement des détails du produit :', error);
            alert('Une erreur est survenue lors du chargement des détails du produit.');
        }
    }

    window.showProductDetailsInTable = async function(productId) {
        try {
            const response = await fetch(`/api/produit-fournisseur/${productId}`);
            const data = await response.json();
            const detailsRow = document.createElement('tr');
            detailsRow.id = `detailsRow_${productId}`;
            detailsRow.innerHTML = `
                <td colspan="9">
                    <strong>ID:</strong> ${data.produit.id}<br>
                    <strong>Nom:</strong> ${data.produit.nom}<br>
                    <strong>Largeur:</strong> ${data.produit.largeur}<br>
                    <strong>Masse:</strong> ${data.produit.masse}<br>
                    <strong>Épaisseur:</strong> ${data.produit.epaisseur}<br>
                    <strong>Forme:</strong> ${data.produit.forme || 'Non spécifiée'}<br>
                    <strong>Hauteur:</strong> ${data.produit.hauteur}<br>
                    <strong>Section:</strong> ${data.produit.section}<br>
                    <button onclick="hideProductDetails(${productId})">Masquer</button>
                </td>
            `;

            const existingRow = document.getElementById(`detailsRow_${productId}`);
            if (existingRow) {
                existingRow.parentNode.removeChild(existingRow);
            } else {
                document.getElementById(`row_${productId}`).insertAdjacentElement('afterend', detailsRow);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des détails du produit :', error);
            alert('Une erreur est survenue lors du chargement des détails du produit.');
        }
    }

    window.hideProductDetails = function(productId) {
        const detailsRow = document.getElementById(`detailsRow_${productId}`);
        if (detailsRow) {
            detailsRow.parentNode.removeChild(detailsRow);
        }
    }

    fetchSuppliers();
    fetchProducts();
    loadProduits();
});
