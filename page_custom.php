<!DOCTYPE html>
<html>
<head>
    <title>Page personnalisée</title>
    <style>
        .post {
            text-align: center;
        }

        .post img {
            width: 300px;
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
    <?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "667", "aftp");

    // Vérification de la connexion
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Récupération de l'identifiant du post depuis l'URL
    $id = $_GET['id'];

    // Requête SQL pour récupérer les détails du post en fonction de l'identifiant
    $requete = "SELECT photoVictime, nom, prenom FROM utilisateurs WHERE id = $id";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);

    // Vérification des résultats
    if (mysqli_num_rows($resultat) > 0) {
        // Affichage du post
        $row = mysqli_fetch_assoc($resultat);
        $photoVictime = $row["photoVictime"];
        $nom = $row["nom"];
        $prenom = $row["prenom"];

        echo '<div class="post">';
        echo '<img src="chemin_vers_le_dossier_des_images/' . $photoVictime . '">';
        echo '<div class="caption">' . $nom . ' ' . $prenom . '</div>';
        echo '</div>';
    } else {
        echo "Post introuvable.";
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connexion);
    ?>
</body>
</html>
