<?php
require_once '../config/config.php';

if (!isLoggedIn()) {
    redirect('login.php'); // Rediriger vers la page de connexion si non connecté
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php'); // Rediriger vers la page d'accueil si l'ID n'est pas valide
}

$article_id = (int)$_GET['id'];

// Vérifier si l'article appartient à l'utilisateur ou si l'utilisateur est un admin
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->bindParam(':id', $article_id);
$stmt->execute();
$article = $stmt->fetch();

if (!$article) {
    redirect('index.php'); // Rediriger vers la page d'accueil si l'article n'existe pas
}

if ($_SESSION['user']['role'] != 'admin' && $article['id_utilisateur'] != $_SESSION['user']['id']) {
    redirect('index.php'); // Rediriger vers la page d'accueil si l'utilisateur n'est pas autorisé à supprimer l'article
}
$actif = $article['actif'] ? 0 : 1;
// Désactiver l'article
$stmt = $pdo->prepare("UPDATE articles SET actif = :actif WHERE id = :id");
$stmt->bindParam(':id', $article_id);
$stmt->bindParam(':actif', $actif);

if (!$stmt->execute()) {
    redirect('index.php'); // Rediriger vers la page d'accueil après suppression
}else{
    redirect('index.php');
}
