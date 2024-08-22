<?php
    //logout.php
    require_once '../config/config.php';

    // Vérifie si l'utilisateur est connecté
    if (!isLoggedIn()) {
        redirect('login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    }

    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion
    redirect('login.php');
?>
