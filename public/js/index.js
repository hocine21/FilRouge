document.addEventListener('DOMContentLoaded', function() {
    const user = JSON.parse(localStorage.getItem('user'));

    if (user) {
        // Si un utilisateur est connecté, cacher les boutons de connexion et de création de compte
        const buttonsSection = document.querySelector('.buttons');
        buttonsSection.innerHTML = '<button class="button deconnexion">Déconnexion</button>';

        // Ajouter un gestionnaire d'événement pour la déconnexion
        const deconnexionButton = document.querySelector('.deconnexion');
        deconnexionButton.addEventListener('click', function() {
            // Supprimer l'utilisateur du localStorage
            localStorage.removeItem('user');

            // Recharger la page pour actualiser les boutons
            location.reload();
        });
    }
});