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
<h1>Liste des Posts</h1>
    
    <?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "667", "aftp");

    // Vérification de la connexion
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Requête SQL pour récupérer les posts dans l'ordre chronologique
    $requete = "SELECT photoVictime, nom, prenom FROM utilisateurs ORDER BY date_creation DESC";

    // Exécution de la requête
    $resultat = mysqli_query($connexion, $requete);

    // Vérification des résultats
    if (mysqli_num_rows($resultat) > 0) {
        // Affichage des posts
        $count = 0;
        echo '<div class="post-container">';
        while ($row = mysqli_fetch_assoc($resultat)) {
            $photoVictime = $row["photoVictime"];
            $nom = $row["nom"];
            $prenom = $row["prenom"];
            
            echo '<div class="post">';
            echo '<img src="chemin_vers_le_dossier_des_images/' . $photoVictime . '">';
            echo '<div class="caption">' . $nom . ' ' . $prenom . '</div>';
            echo '</div>';

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
