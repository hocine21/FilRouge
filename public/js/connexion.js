// public/js/connexion.js

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Empêche le comportement par défaut du formulaire
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('/api/connexion', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        AdresseEmail: email,
                        MotDePasse: password
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    // Enregistrer le token dans le localStorage
                    localStorage.setItem('authToken', data.token);

                    // Rediriger l'utilisateur en fonction du rôle
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    // Afficher les erreurs
                    alert(data.error || 'Une erreur est survenue.');
                }
            } catch (error) {
                console.error('Erreur lors de la connexion:', error);
                alert('Une erreur est survenue lors de la connexion.');
            }
        });
    }
});
