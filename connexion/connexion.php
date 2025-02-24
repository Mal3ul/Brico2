<?php 
$titre= "Connexion";
include_once("../panier/fonctions.php");
include_once("../inclus/header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $connexion = getDbConnection(); // Connexion PDO

        $stmt = $connexion->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['profil'] = $user['profil'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            sauvegarder_panier($user['id']);
            echo "Connexion réussie. Bonjour " . htmlspecialchars($user['username']) . " !";
            echo " Ton profil est " . htmlspecialchars($user['profil']) . " !";

            header('Location: ../inclus/index.php');
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="POST">
        <label for="username">Nom d'utilisateur :</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="login" value="Se connecter">
        <a href="inscription.php">Inscription</a>
    </form>
</body>
</html>