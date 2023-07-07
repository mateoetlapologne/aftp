<?php
    
    // Connexion à la base de données
    $connexion = mysqli_connect("localhost", "root", "Pologne667", "aftp");

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
        $age = $row["age"];
        $adresse = $row["adresse"];
        $pseudo = $row["pseudo"];
        $infos = $row["infos"];
        $ville = $row["ville"];
        $numero = $row["numero"];
        $preuve1 = $row["preuve1"];
        $preuve2 = $row["preuve2"];
        $preuve3 = $row["preuve3"];
        $timestamp = strtotime($row["datepost"]);
        $datepost = date("Y-m-d", $timestamp);
    

        ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/custom.css">
    <title> squigame </title>
</head>
<body>
    
    <?php
        echo '<div class="post">';
        echo '<a class="caption ">TETE DU PEDO</a>';
        echo '<a href="https://www.aftp.fr/aftp/image/' . $photoVictime . '"><button class="rounded-button">Voir</button></a>';
        echo '<div class="caption">PREUVE(S)</div>';
        echo '<a class="caption">Preuve 1</a>';
        echo '<a href="https://www.aftp.fr/aftp/image/' . $preuve1 . '"><button class="rounded-button">Voir</button></a>';
        if (!empty($preuve2)){
            echo '<a class="caption">Preuve 2</a>';
            echo '<a href="https://www.aftp.fr/aftp/image/' . $preuve2 . '"><button class="rounded-button">Voir</button></a>';
        }
        if (!empty($preuve3)){
            echo '<a class="caption">Preuve 3</a>';
            echo '<a href="https://www.aftp.fr/aftp/image/' . $preuve3 . '"><button class="rounded-button">Voir</button></a>';
        }
        echo '<div class="caption">Prénom = ' . $prenom . '</div>';
        if (!empty($nom)){
            echo  '<div class="caption">Nom = ' . $nom . '</div>';
        }
        if (!empty($numero)){
            echo  '<div class="caption">Numéro de telephone  = ' . $numero . '</div>';
        }
        if (!$age == 999){
            echo  '<div class="caption">Nom = ' . $age . '</div>';
        }
        if (!empty($ville)){
            echo  '<div class="caption">Ville = ' . $ville . '</div>';
        }
        if (!empty($adresse)){
            echo  '<div class="caption">Adresse = ' . $adresse . '</div>';
        }
        if (!empty($infos)){
            echo  '<div class="caption">Autres Infos = ' . $infos . '</div>';
        }
        echo '<div class="caption">Posté le ' . $datepost . ' par ' .pseudo .'</div>';
        echo '</div>';
    } else {
        echo "Post introuvable.";
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
