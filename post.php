<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de Post</title>
</head>
<body>
    <h1>Formulaire de Post</h1>
    <form method="POST" action="traitement.php" enctype="multipart/form-data">
        <label for="image">Image de la victime :</label>
        <input type="file" name="image" id="image" required>
        <br><br>
        <label for="preuve1">Preuve 1 :</label>
        <input type="file" name="preuve1" id="preuve1" required>
        <br><br>
        <label for="preuve2">Preuve 2 :</label>
        <input type="file" name="preuve2" id="preuve2">
        <br><br>
        <label for="preuve3">Preuve 3 :</label>
        <input type="file" name="preuve3" id="preuve3">
        <br><br>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
        <br><br>
        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required>
        <br><br>
        <label for="date_naissance">Date de naissance :</label>
        <input type="date" name="date_naissance" id="date_naissance" required>
        <br><br>
        <label for="ville">Ville :</label>
        <input type="text" name="ville" id="ville" required>
        <br><br>
        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" id="adresse" required>
        <br><br>
        <label for="numero">Numéro de téléphone :</label>
        <input type="text" name="numero" id="numero" required>
        <br><br>
        <label for="reseaux_sociaux">Réseaux sociaux :</label>
        <input type="text" name="reseaux_sociaux" id="reseaux_sociaux">
        <br><br>
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" required>
        <br><br>
        <label for="infos">Informations :</label>
        <textarea name="infos" id="infos" rows="4" required></textarea>
        <br><br>
        <input type="submit" value="Soumettre">
    </form>
</body>
</html>


<?php
// Vérification des champs avec des lettres, espaces et tirets
function verifierChampLettres($valeur)
{
    return preg_match("/^[A-Za-z\s\-]+$/", $valeur);
}

// Vérification de la longueur maximale du champ pseudo
function verifierLongueurPseudo($valeur)
{
    return strlen($valeur) <= 20;
}

// Vérification du format de la date de naissance
function verifierFormatDateNaissance($valeur)
{
    return preg_match("/^\d{4}-\d{2}-\d{2}$/", $valeur);
}

// Vérification du format du numéro de téléphone
function verifierFormatNumero($valeur)
{
    return preg_match("/^\d{10}$/", $valeur);
}

// Vérification des extensions d'image valides
function verifierExtensionsImage($nomFichier)
{
    $extensionsValides = array("jpg", "jpeg", "png", "gif");
    $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
    return in_array($extension, $extensionsValides);
}

// Générer un nom unique pour les fichiers
function genererNomUnique($extension)
{
    return uniqid() . "." . $extension;
}

// Vérification des champs du formulaire et traitement des données
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $image = $_FILES["image"];
    $preuve1 = $_FILES["preuve1"];
    $preuve2 = $_FILES["preuve2"];
    $preuve3 = $_FILES["preuve3"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $dateNaissance = $_POST["date_naissance"];
    $ville = $_POST["ville"];
    $adresse = $_POST["adresse"];
    $numero = $_POST["numero"];
    $reseauxSociaux = $_POST["reseaux_sociaux"];
    $pseudo = $_POST["pseudo"];
    $infos = $_POST["infos"];

    // Validation des champs
    if (!verifierChampLettres($nom) || !verifierChampLettres($prenom) || !verifierChampLettres($ville) || !verifierChampLettres($adresse)) {
        echo "Les champs Nom, Prénom, Ville et Adresse doivent contenir uniquement des lettres, espaces et tirets.";
    } elseif (!verifierLongueurPseudo($pseudo)) {
        echo "Le champ Pseudo ne doit pas dépasser 20 caractères.";
    } elseif (!verifierFormatDateNaissance($dateNaissance)) {
        echo "Le format de la date de naissance est incorrect. Utilisez le format AAAA-MM-JJ.";
    } elseif (!verifierFormatNumero($numero)) {
        echo "Le numéro de téléphone doit comporter 10 chiffres.";
    } elseif (strlen($infos) > 350) {
        echo "Le champ Infos ne doit pas dépasser 350 caractères.";
    } elseif (!verifierExtensionsImage($image["name"]) || !verifierExtensionsImage($preuve1["name"]) || (!empty($preuve2["name"]) && !verifierExtensionsImage($preuve2["name"])) || (!empty($preuve3["name"]) && !verifierExtensionsImage($preuve3["name"]))) {
        echo "Les fichiers doivent être des images au format JPG, JPEG, PNG ou GIF.";
    } else {
        // Dossier de destination des images
        $dossierDestination = "images/";

        // Génération des noms uniques pour les fichiers
        $nomImage = genererNomUnique(pathinfo($image["name"], PATHINFO_EXTENSION));
        $nomPreuve1 = genererNomUnique(pathinfo($preuve1["name"], PATHINFO_EXTENSION));
        $nomPreuve2 = !empty($preuve2["name"]) ? genererNomUnique(pathinfo($preuve2["name"], PATHINFO_EXTENSION)) : "";
        $nomPreuve3 = !empty($preuve3["name"]) ? genererNomUnique(pathinfo($preuve3["name"], PATHINFO_EXTENSION)) : "";

        // Upload des images
        move_uploaded_file($image["tmp_name"], $dossierDestination . $nomImage);
        move_uploaded_file($preuve1["tmp_name"], $dossierDestination . $nomPreuve1);
        if (!empty($preuve2["name"])) {
            move_uploaded_file($preuve2["tmp_name"], $dossierDestination . $nomPreuve2);
        }
        if (!empty($preuve3["name"])) {
            move_uploaded_file($preuve3["tmp_name"], $dossierDestination . $nomPreuve3);
        }

        // Connexion à la base de données et insertion des données
        $servername = "localhost";
        $username = "root";
        $password = "667";
        $dbname = "aftp";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }
        $ip = $_SERVER['REMOTE_ADDR'];

        $sql = "INSERT INTO posts (photo, preuve1, preuve2, preuve3, nom, prenom, date_naissance, ville, adresse, numero, reseaux_sociaux, pseudo, infos, ip)
                VALUES ('$nomImage', '$nomPreuve1', '$nomPreuve2', '$nomPreuve3', '$nom', '$prenom', '$dateNaissance', '$ville', '$adresse', '$numero', '$reseauxSociaux', '$pseudo', '$infos', '$ip')";

        if ($conn->query($sql) === true) {
            echo "Les données ont été enregistrées avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement des données : " . $conn->error;
        }

        $conn->close();
    }
}
?>
