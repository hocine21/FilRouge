document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le formulaire de se soumettre normalement

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    // Données à envoyer à l'API Symfony
    const formData = {
        AdresseEmail: email,
        MotDePasse: password
    };

    // URL de l'API Symfony
    const url = 'http://localhost:8080/api/connexion'; // Modifier l'URL selon votre environnement

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Adresse e-mail ou mot de passe incorrect.');
        }
        return response.json();
    })
    .then(data => {
        const token = data.token;
        localStorage.setItem('jwtToken', token); // Stockage du token JWT

        // Envoi du token JWT dans l'en-tête Authorization pour les futures requêtes
        const headers = {
            'Authorization': `Bearer ${token}`
        };

        // Redirection en fonction des rôles
        const decodedToken = parseJwt(token);
        const roles = decodedToken.roles;
        const userId = decodedToken.userId; // Assurez-vous que le payload JWT contient l'ID de l'utilisateur

        if (roles.includes('ROLE_SUPER_ADMIN')) {
            window.location.href = `http://localhost:8080/super-admin?role=${roles}&id=${userId}`;
        } else if (roles.includes('ROLE_APPROVISIONNEMENT')) {
            window.location.href = `http://localhost:8080/appro?role=${roles}&id=${userId}`;
        } else {
            console.log('Rôle non géré');
            // Redirection vers une page par défaut ou affichage d'un message d'erreur
        }
    })
    .catch(error => {
        console.error('Erreur:', error.message);
        // Affichage de l'erreur à l'utilisateur (par exemple dans une div)
    });
});

// Fonction pour décoder le token JWT
function parseJwt(token) {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(atob(base64).split('').map(c => {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
}
