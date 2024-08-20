<?php
require_once '../config/config.php';

// Rediriger si non connecté
if (!isLoggedIn()) {
    redirect('login.php');
}

// Vérifier le rôle de l'utilisateur
$isAdmin = isAdmin();

// Obtenir les informations des articles pour les utilisateurs normaux ou les admins
if ($isAdmin) {
    // Pour l'admin : récupérer les utilisateurs et les articles
    $stmtUsers = $pdo->query("SELECT * FROM utilisateurs");
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtArticles = $pdo->query("SELECT * FROM articles");
    $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtCategories = $pdo->query("SELECT * FROM categories");
    $categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);
    
} else {
    // Pour les utilisateurs normaux : récupérer uniquement leurs articles
    $userId = $_SESSION['user']['id'];
    
    $stmtArticles = $pdo->prepare("SELECT * FROM articles WHERE id_utilisateur = :user_id");
    $stmtArticles->bindParam(':user_id', $userId);
    $stmtArticles->execute();
    $articles = $stmtArticles->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <header>
        <h1>Tableau de bord</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <?php if ($isAdmin): ?>
            <section>
                <h2>Gestion des Utilisateurs</h2>
                <a href="add_user.php" class="add-button">+</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['nom_utilisateur']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo $user['statut'] ? 'Activé' : 'Bloqué'; ?></td>
                                <td class="table-actions">
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Modifier</a>
                                    <a href="disable_user.php?id=<?php echo $user['id']; ?>">Desactiver</a>
                                    <!-- Ajoutez ici une option pour activer/désactiver -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <section>
                <h2>Gestion des Catégories</h2>
                <a href="add_category.php" class="add-button">+</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category['id']); ?></td>
                                <td><?php echo htmlspecialchars($category['nom']); ?></td>
                                <td class="table-actions">
                                    <a href="edit_category.php?id=<?php echo $category['id']; ?>">Modifier</a>
                                    <a href="delete_category.php?id=<?php echo $category['id']; ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

        <?php else: ?>
            <section>
                <h2>Gestion de vos Articles</h2>
                <a href="add_article.php" class="add-button">+</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Date de Création</th>
                            <th>Approuvé</th>
                            <th>Actif</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($article['id']); ?></td>
                                <td><?php echo htmlspecialchars($article['titre']); ?></td>
                                <td><?php echo htmlspecialchars($article['date_creation']); ?></td>
                                <td><?php echo $article['approuve'] ? 'Oui' : 'Non'; ?></td>
                                <td><?php echo $article['actif'] ? 'Oui' : 'Non'; ?></td>
                                <td class="table-actions">
                                    <a href="edit_article.php?id=<?php echo $article['id']; ?>">Modifier</a>
                                    <!-- Ajouter une option pour supprimer si non approuvé -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
