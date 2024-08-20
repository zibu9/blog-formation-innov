<?php
require_once '../config/config.php';

$errors = [];
$success = '';

if(isLoggedIn()){
    redirect('index.php'); // Rediriger vers la page d'accueil ou le tableau de bord
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);

    // Validation des champs
    if (!isValidEmail($email)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe ne peut pas être vide.";
    }

    // Si aucune erreur, on procède à la connexion
    if (empty($errors)) {
        // Préparer l'instruction SQL pour vérifier l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user'] = $user;
            redirect('index.php'); // Rediriger vers la page d'accueil ou le tableau de bord
        } else {
            $errors[] = "Adresse e-mail ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="form-container">
        <h2>Connexion</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Se connecter</button>
            </div>
        </form>
        <p>Pas encore inscrit ? <a href="register.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html>
