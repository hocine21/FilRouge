document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('.login-form');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Empêcher le formulaire de se soumettre normalement

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        // Vérifier si l'élément CSRF existe avant de le récupérer
        const csrfTokenElement = document.getElementById('csrf_token');
        const csrfToken = csrfTokenElement ? csrfTokenElement.value : null;

        if (!csrfToken) {
            console.error('Le jeton CSRF n\'a pas été trouvé.');
            return;
        }

        fetch('http://localhost:8080/api/connexion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Inclure le jeton CSRF dans l'en-tête de la requête
            },
            body: JSON.stringify({ AdresseEmail: email, MotDePasse: password })
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
