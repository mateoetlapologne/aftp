<!DOCTYPE html>
<html>
<head>
    <title>Liste des Posts</title>
    <style>
        .post-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .post {
            width: 23%;
            margin-bottom: 20px;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
        }

        .post img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .post .caption {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Liste des Posts</h1>
    
    <?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "667", "aftp");

    // Vérification de la connexion
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Requête SQL pour récupérer les posts dans l'ordre chronologique
    $requete = "SELECT id, photoVictime, nom, prenom FROM utilisateurs ORDER BY datepost DESC";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);

    // Vérification des résultats
    if (mysqli_num_rows($resultat) > 0) {
        // Affichage des posts
        $count = 0;
        echo '<div class="post-container">';
        while ($row = mysqli_fetch_assoc($resultat)) {
            $id = $row["id"];
            $photoVictime = $row["photoVictime"];
            $nom = $row["nom"];
            $prenom = $row["prenom"];
            
            echo '<a href="page_custom.php?id=' . $id . '">';
            echo '<div class="post">';
            echo '<img src="chemin_vers_le_dossier_des_images/' . $photoVictime . '">';
            echo '<div class="caption">' . $nom . ' ' . $prenom . '</div>';
            echo '</div>';
            echo '</a>';

            $count++;
            if ($count % 4 == 0) {
                echo '</div><div class="post-container">';
            }
        }
        echo '</div>';
    } else {
        echo "Aucun post trouvé.";
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connexion);
    ?>
</body>
</html>
