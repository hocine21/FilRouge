// Fonction pour valider le formulaire
function validateForm() {
    var nom = document.getElementById('nom').value.trim();
    var prenom = document.getElementById('prenom').value.trim();
    var adresse = document.getElementById('adresse').value.trim();
    var codePostal = document.getElementById('code-postal').value.trim();
    var ville = document.getElementById('ville').value.trim();
    var motDePasse = document.getElementById('mot-de-passe').value;
    var adresseEmail = document.getElementById('adresse-email').value.trim();
    var numeroTelephone = document.getElementById('numero-telephone').value.trim();

    // Validation simple côté client
    if (!nom || !prenom || !adresse || !codePostal || !ville || !motDePasse || !adresseEmail || !numeroTelephone) {
        document.getElementById('error-message').style.display = 'block';
        return false;
    }

    // Création de l'objet contenant les données du formulaire
    var formData = {
        Nom: nom,
        Prenom: prenom,
        NomRue: adresse,
        CodePostale: codePostal,
        Ville: ville,
        MotDePasse: motDePasse,
        AdresseEmail: adresseEmail,
        NumeroTelephone: numeroTelephone,
        Roles: 'ROLE_PARTICULIER' // Définition du rôle ici
    };

    // Envoi des données vers l'API Symfony
    fetch('/api/inscription', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de la requête.');
        }
        return response.json();
    })
    .then(data => {
        // Redirection vers la page de connexion en cas de succès
        window.location.href = '/connexion'; // Remplacez par l'URL de votre page de connexion
    })
    .catch(error => {
        console.error('Erreur:', error);
        // Affichage de l'erreur (vous pouvez personnaliser selon vos besoins)
        alert('Une erreur est survenue lors de l\'inscription.');
    });

    // Empêcher l'envoi du formulaire par défaut
    return false;
}
