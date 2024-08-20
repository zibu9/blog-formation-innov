document.addEventListener('DOMContentLoaded', () => { // Attends que le DOM soit entièrement chargé
    const form = document.getElementById('contact-form'); // Récupère l'élément du formulaire
  
    form.addEventListener('submit', function(event) { // Ajoute un écouteur d'événement pour la soumission du formulaire
      let isValid = true; // Variable pour vérifier si le formulaire est valide
  
      // Réinitialiser les messages d'erreur
      document.getElementById('name-error').textContent = ''; // Efface les messages d'erreur pour le nom
      document.getElementById('email-error').textContent = ''; // Efface les messages d'erreur pour l'email
      document.getElementById('message-error').textContent = ''; // Efface les messages d'erreur pour le message
  
      // Validation du nom
    const name = document.getElementById('name').value.trim(); // Récupère et nettoie la valeur du champ nom
    const namePattern = /^[A-Za-zÀ-ÿ\s]+$/; // Expression régulière pour vérifier que le nom contient uniquement des lettres et des espaces

    if (name === '') { // Vérifie si le nom est vide
      document.getElementById('name-error').textContent = 'Le nom est requis.'; // Affiche un message d'erreur
      isValid = false; // Définit isValid à false
    } else if (!namePattern.test(name)) { // Vérifie si le nom contient des chiffres ou des caractères non autorisés
      document.getElementById('name-error').textContent = 'Le nom ne doit contenir que des lettres et des espaces.'; // Affiche un message d'erreur
      isValid = false; // Définit isValid à false
    }
  
      // Validation de l'email
      const email = document.getElementById('email').value.trim(); // Récupère et nettoie la valeur du champ email
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Expression régulière pour vérifier le format de l'email
      if (email === '') { // Vérifie si l'email est vide
        document.getElementById('email-error').textContent = 'L\'email est requis.'; // Affiche un message d'erreur
        isValid = false; // Définit isValid à false
      } else if (!emailPattern.test(email)) { // Vérifie si l'email ne correspond pas au format attendu
        document.getElementById('email-error').textContent = 'L\'email n\'est pas valide.'; // Affiche un message d'erreur
        isValid = false; // Définit isValid à false
      }
  
      // Validation du message
      const message = document.getElementById('message').value.trim(); // Récupère et nettoie la valeur du champ message
      if (message === '') { // Vérifie si le message est vide
        document.getElementById('message-error').textContent = 'Le message est requis.'; // Affiche un message d'erreur
        isValid = false; // Définit isValid à false
      }
  
      if (!isValid) { // Vérifie si le formulaire est invalide
        event.preventDefault(); // Empêche l'envoi du formulaire si des erreurs sont présentes
      }
    });
  });
  