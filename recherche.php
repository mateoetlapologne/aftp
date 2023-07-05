<!DOCTYPE html>
<html>
<head>
    <title>Liste des Posts</title>
</head>
<body>
    <h1>Liste des Posts</h1>

    <form action="recherche.php" method="GET">
        <input type="text" name="search" placeholder="Rechercher par nom ou prénom">
        <button type="submit">Rechercher</button>
    </form>

    <?php
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "667", "aftp");

    // Vérification de la connexion
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    // Vérification si une recherche a été effectuée
    if (isset($_GET['search'])) {
        $search = $_GET['search'];

        // Requête SQL pour récupérer les posts correspondant à la recherche
        // Requête SQL pour récupérer les posts correspondant à la recherche
        $requete = "SELECT id, photoVictime, nom, prenom FROM utilisateurs WHERE nom = '$search' OR prenom = '$search' OR numero ='$search' ORDER BY datepost DESC";


        // Exécution de la requête
        $resultat = mysqli_query($connexion, $requete);

        // Vérification des résultats
        if (mysqli_num_rows($resultat) > 0) {
            // Affichage des posts
            while ($row = mysqli_fetch_assoc($resultat)) {
                $id = $row["id"];
                $photoVictime = $row["photoVictime"];
                $nom = $row["nom"];
                $prenom = $row["prenom"];

                // Création du lien hypertexte vers la page personnalisée du post
                echo '<a href="page_custom.php?id=' . $id . '">';
                echo '<div class="post">';
                echo '<img src="chemin_vers_le_dossier_des_images/' . $photoVictime . '">';
                echo '<div>' . $nom . ' ' . $prenom . '</div>';
                echo '</div>';
                echo '</a>';
            }
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
