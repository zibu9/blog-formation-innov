<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require '../config/config.php';

// Récupérer l'ID de l'article depuis l'URL
$id = (isset($_GET['id']) && is_numeric($_GET['id']) ) ? (int)$_GET['id'] : redirect('../index.php');

// Récupérer les informations de l'article correspondant
$article = $pdo->prepare('SELECT * FROM articles WHERE id = :id AND actif = 1 AND approuve = 1');
$article->execute(['id' => $id]);
$article = $article->fetch();

// Si l'article n'existe pas ou n'est pas approuvé, rediriger vers la page d'accueil
if (!$article) {
    redirect('../index.php');
}

// Récupérer les articles récents de la même catégorie
$articles_similaires = $pdo->prepare('SELECT * FROM articles WHERE id_categorie = :id_categorie AND id != :id AND actif = 1 AND approuve = 1 ORDER BY date_creation DESC LIMIT 4');
$articles_similaires->execute([
    'id_categorie' => $article['id_categorie'],
    'id' => $id
]);
$articles_similaires = $articles_similaires->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($article['titre']) ?> - Blog Exemple</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/article.css">
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
        <li><a href="../index.php">Accueil</a></li>
        <li class="dropdown">
          <a href="#" class="active-page">Catégories</a>
          <ul class="dropdown-content">
            <?php
            // Récupérer toutes les catégories pour le menu
            $categories = $pdo->query('SELECT * FROM categories')->fetchAll();
            foreach ($categories as $categorie) : ?>
              <li><a href="category.php?id=<?= $categorie['id'] ?>"><?= htmlspecialchars($categorie['nom']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
        <li><a href="about.html">À propos</a></li>
        <li><a href="contact.html">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Contenu principal -->
  <main>
    <!-- Section d'article -->
    <section class="article-section">
      <div class="article-content">
        <img src="../<?= htmlspecialchars($article['image_lien']) ?>" alt="Image de l'article" class="article-image">
        <h1 class="article-title"><?= htmlspecialchars($article['titre']) ?></h1>
        <p><?= nl2br(htmlspecialchars($article['contenu'])) ?></p>
      </div>
    </section>

    <!-- Section avec les cartes -->
    <section class="related-cards">
      <h1>Dans la même catégorie</h1>
      <div class="cards-section">
        <?php foreach ($articles_similaires as $similaire) : ?>
          <div class="card">
            <div class="badge"><?= htmlspecialchars($categories[$similaire['id_categorie']]['nom']) ?></div>
            <a href="details-article.php?id=<?= $similaire['id'] ?>"><img src="../<?= htmlspecialchars($similaire['image_lien']) ?>" alt="Image"></a>
            <div class="card-content">
              <h3><?= htmlspecialchars($similaire['titre']) ?></h3>
              <p><?= substr(htmlspecialchars($similaire['contenu']), 0, 100) ?>...</p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <!-- Pied de page -->
  <footer>
    <p>&copy; 2024 MonBlog. Tous droits réservés.</p>
  </footer>

  <script src="../js/validation.js" defer></script>
  <script src="../js/script.js" defer></script>
</body>
</html>
