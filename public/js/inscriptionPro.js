// Fonction de validation du formulaire
function validateForm() {
    let isValid = true;

    // Récupérer les données du formulaire
    const nom = document.getElementById('nom');
    const prenom = document.getElementById('prenom');
    const adresse = document.getElementById('adresse');
    const codePostal = document.getElementById('code-postal');
    const ville = document.getElementById('ville');
    const motDePasse = document.getElementById('mot-de-passe');
    const adresseEmail = document.getElementById('adresse-email');
    const numeroTelephone = document.getElementById('numero-telephone');
    const roles = document.getElementById('roles');
    const siret = document.getElementById('siret');
    const RaisonSociale = document.getElementById('raison-sociale');

    const fields = [nom, prenom, adresse, codePostal, ville, motDePasse, adresseEmail, numeroTelephone, roles, siret, RaisonSociale];

    // Vérifier si tous les champs sont remplis
    fields.forEach(field => {
        if (field.value.trim() === '') {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });

    // Afficher ou masquer le message d'erreur
    const errorMessage = document.getElementById('error-message');
    if (!isValid) {
        errorMessage.style.display = 'block';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    } else {
        errorMessage.style.display = 'none';
    }

    return isValid;
}

// Fonction pour valider le mot de passe
function validatePassword(password) {
    const isLengthValid = password.length >= 12;
    const isUpperCaseValid = /[A-Z]/.test(password);
    const isLowerCaseValid = /[a-z]/.test(password);
    const isNumberValid = /\d/.test(password);
    const isSymbolValid = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

    iconLength.className = isLengthValid ? 'icon-valid' : 'icon-invalid';
    iconUpperCase.className = isUpperCaseValid ? 'icon-valid' : 'icon-invalid';
    iconLowerCase.className = isLowerCaseValid ? 'icon-valid' : 'icon-invalid';
    iconNumber.className = isNumberValid ? 'icon-valid' : 'icon-invalid';
    iconSymbol.className = isSymbolValid ? 'icon-valid' : 'icon-invalid';

    return isLengthValid && isUpperCaseValid && isLowerCaseValid && isNumberValid && isSymbolValid;
}

// Ajout d'écouteur d'événements pour la soumission du formulaire
document.getElementById('inscriptionForm').addEventListener('submit', function(event) {
    event.preventDefault();

    if (!validateForm()) {
        return;
    }

    document.getElementById('error-message').style.display = 'none';
    document.getElementById('responseContainer').innerHTML = '';

    const formData = new FormData(this);
    const formObject = Object.fromEntries(formData.entries());

    fetch(this.getAttribute('action'), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formObject),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Erreurs: " + data.error.join("\n"));
            data.error.forEach(error => {
                const errorDiv = document.createElement('div');
                errorDiv.textContent = error;
                document.getElementById('responseContainer').appendChild(errorDiv);
            });
        } else {
            alert("Inscription réussie. Vous allez être redirigé vers la page de connexion.");
            document.getElementById('inscriptionForm').reset();
            window.location.href = '/connexion';
        }
    })
    .catch(error => console.error('Erreur lors de la requête :', error));
});

const passwordInput = document.getElementById('mot-de-passe');
const passwordMessage = document.getElementById('passwordMessage');
const iconLength = document.getElementById('iconLength');
const iconUpperCase = document.getElementById('iconUpperCase');
const iconLowerCase = document.getElementById('iconLowerCase');
const iconNumber = document.getElementById('iconNumber');
const iconSymbol = document.getElementById('iconSymbol');

passwordInput.addEventListener('input', function() {
    const password = passwordInput.value;
    const isPasswordValid = validatePassword(password);

    if (!isPasswordValid) {
        passwordMessage.style.display = 'block';
    } else {
        passwordMessage.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('adresse-email');
    emailInput.addEventListener('mouseover', function() {
        emailInput.setAttribute('title', "L'adresse e-mail doit être unique et respecter le format standard.");
        emailInput.setAttribute('placeholder', "Entrez une adresse e-mail unique");
    });

    const menuIcon = document.getElementById('menuIcon');
    const navMenu = document.getElementById('navMenu');
    const container = document.querySelector('.container');

    menuIcon.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        container.classList.toggle('menu-open');
    });
});
