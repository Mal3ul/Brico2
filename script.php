<?php
try {
    // Connexion à la base de données
    $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête d'insertion
    $query = "INSERT INTO articles (nom, ref, prix, tva, promo, new) VALUES (:nom, :ref, :prix, :tva, :promo, :new)";
    $stmt = $connexion->prepare($query);

    // Tableau de données à insérer
    $articles = [
        ['marteau de menuisier bois verni', 81968453, 8.90, 20, NULL, 0],
        ['marteau massette fibre de verre', 80166978, 21.90, 20, NULL, 0],
        ['maillet de menuisier bois', 82039106, 14.90, 20, NULL, 0],
        ['marteau arrache-clou', 81968500, 12.90, 20, NULL, 0],
        ['tournevis électricien plat', 74936295, 1.95, 20, NULL, 1],
        ['tournevis électricien isolé plat', 67337361, 5.10, 20, NULL, 0],
        ['tournevis testeur de tension plat', 76292503, 2.90, 20, NULL, 0],
        ['tournevis sans fil', 81900760, 40.00, 20, NULL, 0],
        ['jeu de tournevis', 73923500, 34.90, 20, NULL, 1],
        ['tournevis cruciforme', 74936246, 3.20, 20, NULL, 0],
        ['jeu de tournevis torx', 74936372, 17.90, 20, 15, 0],
        ['tournevis boule cruciforme', 73708264, 3.95, 20, NULL, 0],
        ['scie de carreleur', 18850476, 9.95, 20, NULL, 0],
        ['lot de 2 lames pour scie à métaux', 70709401, 2.50, 20, 10, 0],
        ['scie à métaux', 70907452, 8.90, 20, NULL, 0],
        ['scie égoïne de charpentier', 70907354, 10.90, 20, NULL, 0],
        ['boîte à onglet manuelle', 70709653, 9.90, 20, NULL, 1],
        ['scie japonaise', 67998931, 18.90, 20, NULL, 0],
        ['scie à bûche', 63732655, 15.60, 20, NULL, 0],
        ['scie universelle', 70720265, 2.05, 20, NULL, 0],
        ['fourreau pour scie', 70709345, 3.90, 20, NULL, 0],
        ['scie à chantourner de plaquiste', 73550442, 5.99, 20, NULL, 0],
        ['pince coupante', 69241060, 39.00, 20, NULL, 0],
        ['pince à sertir les rails', 80150490, 24.90, 20, NULL, 0],
        ['pince à agraphage des profiles', 80124107, 55.00, 20, NULL, 0],
        ['pince à dénuder', 80125159, 8.90, 20, NULL, 0],
        ['pince-clé multiprise', 69587994, 49.90, 20, NULL, 1],
        ['pince coupe-mosaïque', 18699366, 19.90, 20, NULL, 0],
        ['pince perroquet', 18699345, 14.90, 20, NULL, 0],
        ['pince à cosse isolée', 70059913, 25.90, 20, NULL, 0],
        ['pince coupe-carrelage', 18699310, 9.90, 20, NULL, 0],
        ['pince à cintrer', 74669791, 20.40, 20, NULL, 0],
        ['pince coupe-boulons coupante', 80125144, 20.90, 20, NULL, 0],
        ['cisaille à tôle à ardoise coupe devant', 80125135, 10.90, 20, NULL, 0],
        ['pince pour collier de fixation', 66502576, 17.35, 20, NULL, 0],
        ['pince à bec', 80125154, 10.90, 20, NULL, 1]
    ];

    // Insertion des données avec la première lettre en majuscule
    foreach ($articles as $article) {
        $nom = mb_strtoupper(mb_substr($article[0], 0, 1)) . mb_substr($article[0], 1);
        $stmt->execute([
            ':nom' => $nom,
            ':ref' => $article[1],
            ':prix' => $article[2],
            ':tva' => $article[3],
            ':promo' => $article[4],
            ':new' => $article[5]
        ]);
    }

    echo "Les articles ont été insérés avec succès.";

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
