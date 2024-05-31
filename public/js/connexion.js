document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher le formulaire de se soumettre normalement

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        fetch('http://localhost:8080/api/connexion', { // Ajoutez le port 8080
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ AdresseEmail: email, MotDePasse: password }),
})
.then(response => response.json())
.then(data => {
    // Vérifier si la connexion a réussi
    if (data.user) {
        // Stocker les informations de l'utilisateur dans le localStorage
        localStorage.setItem('user', JSON.stringify(data.user));

        // Rediriger l'utilisateur vers la page d'accueil
        window.location.href = '/accueil';
    } else {
        // Afficher un message d'erreur
        alert(data.error);
    }
})
.catch(error => console.error('Erreur lors de la connexion:', error));
    });
});