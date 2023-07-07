<!DOCTYPE html>
<html>
<head>
    <title>Liste des Posts</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="css/search.css">
<div class="navbar">
    <div class="left-button">
        <a href="index.php" class="nav-title">Affiche Ton Pedo</a>
    </div>
    <div class="right-buttons">
        <a href="post.php"><button class="rounded-button">Poster</button></a>
    </div>
    
</div>
    <h1>Liste des Posts</h1>

    <div class="search-container">
    <form action="recherche.php" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Rechercher par nom ou prénom">
        <button type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>
    <?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "Pologne667", "aftp");

    // Vérification de la connexion
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Vérification si une recherche a été effectuée
    if (isset($_GET['search'])) {
        $search = $_GET['search'];

        // Requête SQL pour récupérer les posts correspondant à la recherche
        // Requête SQL pour récupérer les posts correspondant à la recherche
        $requete = "SELECT id, photoVictime, nom, prenom, numero, ville, pseudo FROM utilisateurs WHERE nom = '$search' OR prenom = '$search' OR numero ='$search' OR ville = '$search' OR pseudo='$' ORDER BY datepost DESC";


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
    } else {
        echo "Effectuez une recherche.";
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connexion);
    ?>
</body>
</html>
