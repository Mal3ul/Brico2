<?php
// Page de gestion des utilisateurs pour les gestionnaires et les admins
// Permet de supprimer, modifier et changer le rôle des utilisateurs

include_once('../inclus/header.php');
include_once('../panier/fonctions.php');

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['profil'], ['gest', 'admin'])){
    header('Location: ../index.php');
    exit();
}

$exclure_admins = ($_SESSION['profil'] == 'gest');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer'])) {
        $user_id = $_POST['user_id'];
        supprimer_utilisateur_et_ses_donnees($user_id);
    } elseif (isset($_POST['modifier'])) {
        $user_id = $_POST['user_id'];
        $nouveau_nom = $_POST['username'];
        $nouveau_email = $_POST['email'];
        mettre_a_jour_utilisateur($user_id, $nouveau_nom, $nouveau_email);
    }
    elseif (isset($_POST['modifier_profil']) && $_SESSION['profil'] == 'admin') {
        $user_id = $_POST['user_id'];
        $nouveau_profil = $_POST['profil'];
        mettre_a_jour_profil_utilisateur($user_id, $nouveau_profil);
    }
}

$utilisateurs = obtenir_tous_les_utilisateurs($exclure_admins);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Gestion des Utilisateurs</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?php echo $utilisateur['id']; ?></td>
                    <td><?php echo htmlspecialchars($utilisateur['username']); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                    <td><?php echo htmlspecialchars($utilisateur['profil']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $utilisateur['id']; ?>">
                            <input type="submit" name="supprimer" value="Supprimer">
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $utilisateur['id']; ?>">
                            <input type="text" name="username" value="<?php echo htmlspecialchars($utilisateur['username']); ?>" required>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required>
                            <input type="submit" name="modifier" value="Modifier">
                        </form>
                        <?php if ($_SESSION['profil'] == 'admin'): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $utilisateur['id']; ?>">
                                <select name="profil">
                                    <option value="client" <?php if ($utilisateur['profil'] == 'client') echo 'selected'; ?>>Client</option>
                                    <option value="gest" <?php if ($utilisateur['profil'] == 'gest') echo 'selected'; ?>>Gestionnaire</option>
                                    <option value="admin" <?php if ($utilisateur['profil'] == 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                                <input type="submit" name="modifier_profil" value="Modifier Rôle">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
