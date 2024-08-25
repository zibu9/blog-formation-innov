<?php 
  require_once '../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Blog Exemple</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/contact.css">
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
        <li><a href="../index.html">Accueil</a></li>
        <li class="dropdown">
          <a href="#">Catégories</a>
          <ul class="dropdown-content">
            <li><a href="category.html">Politique</a></li>
            <li><a href="category.html">Diplomatie</a></li>
            <li><a href="category.html">Santé</a></li>
            <li><a href="category.html">Sports</a></li>
          </ul>
        </li>
        <li><a href="about.html">À propos</a></li>
        <li><a href="#" class="active-page">Contact</a></li>
      </ul>
    </nav>
  </header>

  <!-- Contenu principal -->
  <main>
    <section class="contact">
      <h1>Contactez-nous</h1>
      <form id="contact-form" action="contact.php" method="POST">
        <div class="form-group">
          <label for="name">Nom :</label>
          <input type="text" id="name" name="name">
          <span class="error" id="name-error"></span>
        </div>
        <div class="form-group">
          <label for="email">Email :</label>
          <input type="email" id="email" name="email">
          <span class="error" id="email-error"></span>
        </div>
        <div class="form-group">
          <label for="message">Message :</label>
          <textarea id="message" name="message" rows="5"></textarea>
          <span class="error" id="message-error"></span>
        </div>
        <button type="submit">Envoyer</button>
      </form>
    </section>
  </main>

  <script src="../js/validation.js" defer></script>
  <script src="../js/script.js" defer></script>
</body>
</html>
