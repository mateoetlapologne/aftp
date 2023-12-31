<!DOCTYPE html>
<html>
<head>
    <title>Liste des Posts</title>
    <style>
    </style>
</head>
<body>
<link rel="stylesheet" type="text/css" href="css/index.css">
<div class="navbar">
    <div class="left-button">
        <a href="index.php" class="nav-title">Affiche Ton Pedo</a>
    </div>
    <div class="right-buttons">
        <a href="post.php"><button class="rounded-button">Poster</button></a>
        <a href="recherche.php"><button class="rounded-button">Rechercher</button></a>
    </div>
</div>
    <?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "Pologne667", "aftp");
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
    <footer>
    <div class="container">
        <p class="btc-address">Adresse BTC : 3DsfSuEx5s2iAvfo92EjPHw2H69pYMGkeN</p>
        <p class="btc-address">Adresse ETH : 0x88cD9D40de35f36A82918b168f78AC1D233BF6bd</p>
    </div>
</footer>

</body>
</html>
