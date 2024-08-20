
// Attends que le DOM soit complètement chargé avant d'exécuter le code
document.addEventListener("DOMContentLoaded", function() {
    let currentSlide = 0; // Variable pour suivre l'index du slide actuellement affiché
    const slides = document.querySelectorAll('.slide'); // Sélectionne tous les éléments avec la classe "slide"
    const nextButton = document.querySelector('.next'); // Sélectionne le bouton "Next" pour aller au slide suivant
    const prevButton = document.querySelector('.prev'); // Sélectionne le bouton "Prev" pour revenir au slide précédent
  
    // Fonction pour afficher un slide spécifique
    function showSlide(index) {
        slides.forEach((slide, i) => { // Parcourt tous les slides
            slide.style.display = 'none'; // Cache tous les slides
            if (i === index) { // Si l'index correspond au slide actuel
                slide.style.display = 'block'; // Affiche le slide courant
            }
        });
    }
  
    // Ajoute un écouteur d'événement sur le bouton "Next" pour afficher le slide suivant
    nextButton.addEventListener('click', function() {
        currentSlide = (currentSlide + 1) % slides.length; // Incrémente l'index du slide actuel et revient au début si on dépasse le nombre total de slides
        showSlide(currentSlide); // Affiche le nouveau slide
    });
  
    // Ajoute un écouteur d'événement sur le bouton "Prev" pour afficher le slide précédent
    prevButton.addEventListener('click', function() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length; // Décrémente l'index du slide actuel et revient au dernier slide si on va en dessous de zéro
        showSlide(currentSlide); // Affiche le nouveau slide
    });
  
    // Change automatiquement de slide toutes les 5 secondes
    setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length; // Incrémente l'index du slide actuel pour aller au suivant
        showSlide(currentSlide); // Affiche le nouveau slide
    }, 5000); // 5000ms = 5 secondes
  
    // Affiche le premier slide au chargement de la page
    showSlide(currentSlide);
});
