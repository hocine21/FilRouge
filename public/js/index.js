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

 // Cacher le bouton
 document.querySelector('.show-button').classList.add('hidden');
    
 // Script pour l'animation des lettres
 document.addEventListener('DOMContentLoaded', () => {
     const letters = document.querySelectorAll('.bonjour-letter');
     letters.forEach((letter, index) => {
         setTimeout(() => {
             letter.classList.add('show');
             // Si c'est la dernière lettre, montrer le bouton
             if (index === letters.length - 1) {
                 document.querySelector('.show-button').classList.remove('hidden');
                 document.querySelector('.show-button').classList.add('visible');
             }
         }, index * 250); // Délai de 250ms entre chaque lettre
     });
 });

 // Fonction pour afficher le contenu
 function showContent() {
     document.querySelector('.blue-overlay').classList.add('show-blue-overlay');
     document.querySelector('.red-overlay').classList.add('show-red-overlay');
     document.querySelector('.show-button').style.display = 'none'; // Cacher le bouton
     const letters = document.querySelectorAll('.bonjour-letter');
     letters.forEach((letter, index) => {
         setTimeout(() => {
             letter.style.display = 'none'; // Cacher chaque lettre
         }, 0 + index * 0); // Délai de 3 secondes avant de cacher chaque lettre
     });
 }