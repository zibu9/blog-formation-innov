/*****************Menu***************
************************************/
document.addEventListener("DOMContentLoaded", function() {
  // Sélectionne les éléments du DOM nécessaires
  const hamburgerMenu = document.getElementById("hamburger-menu");
  const navMenu = document.getElementById("nav-menu");
  const dropdown = document.querySelector(".dropdown");

  // Ajoute un événement de clic sur le menu hamburger
  hamburgerMenu.addEventListener("click", function() {
    navMenu.classList.toggle("active"); // Affiche ou cache le menu de navigation
  });

  // Ajoute un événement de clic sur le menu déroulant
  dropdown.addEventListener("click", function(e) {
    if (window.innerWidth <= 768) { // Vérifie si l'écran est petit
      e.preventDefault(); // Empêche le comportement par défaut du lien
      this.classList.toggle("active"); // Affiche ou cache le sous-menu
    }
  });

});

