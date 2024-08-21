<?php
require_once '../config/config.php';

$errors = [];
$success = '';

if (!isLoggedIn()) {
    redirect('login.php'); // Rediriger vers la page de connexion si non connecté
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = cleanInput($_POST['titre']);
    $contenu = cleanInput($_POST['contenu']);
    $id_categorie = cleanInput($_POST['id_categorie']);
    $image = $_FILES['image'];

    // Validation des champs
    if (empty($titre)) {
        $errors[] = "Le titre est requis.";
    }
    if (empty($contenu)) {
        $errors[] = "Le contenu est requis.";
    }
    if (empty($id_categorie)) {
        $errors[] = "Veuillez choisir une catégorie.";
    }

    // Validation de l'image
    if ($image['size'] > 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_ext = pathinfo($image['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($image_ext), $allowed_extensions)) {
            $errors[] = "Le format de l'image est invalide. Les formats autorisés sont : jpg, jpeg, png, gif.";
        }

        if ($image['size'] > 2 * 1024 * 1024) {
            $errors[] = "La taille de l'image ne peut pas dépasser 2 Mo.";
        }

        if ($image['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors du téléchargement de l'image. Code d'erreur : " . $image['error'];
        }

        if (empty($errors)) {
            // Déplacement du fichier image
            $uploadFileDir = '../uploads/';
            $image_path = 'uploads/' . uniqid() . '.' . $image_ext;

            // Assurez-vous que le répertoire existe et a les permissions appropriées
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            if (!move_uploaded_file($image['tmp_name'], '../'.$image_path)) {
                $errors[] = "Impossible de déplacer le fichier téléchargé. Assurez-vous que le répertoire 'uploads' a les permissions appropriées.";
            }
        }
    } else {
        $errors[] = "Veuillez télécharger une image.";
    }

    // Si aucune erreur, on procède à l'enregistrement
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu, image_lien, id_categorie, id_utilisateur, date_creation, approuve, actif) 
                                VALUES (:titre, :contenu, :image_lien, :id_categorie, :id_utilisateur, NOW(), 0, 1)");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':contenu', $contenu);
        $stmt->bindParam(':image_lien', $image_path);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':id_utilisateur', $_SESSION['user']['id']);

        if ($stmt->execute()) {
            $success = "Article ajouté avec succès. Il sera publié après approbation.";
        } else {
            $errors[] = "Une erreur s'est produite lors de l'ajout de l'article.";
        }
    }
}

// Récupérer les catégories pour le formulaire
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un article</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="form-container">
        <h2>Ajouter un article</h2>

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

        <form method="POST" action="add_article.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="titre" required>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu :</label>
                <textarea name="contenu" id="contenu" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="id_categorie">Catégorie :</label>
                <select name="id_categorie" id="id_categorie" required>
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id']; ?>"><?php echo $categorie['nom']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image :</label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="form-group">
                <button type="submit">Ajouter l'article</button>
            </div>
        </form>
        <a href="index.php" class="btn-back">Retour à l'accueil</a>
    </div>
</body>
</html>
