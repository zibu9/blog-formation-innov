<?php
//add_categorie.php
require_once '../config/config.php';

$errors = [];
$success = '';

// Vérifier si l'utilisateur est connecté et s'il est administrateur
if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

// Traiter le formulaire lorsqu'il est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_name = cleanInput($_POST['category_name']);

    // Validation du champ
    if (empty($category_name)) {
        $errors[] = "Le nom de la catégorie ne peut pas être vide.";
    } elseif (strlen($category_name) < 3) {
        $errors[] = "Le nom de la catégorie doit comporter au moins 3 caractères.";
    }

    // Vérifier si la catégorie existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE nom = :nom");
    $stmt->bindParam(':nom', $category_name);
    $stmt->execute();
    $category_exists = $stmt->fetchColumn();

    if ($category_exists) {
        $errors[] = "Une catégorie avec ce nom existe déjà.";
    }

    // Si aucune erreur, on procède à l'ajout de la catégorie
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO categories (nom) VALUES (:nom)");
        $stmt->bindParam(':nom', $category_name);

        if ($stmt->execute()) {
            $success = "Catégorie ajoutée avec succès.";
        } else {
            $errors[] = "Une erreur s'est produite lors de l'ajout de la catégorie. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Catégorie</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="form-container">
        <h2>Ajouter une Catégorie</h2>

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

        <form method="POST" action="add_category.php">
            <div class="form-group">
                <label for="category_name">Nom de la catégorie :</label>
                <input type="text" name="category_name" id="category_name" required>
            </div>
            <div class="form-group">
                <button type="submit">Ajouter la Catégorie</button>
            </div>
        </form>
        
        <!-- Bouton pour retourner à la page d'accueil -->
        <div class="form-group">
            <a href="index.php" class="button">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
