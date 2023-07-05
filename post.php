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
    $extensionsPreuves = array();

    $erreur = false;

    // Vérification des extensions des images
    $imageExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    if (!in_array($imageExtension, $extensionsImages)) {
        $erreur = true;
        echo "L'extension de l'image n'est pas valide. Les extensions autorisées sont : " . implode(", ", $extensionsImages);
    }

    // Vérification des extensions des preuves
    foreach ($_FILES["preuve1"]["tmp_name"] as $key => $tmp_name) {
        $extension = strtolower(pathinfo($_FILES["preuve1"]["name"][$key], PATHINFO_EXTENSION));
        if (!empty($_FILES["preuve1"]["name"][$key]) && !in_array($extension, $extensionsImages)) {
            $erreur = true;
            echo "L'extension de la preuve " . ($key + 1) . " n'est pas valide. Les extensions autorisées sont : " . implode(", ", $extensionsImages);
        }
        $extensionsPreuves["preuve" . ($key + 1)] = $extension;
    }

    // Vérification des champs de texte
    if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $nom)) {
        $erreur = true;
        echo "Le nom ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $prenom)) {
        $erreur = true;
        echo "Le prénom ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!empty($ville) && !preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $ville)) {
        $erreur = true;
        echo "La ville ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!preg_match("/^\d{10}$/", $numero)) {
        $erreur = true;
        echo "Le numéro de téléphone doit comporter 10 chiffres.";
    }

    if (!preg_match("/^[a-zA-Z0-9À-ÿ\s-]+$/", $pseudo)) {
        $erreur = true;
        echo "Le pseudo ne doit contenir que des lettres, des chiffres, des espaces et des tirets.";
    }

    if (strlen($infos) > 350) {
        $erreur = true;
        echo "Les informations ne doivent pas dépasser 350 caractères.";
    }

    // Si aucune erreur, enregistrement des données dans la base de données
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
        move_uploaded_file($_FILES["image"]["tmp_name"], $dossierDestination . $nomPhotoVictime);

        // Génération de noms uniques pour les preuves et enregistrement dans la base de données
        $preuve1Nom = uniqid() . "." . $extensionsPreuves["preuve1"];
        $preuve2Nom = "";
        $preuve3Nom = "";

        if (!empty($_FILES["preuve2"]["name"])) {
            $preuve2Nom = uniqid() . "." . $extensionsPreuves["preuve2"];
            move_uploaded_file($_FILES["preuve2"]["tmp_name"], $dossierDestination . $preuve2Nom);
        }

        if (!empty($_FILES["preuve3"]["name"])) {
            $preuve3Nom = uniqid() . "." . $extensionsPreuves["preuve3"];
            move_uploaded_file($_FILES["preuve3"]["tmp_name"], $dossierDestination . $preuve3Nom);
        }
        $ip = $_SERVER["REMOTE_ADDR"];
        // Requête SQL pour insérer les données dans la base de données
        $requete = "INSERT INTO utilisateurs (image, preuve1, preuve2, preuve3, nom, prenom, date_naissance, ville, adresse, numero, pseudo, infos, ip)
            VALUES ('$nomPhotoVictime', '$preuve1Nom', '$preuve2Nom', '$preuve3Nom', '$nom', '$prenom', '$dateNaissance', '$ville', '$adresse', '$numero', '$pseudo', '$infos', '$ip')";

        // Exécution de la requête
        if (mysqli_query($connexion, $requete)) {
            echo "Le post a été enregistré avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement du post : " . mysqli_error($connexion);
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

        <label for="preuve1">Preuve 1 :</label>
        <input type="file" name="preuve1" required>
        <br><br>

        <label for="preuve2">Preuve 2 :</label>
        <input type="file" name="preuve2">
        <br><br>

        <label for="preuve3">Preuve 3 :</label>
        <input type="file" name="preuve3">
        <br><br>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="date_naissance">Age :</label>
        <input type="date" name="date_naissance">
        <br><br>

        <label for="ville">Ville :</label>
        <input type="text" name="ville" required>
        <br><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
        <br><br>

        <label for="numero">Numéro de téléphone :</label>
        <input type="text" name="numero">
        <br><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required>
        <br><br>

        <label for="infos">Informations :</label>
        <textarea name="infos"></textarea>
        <br><br>

        <input type="submit" value="Soumettre">
    </form>
</body>
</html>
