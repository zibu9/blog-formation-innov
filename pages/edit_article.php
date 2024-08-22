<?php
require_once '../config/config.php';

$errors = [];
$success = '';

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
    redirect('index.php'); // Rediriger vers la page d'accueil si l'utilisateur n'est pas autorisé à modifier l'article
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
            } else {
                // Supprimer l'ancienne image
                if ($article['image_lien'] && file_exists('../' . $article['image_lien'])) {
                    unlink('../' . $article['image_lien']);
                }
            }
        } else {
            $image_path = $article['image_lien']; // Garder l'ancienne image en cas d'erreur
        }
    } else {
        $image_path = $article['image_lien']; // Garder l'ancienne image si aucune nouvelle image n'est téléchargée
    }

    // Mise à jour de l'article dans la base de données
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE articles SET titre = :titre, contenu = :contenu, image_lien = :image_lien, id_categorie = :id_categorie WHERE id = :id");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':contenu', $contenu);
        $stmt->bindParam(':image_lien', $image_path);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->bindParam(':id', $article_id);

        if ($stmt->execute()) {
            $success = "Article mis à jour avec succès.";
        } else {
            $errors[] = "Une erreur s'est produite lors de la mise à jour de l'article.";
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
    <title>Modifier un article</title>
    <link rel="stylesheet" href="../css/form.css">
</head>
<body>
    <div class="form-container">
        <h2>Modifier l'article</h2>

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

        <form method="POST" action="edit_article.php?id=<?php echo $article_id; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="titre">Titre :</label>
                <input type="text" name="titre" id="titre" value="<?php echo htmlspecialchars($article['titre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu :</label>
                <textarea name="contenu" id="contenu" rows="5" required><?php echo htmlspecialchars($article['contenu']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="id_categorie">Catégorie :</label>
                <select name="id_categorie" id="id_categorie" required>
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id']; ?>" <?php echo $categorie['id'] == $article['id_categorie'] ? 'selected' : ''; ?>><?php echo $categorie['nom']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image (laisser vide pour conserver l'image actuelle) :</label>
                <input type="file" name="image" id="image">
                <?php if ($article['image_lien']): ?>
                    <img src="../<?php echo $article['image_lien']; ?>" alt="Image actuelle" style="max-width: 200px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <button type="submit">Mettre à jour l'article</button>
            </div>
        </form>
        <a href="index.php" class="btn-back">Retour à l'accueil</a>
    </div>
</body>
</html>
