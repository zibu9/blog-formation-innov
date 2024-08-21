<?php
    require_once '../config/config.php';

    // Vérifier si l'utilisateur est connecté et s'il est administrateur
    if (!isLoggedIn()) {
        redirect('login.php');
    }
    
    if (!isAdmin()) {
        redirect('index.php');
    }
    if (isset($_GET['id'])) {
        $user_id = cleanInput($_GET['id']);

        // Récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Inverser le statut de l'utilisateur (0 devient 1, 1 devient 0)
            $new_status = $user['statut'] ? 0 : 1;

            // Mettre à jour le statut de l'utilisateur dans la base de données
            $update_stmt = $pdo->prepare("UPDATE utilisateurs SET statut = :statut WHERE id = :id");
            $update_stmt->bindParam(':statut', $new_status);
            $update_stmt->bindParam(':id', $user_id);
            $update_stmt->execute();
        }
    }

    // Redirection vers la page précédente
    redirect($_SERVER['HTTP_REFERER']);
    exit;
