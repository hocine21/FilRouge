document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('commande2');
    const apiEndpoint = '/api/produits';

    fetch(apiEndpoint)
        .then(response => response.json())
        .then(data => {
            const groupedProducts = groupProductsByNameAndImage(data);

            for (const [nomProduit, details] of Object.entries(groupedProducts)) {
                const productCard = createProductCard(nomProduit, details.image);

                productCard.querySelector('.details-link').addEventListener('click', (event) => {
                    event.preventDefault();
                    redirectToProductDetails(nomProduit, details.variants);
                });

                productsContainer.appendChild(productCard);
            }
        })
        .catch(error => console.error('Erreur lors de la récupération des produits:', error));

    function groupProductsByNameAndImage(products) {
        return products.reduce((acc, product) => {
            if (!acc[product.nomProduit]) {
                acc[product.nomProduit] = {
                    image: product.image,
                    variants: []
                };
            }
            acc[product.nomProduit].variants.push(product);
            return acc;
        }, {});
    }

    function createProductCard(nomProduit, image) {
        const productCard = document.createElement('div');
        productCard.className = 'product-card';
        productCard.innerHTML = `
            <img src="/pictures/${image}" alt="${nomProduit}">
            <h3>${nomProduit}</h3>
            <div class="actions">
                <a href="#" class="cart-link">
                    <button class="add-to-cart">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </a>
                <a href="#" class="details-link">
                    <button class="show-details">
                        <i class="fas fa-info"></i>
                    </button>
                </a>
            </div>
        `;
        return productCard;
    }

    function redirectToProductDetails(nomProduit, variants) {
        const queryParams = new URLSearchParams({
            nomProduit: nomProduit,
            variants: JSON.stringify(variants)
        }).toString();
        window.location.href = `/produit-bis?${queryParams}`;
    }
});
