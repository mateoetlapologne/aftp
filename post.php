<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $dateNaissance = date('Y-m-d', strtotime($_POST['date_naissance']));
    $ville = $_POST["ville"];
    $adresse = $_POST["adresse"];
    $numero = $_POST["numero"];
    $pseudo = $_POST["pseudo"];
    $infos = $_POST["infos"];

    // Vérification des extensions des fichiers uploadés
    $extensionsImages = array("jpg", "jpeg", "png");

    $erreur = false;

    // Vérification des extensions des images
    $imageExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    if (!in_array($imageExtension, $extensionsImages)) {
        $erreur = true;
        echo "L'extension de l'image n'est pas valide. Les extensions autorisées sont : " . implode(", ", $extensionsImages);
    }

    // Si aucune erreur, enregistrement des données dans la base de données et upload des images
    if (!$erreur) {
        // Connexion à la base de données
        $connexion = mysqli_connect("localhost", "root", "667", "aftp");

        // Vérification de la connexion
        if (!$connexion) {
            die("Erreur de connexion à la base de données : " . mysqli_connect_error());
        }

        // Génération d'un nom unique pour l'image de la victime
        $nomPhotoVictime = uniqid() . "." . $imageExtension;

        // Déplacement de l'image de la victime vers le dossier de destination
        $dossierDestination = "images/";
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $dossierDestination . $nomPhotoVictime)) {
            // Requête SQL pour insérer les données dans la base de données
            $requete = "INSERT INTO utilisateurs (image, nom, prenom, date_naissance, ville, adresse, numero, pseudo, infos)
                VALUES ('$nomPhotoVictime', '$nom', '$prenom', '$dateNaissance', '$ville', '$adresse', '$numero', '$pseudo', '$infos')";

            // Exécution de la requête
            if (mysqli_query($connexion, $requete)) {
                echo "Le post a été enregistré avec succès.";
            } else {
                echo "Erreur lors de l'enregistrement du post : " . mysqli_error($connexion);
            }
        } else {
            echo "Erreur lors de l'upload de l'image.";
        }

        // Fermeture de la connexion à la base de données
        mysqli_close($connexion);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de Post</title>
</head>
<body>
    <h1>Formulaire de Post</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="image">Image de la victime :</label>
        <input type="file" name="image" required>
        <br><br>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="date_naissance">Age :</label>
        <input type="date" name="date_naissance" >
        <br><br>

        <label for="ville">Ville :</label>
        <input type="text" name="ville" required>
        <br><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
        <br><br>

        <label for="numero">Numéro de téléphone :</label>
        <input type="text" name="numero" >
        <br><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required>
        <br><br>

        <label for="infos">Informations :</label>
        <textarea name="infos" ></textarea>
        <br><br>

        <input type="submit" value="Soumettre">
    </form>
</body>
</html>
