<?php
//register.php
require_once '../config/config.php';

$errors = [];
$success = '';

if(isLoggedIn()){
    redirect('index.php'); // Rediriger vers la page d'accueil ou le tableau de bord
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = cleanInput($_POST['name']);
    $email = cleanInput($_POST['email']);
    $password = cleanInput($_POST['password']);
    $confirm_password = cleanInput($_POST['confirm_password']);

    // Validation des champs
    if (!isValidName($name)) {
        $errors[] = "Le nom n'est pas valide.";
    }
    if (!isValidEmail($email)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier si l'email est déjà utilisé
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $email_count = $stmt->fetchColumn();

        if ($email_count > 0) {
            $errors[] = "L'email est déjà utilisé.";
        }
    }

    // Si aucune erreur, on procède à l'enregistrement
    if (empty($errors)) {
        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Vérifier le nombre d'utilisateurs existants
        $stmt = $pdo->query("SELECT COUNT(*) FROM utilisateurs");
        $user_count = $stmt->fetchColumn();

        // Déterminer le rôle de l'utilisateur
        $role = ($user_count === 0) ? 'admin' : 'utilisateur';

        // Enregistrement dans la base de données
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe, role) VALUES (:name, :email, :password, :role)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $errors[] = "Une erreur s'est produite lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="form-container">
        <h2>Inscription</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmez le mot de passe :</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit">S'inscrire</button>
            </div>
        </form>
        <p>J'ai un Compte ? <a href="login.php">Connectez-vous ici</a></p>
    </div>
</body>
</html>
