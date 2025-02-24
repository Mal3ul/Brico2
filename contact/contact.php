<?php
$titre = "Contact";
include_once("../panier/fonctions.php");
include_once('../inclus/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pro = $_POST['pro'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $tel = $_POST['tel'];
        $cp = $_POST['cp'];
        $question = $_POST['question'];
        $message = $_POST['message'];

        $query = "INSERT INTO messages (pro, nom, prenom, mail, tel, cp, question, message) VALUES (:pro, :nom, :prenom, :mail, :tel, :cp, :question, :message)";
        $stmt = $connexion->prepare($query);
        $stmt->execute([
            ':pro' => $pro,
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':mail' => $mail,
            ':tel' => $tel,
            ':cp' => $cp,
            ':question' => $question,
            ':message' => $message
        ]);

        echo "Inscription réussie.";
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

?>

<div class="container mt-5">
        <h2>Contact</h2><br>

        <form method="POST" class="mx-auto" style="max-width: 600px;"> 

            <div class="row mb-3">
                <label class="col-md-4 col-form-label text-md-end">Vous êtes * :</label>
                <div class="col-md-8 text-md-start">
                    <input type="radio" id="particulier" name="pro" value="0" required>
                    <label for="particulier" class="me-3">Un particulier</label>
                    <input type="radio" id="professionnel" name="pro" value="1">
                    <label for="professionnel">Un professionnel</label>
                </div>
            </div>

            <div class="row mb-3">
                <label for="nom" class="col-md-4 col-form-label text-md-end">Nom * :</label>
                <div class="col-md-8">
                    <input type="text" id="nom" name="nom" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="prenom" class="col-md-4 col-form-label text-md-end">Prénom * :</label>
                <div class="col-md-8">
                    <input type="text" id="prenom" name="prenom" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="mail" class="col-md-4 col-form-label text-md-end">Email * :</label>
                <div class="col-md-8">
                    <input type="email" id="mail" name="mail" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="tel" class="col-md-4 col-form-label text-md-end">Téléphone * :</label>
                <div class="col-md-8">
                    <input type="tel" id="tel" name="tel" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="cp" class="col-md-4 col-form-label text-md-end">Code postal * :</label>
                <div class="col-md-8">
                    <input type="text" id="cp" name="cp" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="question" class="col-md-4 col-form-label text-md-end">Votre question * :</label>
                <div class="col-md-8">
                    <select id="question" name="question" class="form-select" required>
                        <option value="0">-- Sélectionnez une option --</option>
                        <option value="1">Faire une demande de devis</option>
                        <option value="2">Recevoir un conseil technique</option>
                        <option value="3">Trouver un fabricant-distributeur</option>
                        <option value="9">Autre</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="message" class="col-md-4 col-form-label text-md-end">Message * :</label>
                <div class="col-md-8">
                    <textarea id="message" name="message" rows="4" class="form-control" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-8 offset-md-4 text-md-start">
                    <input type="checkbox" required>
                    <label >J’accepte que mes coordonnées soient collectées.</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" name="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </div>

        </form>
    </div>



