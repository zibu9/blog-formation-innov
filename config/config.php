<?php
    // config/config.php

    // Démarre la session
    session_start();

    // Paramètres de connexion à la base de données
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root'); // Remplacez par votre nom d'utilisateur MySQL
    define('DB_PASS', ''); // Remplacez par votre mot de passe MySQL
    define('DB_NAME', 'blog_db'); // Remplacez par le nom de votre base de données

    // Connexion à la base de données avec PDO
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Fonction pour nettoyer les données
    function cleanInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Fonction pour rediriger l'utilisateur
    function redirect($url) {
        header("Location: $url");
        exit;
    }

    // Fonction pour vérifier si un utilisateur est connecté
    function isLoggedIn() {
        return isset($_SESSION['user']["id"]);
    }

    // Fonction pour vérifier si un utilisateur est admin
    function isAdmin() {
        return (isset($_SESSION['user']["id"]) && $_SESSION['user']["role"] === 'admin');
    }

    // fonction pour un debogage clairement visible
    function debug($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
?>
