<!DOCTYPE html>
<html>
<head>
    <title>Affiche Ton Pedo</title>
</head>
<link rel="stylesheet" type="text/css" href="css/index.css">
<div class="navbar">
    <div class="left-button">
        <a href="#" class="nav-title">Affiche Ton Pedo</a>
    </div>
    <div class="right-buttons">
        <a href="post.php"><button class="rounded-button">Poster</button></a>
    </div>
</div>
<h1>Liste des Posts</h1>
<?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "667", "aftp");

    // Vérification de la connexion
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Requête SQL pour récupérer les posts dans l'ordre chronologique
    $requete = "SELECT photoVictime, nom, prenom FROM utilisateurs ORDER BY datepost DESC";
    $resultat = mysqli_query($connexion, $requete);

    // Vérification de la requête
    if (!$resultat) {
        echo "Erreur lors de la récupération des posts : " . mysqli_error($connexion);
    } else {
        // Parcourir les résultats et afficher les prévisualisations des posts
        while ($row = mysqli_fetch_assoc($resultat)) {
            $photoVictime = $row['photoVictime'];
            $nom = $row['nom'];
            $prenom = $row['prenom'];

            // Affichage de la prévisualisation
            echo '<div class="post-preview">';
            echo '<img src="chemin_vers_dossier_images/' . $photoVictime . '" alt="Photo de la victime">';
            echo '<h3>' . $nom . ' ' . $prenom . '</h3>';
            echo '</div>';
        }
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connexion);
    ?>

</body>
</html>
