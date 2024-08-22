<?php
require_once '../config/config.php';

// Rediriger si non connecté ou non admin
if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

// Vérifier si l'ID de l'article est fourni
if (isset($_GET['id'])) {
    $article_id = (int)$_GET['id'];
    $current_status = (int)$_GET['status'];
    // Préparer la requête SQL pour mettre à jour le statut d'approbation
    $new_status = $current_status ? 0 : 1;
    $stmt = $pdo->prepare("UPDATE articles SET approuve = :new_status WHERE id = :id");
    $stmt->bindParam(':new_status', $new_status);
    $stmt->bindParam(':id', $article_id);
    
    // Exécuter la requête
    if ($stmt->execute()) {
        redirect('index.php');
    } else {
        echo "Erreur lors de la mise à jour de l'article.";
    }
} else {
    redirect('index.php');
}
