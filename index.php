<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require_once 'config/config.php';

// Récupérer toutes les catégories pour le menu
$categories = $pdo->query('SELECT * FROM categories')->fetchAll();

// Récupérer les 3 derniers articles pour le slider
$derniers_articles = $pdo->query('SELECT * FROM articles WHERE actif = 1 AND approuve = 1 ORDER BY date_creation DESC LIMIT 3')->fetchAll();

// Récupérer un article aléatoire pour la section en vedette
$article_vedette = $pdo->query('SELECT * FROM articles WHERE actif = 1 AND approuve = 1 ORDER BY RAND() LIMIT 1')->fetch();

// Récupérer les 4 derniers articles (après ceux du slider) pour les cartes
$articles_recents = $pdo->query('SELECT articles.*, categories.nom AS categorie 
                                FROM articles 
                                INNER JOIN categories 
                                ON (articles.id_categorie = categories.id)  
                                WHERE articles.actif = 1 AND articles.approuve = 1 
                                ORDER BY articles.date_creation DESC LIMIT 4'
                                )->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Blog Exemple</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- En-tête -->
    <header>
      <div class="logo">MonBlog</div>
      <div class="hamburger-menu" id="hamburger-menu">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <nav id="nav-menu">
        <ul>
          <li><a href="#" class="active-page">Accueil</a></li>
          <li class="dropdown">
            <a href="#">Catégories</a>
            <ul class="dropdown-content">
              <?php foreach ($categories as $categorie) : ?>
                <li><a href="pages/category.php?id=<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['nom']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </li>
          <li><a href="pages/about.html">À propos</a></li>
          <li><a href="pages/contact.html">Contact</a></li>
        </ul>
      </nav>
    </header>
    <!-- Contenu principal -->
    <main>
      <section class="content">
        <div class="slider-container">
          <div class="slider">
            <!-- Diaporama -->
            <?php foreach ($derniers_articles as $article) : ?>
              <div class="slide" style="background-image: url('<?= htmlspecialchars($article['image_lien']) ?>');">
                <div class="slide-caption">
                  <h2><a href="pages/details-article.php?id=<?= $article['id'] ?>"><?= htmlspecialchars($article['titre']) ?></a></h2>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="prev" onclick="prevSlide()">&#10094;</button>
          <button class="next" onclick="nextSlide()">&#10095;</button>
        </div>

        <div class="featured-article">
          <!-- Article en vedette -->
          <img src="<?= htmlspecialchars($article_vedette['image_lien']) ?>" alt="Image de l'article">
          <div class="article-info">
            <h2><?= htmlspecialchars($article_vedette['titre']) ?></h2>
            <p><?= substr(htmlspecialchars($article_vedette['contenu']), 0, 150) ?>...</p>
            <a href="pages/details-article.php?id=<?= $article_vedette['id'] ?>" class="read-more">Lire plus</a>
          </div>
        </div>
      </section>
      <!-- Deuxième section avec une grille de cartes -->
      <section class="cards-section">
        <?php foreach ($articles_recents as $article) : ?>
          <div class="card">
            <div class="badge"><?= htmlspecialchars($article['categorie']) ?></div>
            <a href="pages/details-article.php?id=<?= $article['id'] ?>"><img src="<?= htmlspecialchars($article['image_lien']) ?>" alt="Image"></a>
            <div class="card-content">
              <h3><?= htmlspecialchars($article['titre']) ?></h3>
              <p><?= substr(htmlspecialchars($article['contenu']), 0, 100) ?>...</p>
            </div>
          </div>
        <?php endforeach; ?>
      </section>
    </main>
    <!-- Pied de page -->
    <footer>
      <p>&copy; Skytech243 2024 MonBlog. Tous droits réservés.</p>
    </footer>
    <script src="js/script.js" defer></script>
    <script src="js/diaporama.js" defer></script>
</body>
</html>
