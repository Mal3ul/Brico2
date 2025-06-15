<?php
function ajouter_au_panier($article, $quantite) {
    if (isset($_SESSION['user_id'])) { //* Vérifie si l'utilisateur est connecté
        $user_id = $_SESSION['user_id'];
        try {
            $connexion = getDbConnection();

            // Vérifie si l'article est déjà dans le panier
            $stmt = $connexion->prepare("SELECT quantite FROM panier WHERE user_id = :user_id AND article_ref = :article_ref");
            $stmt->execute([':user_id' => $user_id, ':article_ref' => $article]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Mettre à jour la quantité
                $nouvelle_quantite = $result['quantite'] + $quantite;
                $stmt = $connexion->prepare("UPDATE panier SET quantite = :quantite WHERE user_id = :user_id AND article_ref = :article_ref");
                $stmt->execute([':quantite' => $nouvelle_quantite, ':user_id' => $user_id, ':article_ref' => $article]);
            } else {
                // Ajouter un nouvel article
                $stmt = $connexion->prepare("INSERT INTO panier (user_id, article_ref, quantite) VALUES (:user_id, :article_ref, :quantite)");
                $stmt->execute([':user_id' => $user_id, ':article_ref' => $article, ':quantite' => $quantite]);
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout au panier : " . $e->getMessage();
        }
    } else {
        // Gestion avec cookies
        $panier = isset($_COOKIE['panier']) ? $_COOKIE['panier'] : '';
        $panier_array = $panier ? explode('|', $panier) : [];

        $nouveau_panier = [];
        $trouve = false;
        
        foreach ($panier_array as $item) {
            list($art, $qty) = explode(':', $item);
            if ($art == $article) {
                $qty += $quantite;
                $trouve = true;
            }
            $nouveau_panier[] = "$art:$qty";
        }
        
        if (!$trouve) {
            $nouveau_panier[] = "$article:$quantite";
        }

        $nouveau_panier_string = implode('|', $nouveau_panier);
        setcookie('panier', $nouveau_panier_string, 0, '/'); //time() + 3600
    }
}

function obtenir_panier() {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        return obtenir_panier_bdd($user_id);
    } else {
        $panier = isset($_COOKIE['panier']) ? $_COOKIE['panier'] : '';
        $panier_array = $panier ? explode('|', $panier) : [];
        
        $articles = [];
        foreach ($panier_array as $item) {
            list($article, $quantite) = explode(':', $item);
            $articles[$article] = $quantite;
        }

        return $articles;
    }
}

function supprimer_du_panier($article) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        try {
            $connexion = getDbConnection(); // Obtenez la connexion PDO
            $stmt = $connexion->prepare("DELETE FROM panier WHERE user_id = :user_id AND article_ref = :article_ref");
            $stmt->execute([':user_id' => $user_id, ':article_ref' => $article]);
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'article du panier : " . $e->getMessage();
        }
    } else {
        $panier = isset($_COOKIE['panier']) ? $_COOKIE['panier'] : '';
        $panier_array = $panier ? explode('|', $panier) : [];

        $nouveau_panier = [];
        foreach ($panier_array as $item) {
            list($art, $qty) = explode(':', $item);
            if ($art != $article) {
                $nouveau_panier[] = "$art:$qty";
            }
        }

        $nouveau_panier_string = implode('|', $nouveau_panier);
        setcookie('panier', $nouveau_panier_string, time() + 3600, '/');
    }
}

function vider_panier() {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        vider_panier_bdd($user_id);
    } else {
        setcookie('panier', '', 0, '/'); 
    }
}

function modifier_quantite($article, $quantite) {
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        try {
            $connexion = getDbConnection(); // Obtenez la connexion PDO

            if ($quantite > 0) {
                $stmt = $connexion->prepare("UPDATE panier SET quantite = :quantite WHERE user_id = :user_id AND article_ref = :article_ref");
                $stmt->execute([':quantite' => $quantite, ':user_id' => $user_id, ':article_ref' => $article]);
            } else {
                $stmt = $connexion->prepare("DELETE FROM panier WHERE user_id = :user_id AND article_ref = :article_ref");
                $stmt->execute([':user_id' => $user_id, ':article_ref' => $article]);
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la modification de la quantité : " . $e->getMessage();
        }
    } else {
        $panier = isset($_COOKIE['panier']) ? $_COOKIE['panier'] : '';
        $panier_array = $panier ? explode('|', $panier) : [];

        $nouveau_panier = [];
        foreach ($panier_array as $item) {
            list($art, $qty) = explode(':', $item);
            if ($art == $article) {
                $qty = $quantite;
            }
            if ($qty > 0) {
                $nouveau_panier[] = "$art:$qty";
            }
        }

        $nouveau_panier_string = implode('|', $nouveau_panier);
        setcookie('panier', $nouveau_panier_string, 0, '/');
    }
}


//
function getDbConnection() {
    try {
        $connexion = new PDO("mysql:host=localhost;dbname=brico", "root", "root");
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexion;
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        return null;
    }
}

function sauvegarder_panier($user_id) {
    if (!isset($_COOKIE['panier'])) {
        return;
    }

    $panier_cookie = $_COOKIE['panier'];
    $panier_array = explode('|', $panier_cookie);

    try {
        $connexion = getDbConnection(); // Obtenez la connexion PDO

        foreach ($panier_array as $item) {
            list($article, $quantite) = explode(':', $item);
            $stmt = $connexion->prepare("SELECT quantite FROM panier WHERE user_id = :user_id AND article_ref = :article_ref");
            $stmt->execute([':user_id' => $user_id, ':article_ref' => $article]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Mettre à jour la quantité si l'article existe déjà
                $nouvelle_quantite = $result['quantite'] + $quantite;
                $stmt = $connexion->prepare("UPDATE panier SET quantite = :quantite WHERE user_id = :user_id AND article_ref = :article_ref");
                $stmt->execute([':quantite' => $nouvelle_quantite, ':user_id' => $user_id, ':article_ref' => $article]);
            } else {
                // Insérer un nouvel article
                $stmt = $connexion->prepare("INSERT INTO panier (user_id, article_ref, quantite) VALUES (:user_id, :article_ref, :quantite)");
                $stmt->execute([':user_id' => $user_id, ':article_ref' => $article, ':quantite' => $quantite]);
            }
        }

        // Vider le panier du cookie après la synchronisation
        setcookie('panier', '', time() - 3600, '/');
    } catch (PDOException $e) {
        echo "Erreur lors de la synchronisation du panier : " . $e->getMessage();
    }
}


function obtenir_panier_bdd($user_id) {
    try {
        $connexion = getDbConnection(); // Obtenez la connexion PDO

        $stmt = $connexion->prepare("SELECT * FROM panier WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $panier = [];
        foreach ($articles as $article) {
            $panier[$article['article_ref']] = $article['quantite'];
        }

        return $panier;
    } catch (PDOException $e) {
        echo "Erreur de récupération du panier : " . $e->getMessage();
        return [];
    }
}

function vider_panier_bdd($user_id) {
    try {
        $connexion = getDbConnection(); // Obtenez la connexion PDO
        $stmt = $connexion->prepare("DELETE FROM panier WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
    } catch (PDOException $e) {
        echo "Erreur lors de la vidange du panier : " . $e->getMessage();
    }
}

function obtenir_tous_les_utilisateurs($exclure_admins = false) {
    try {
        $connexion = getDbConnection();
        if ($exclure_admins) {
            $stmt = $connexion->prepare("SELECT * FROM users WHERE profil NOT IN ('admin', 'gest')");
        } else {
            $stmt = $connexion->prepare("SELECT * FROM users");
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        return [];
    }
}

// Fonction pour supprimer un utilisateur, son panier et ses commandes

//! Commandes et panier ->  
function supprimer_utilisateur_et_ses_donnees($user_id) {
    try {
        $connexion = getDbConnection();
        
        // Supprimer les commandes de l'utilisateur
        $stmt = $connexion->prepare("DELETE FROM commande WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        
        // Supprimer le panier de l'utilisateur
        $stmt = $connexion->prepare("DELETE FROM panier WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        
        // Supprimer l'utilisateur
        $stmt = $connexion->prepare("DELETE FROM users WHERE id = :user_id");
        $stmt->execute([':user_id' => $user_id]);

        return true;
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
        return false;
    }
}

// Fonction pour mettre à jour les informations d'un utilisateur
function mettre_a_jour_utilisateur($user_id, $nouveau_nom, $nouveau_email) {
    try {
        $connexion = getDbConnection();
        $stmt = $connexion->prepare("UPDATE users SET username = :username, email = :email WHERE id = :user_id");
        $stmt->execute([
            ':username' => $nouveau_nom,
            ':email' => $nouveau_email,
            ':user_id' => $user_id
        ]);
        return true;
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
        return false;
    }
}
function mettre_a_jour_profil_utilisateur($user_id, $nouveau_profil) {
    try {
        $connexion = getDbConnection();
        $stmt = $connexion->prepare("UPDATE users SET profil = :profil WHERE id = :user_id");
        $stmt->execute([
            ':profil' => $nouveau_profil,
            ':user_id' => $user_id
        ]);
        return true;
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour du rôle de l'utilisateur : " . $e->getMessage();
        return false;
    }
}
?>