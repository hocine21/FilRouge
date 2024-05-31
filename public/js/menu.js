// JavaScript
function toggleMenu() {
    var nav = document.getElementById("main-nav");
    var icons = document.getElementById("icon-container");

    // Vérifie si l'écran est de petite taille (mobile)
    if (window.innerWidth <= 600) {
        // Bascule l'état du menu et des icônes
        nav.style.display = nav.style.display === "none" ? "block" : "none";
        icons.style.display = icons.style.display === "none" ? "block" : "none";
    }
}

// Cachez le menu et les icônes sur les écrans plus larges (PC et tablette)
window.addEventListener("resize", function() {
    var nav = document.getElementById("main-nav");
    var icons = document.getElementById("icon-container");

    // Vérifie si l'écran est de grande taille (PC et tablette)
    if (window.innerWidth > 600) {
        nav.style.display = "block";
        icons.style.display = "block";
    }
});
