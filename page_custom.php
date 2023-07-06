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
    $requete = "SELECT photoVictime, nom, prenom, age, adresse, datepost, pseudo, infos, ville, numero, preuve1, preuve2, preuve3 FROM utilisateurs WHERE id = $id";

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
        echo '<div class="caption">TETE DU PEDO</div>';
        if (!empty($photoVictime)){
            echo '<img src="image/' . $photoVictime . '" class="post-image">';
        }
        echo '<div class="caption">PREUVE(S)</div>';
        echo '<div class="caption">PREUVE 1</div>';
        echo '<img src="image/' . $preuve1 . '" class="post-image">';
        if (!empty($preuve2)){
            echo '<div class="caption">PREUVE 2</div>';
            echo '<img src="image/' . $preuve2 . '" class="post-image">';
        }
        if (!empty($preuve3)){
            echo '<div class="caption">PREUVE 3</div>';
            echo '<img src="image/' . $preuve3 . '" class="post-image">';
        }
        echo '<div class="Info">Prénom = ' . $prenom . '</div>';
        if (!empty($nom)){
        echo  '<div class="Info">Nom =' . $nom . '</div>';
        }
        if (!empty($numero)){
            echo  '<div class="caption">Prénom =' . $numero . '</div>';
        }
        if (!empty($age)){
            echo  '<div class="caption">Nom =' . $age . '</div>';
        }
        if (!empty($ville)){
            echo  '<div class="caption">Ville =' . $ville . '</div>';
        }
        if (!empty($adresse)){
            echo  '<div class="caption">Adresse =' . $adresse . '</div>';
        }
        if (!empty($infos)){
            echo  '<div class="caption">Autres Infos =' . $infos . '</div>';
        }
        echo '<div class="caption">Posté le par ' . $pseudo . '</div>';
        echo '</div>';
    } else {
        echo "Post introuvable.";
    }

    // Fermeture de la connexion à la base de données
    mysqli_close($connexion);
    ?>
</body>
</html>
