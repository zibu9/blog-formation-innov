<?php
// Inclure le fichier de configuration pour la connexion à la base de données
require '../config/config.php';

// Récupérer l'ID de la catégorie depuis l'URL
$id_categorie = (isset($_GET['id']) && is_numeric($_GET['id']) ) ? (int)$_GET['id'] : redirect('../index.php');

// Vérifier si la catégorie existe
$categorie = $pdo->prepare('SELECT * FROM categories WHERE id = :id');
$categorie->execute(['id' => $id_categorie]);
$categorie = $categorie->fetch();

// Si la catégorie n'existe pas, rediriger vers la page d'accueil
if (!$categorie) {
    redirect('../index.php');
}

$articles = $pdo->prepare('SELECT * FROM articles WHERE id_categorie = :id_categorie AND actif = 1 AND approuve = 1 ORDER BY date_creation DESC LIMIT 12');
$articles->execute(['id_categorie' => $id_categorie]);
$articles = $articles->fetchAll();

// Récupérer toutes les catégories pour le menu
$categories = $pdo->query('SELECT * FROM categories')->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catégorie : <?= htmlspecialchars($categorie['nom']) ?> - Blog Exemple</title>
  <link rel="stylesheet" href="../css/style.css">
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
              <?php foreach ($categories as $cat) : ?>
                <li><a href="category.php?id=<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></a></li>
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
      <!-- Titre de la catégorie -->
      
      <!-- Section avec une grille de cartes -->
      <section class="cards-section">
        <?php if ($articles): ?>
          <?php foreach ($articles as $article) : ?>
            <div class="card">
              <div class="badge"><?= htmlspecialchars($categorie['nom']) ?></div>
              <a href="details-article.php?id=<?= $article['id'] ?>"><img src="../<?= htmlspecialchars($article['image_lien']) ?>" alt="Image"></a>
              <div class="card-content">
                <h3><?= htmlspecialchars($article['titre']) ?></h3>
                <p><?= substr(htmlspecialchars($article['contenu']), 0, 100) ?>...</p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun article disponible dans cette catégorie.</p>
        <?php endif; ?>
      </section>
    </main>

    <!-- Pied de page -->
    <footer>
      <p>&copy; Skytech243 2024 MonBlog. Tous droits réservés.</p>
    </footer>

    <script src="../js/script.js" defer></script>
    <!-- <script src="../js/article.js" defer></script> -->
</body>
</html>
