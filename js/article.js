// Définition des articles comme un tableau d'objets
const articles = [
    {
      id: 1,
      title: "Article 1",
      description: "Description de l'article 1.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 1"
    },
    {
      id: 2,
      title: "Article 2",
      description: "Description de l'article 2.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 2"
    },
    {
      id: 3,
      title: "Article 3",
      description: "Description de l'article 3.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 3"
    },
    {
      id: 4,
      title: "Article 4",
      description: "Description de l'article 4.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 4"
    },
    {
      id: 5,
      title: "Article 5",
      description: "Description de l'article 5.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 5"
    },
    {
      id: 6,
      title: "Article 6",
      description: "Description de l'article 6.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 6"
    },
    {
      id: 7,
      title: "Article 7",
      description: "Description de l'article 7.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 7"
    },
    {
      id: 8,
      title: "Article 8",
      description: "Description de l'article 8.",
      image: "https://via.placeholder.com/360x260",
      category: "Catégorie 8"
    },
    {
        id: 9,
        title: "Article 9",
        description: "Description de l'article 9.",
        image: "https://via.placeholder.com/360x260",
        category: "Catégorie 9"
    },

    {
        id: 10,
        title: "Article 10",
        description: "Description de l'article 10.",
        image: "https://via.placeholder.com/360x260",
        category: "Catégorie 10"
    },
    
  ];
  
  // Fonction pour générer le HTML des articles
  function generateArticlesHtml(articles) {
    // Utilisation de map pour créer un tableau de chaînes HTML pour chaque article
    return articles.map(article => `
        <div class="card">
          <div class="badge">${article.category}</div>
          <a href="pages/details-article.html"><img src="${article.image}" alt="Image 1"></a>
          <div class="card-content">
            <h3>${article.title}</h3>
            <p>${article.description}</p>
          </div>
        </div>
    `).join(''); // Combinaison de toutes les chaînes HTML en une seule
  }
  
  // Ajouter les articles à la page d'accueil lorsque le DOM est complètement chargé
  document.addEventListener('DOMContentLoaded', () => {
    // Sélectionner l'élément de la page où les articles seront affichés
    const articlesSection = document.querySelector('.cards-section');
    // Définir le contenu HTML de l'élément en utilisant le HTML généré pour les articles
    articlesSection.innerHTML = generateArticlesHtml(articles);
  });
  