<!DOCTYPE html>
<html>
<head>
    <title>Formulaire</title>
</head>
<body>
    <?php

    // Paramètres de connexion à la base de données
$servername = "localhost"; // Nom du serveur (par défaut : localhost)
$username = "root"; // Nom d'utilisateur MySQL
$password = "667"; // Mot de passe MySQL
$dbname = "aftp"; // Nom de la base de données

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

    // Définir les variables et les messages d'erreur
    $preuve1Err = $preuve2Err = $preuve3Err = $nomErr = $prenomErr = $dateNaissanceErr = $adresseErr = $numeroErr = $pseudoErr = $infosErr = "";
    $preuve1 = $preuve2 = $preuve3 = $photoVictime = $nom = $prenom = $dateNaissance = $adresse = $numero = $pseudo = $infos = "";

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validation de la preuve 1
        if (empty($_FILES["preuve1"]["name"])) {
            $preuve1Err = "Veuillez sélectionner une image";
        } else {
            $preuve1FileType = strtolower(pathinfo($_FILES["preuve1"]["name"], PATHINFO_EXTENSION));
            $preuve1 = uniqid() . "." . $preuve1FileType;

            // Vérifier si le fichier est une image
            $check = getimagesize($_FILES["preuve1"]["tmp_name"]);
            if ($check === false) {
                $preuve1Err = "Le fichier n'est pas une image";
            }

            // Vérifier la taille de l'image (maximum 2 Mo)
            if ($_FILES["preuve1"]["size"] > 5 * 1024 * 1024) {
                $preuve1Err = "La taille de l'image ne doit pas dépasser 5 Mo";
            }

            // Déplacer l'image vers le dossier de destination
            move_uploaded_file($_FILES["preuve1"]["tmp_name"], "image/" . $preuve1);
        }

        // Validation de la preuve 2
        if (!empty($_FILES["preuve2"]["name"])) {
            $preuve2FileType = strtolower(pathinfo($_FILES["preuve2"]["name"], PATHINFO_EXTENSION));
            $preuve2 = uniqid() . "." . $preuve2FileType;

            // Vérifier si le fichier est une image
            $check = getimagesize($_FILES["preuve2"]["tmp_name"]);
            if ($check === false) {
                $preuve2Err = "Le fichier n'est pas une image";
            }

            // Vérifier la taille de l'image (maximum 2 Mo)
            if ($_FILES["preuve2"]["size"] > 5 * 1024 * 1024) {
                $preuve2Err = "La taille de l'image ne doit pas dépasser 5 Mo";
            }

            // Déplacer l'image vers le dossier de destination
            move_uploaded_file($_FILES["preuve2"]["tmp_name"], "image/" . $preuve2);
        } else {
            $preuve2 = "";
        }

        // Validation de la preuve 3
        if (!empty($_FILES["preuve3"]["name"])) {
            $preuve3FileType = strtolower(pathinfo($_FILES["preuve3"]["name"], PATHINFO_EXTENSION));
            $preuve3 = uniqid() . "." . $preuve3FileType;

            // Vérifier si le fichier est une image
            $check = getimagesize($_FILES["preuve3"]["tmp_name"]);
            if ($check === false) {
                $preuve3Err = "Le fichier n'est pas une image";
            }

            // Vérifier la taille de l'image (maximum 2 Mo)
            if ($_FILES["preuve3"]["size"] > 5 * 1024 * 1024) {
                $preuve3Err = "La taille de l'image ne doit pas dépasser 5 Mo";
            }

            // Déplacer l'image vers le dossier de destination
            move_uploaded_file($_FILES["preuve3"]["tmp_name"], "image/" . $preuve3);
        } else {
            $preuve3 = "";
        }

        // Validation de la photo de la victime
        if (empty($_FILES["photoVictime"]["name"])) {
            $photoVictime = "";
        } else {
            $photoVictimeFileType = strtolower(pathinfo($_FILES["photoVictime"]["name"], PATHINFO_EXTENSION));
            $photoVictime = uniqid() . "." . $photoVictimeFileType;

            // Vérifier si le fichier est une image
            $check = getimagesize($_FILES["photoVictime"]["tmp_name"]);
            if ($check === false) {
                $photoVictimeErr = "Le fichier n'est pas une image";
            }

            // Vérifier la taille de l'image (maximum 2 Mo)
            if ($_FILES["photoVictime"]["size"] > 5 * 1024 * 1024) {
                $photoVictimeErr = "La taille de l'image ne doit pas dépasser 5 Mo";
            }
            if ($_FILES["photoVictime"]["error"] !== UPLOAD_ERR_OK) {
                echo "Erreur lors de l'upload de l'image de la victime : " . $_FILES["photoVictime"]["error"];
            }

            // Déplacer l'image vers le dossier de destination
            move_uploaded_file($_FILES["photoVictime"]["tmp_name"], "image/" . $photoVictime);
        }

        // Validation des autres champs ...

        // Si toutes les validations sont réussies, insérer les données dans la base de données
        if (empty($preuve1Err) && empty($preuve2Err) && empty($preuve3Err) && empty($photoVictimeErr) && empty($nomErr) && empty($prenomErr) && empty($dateNaissanceErr) && empty($adresseErr) && empty($numeroErr) && empty($pseudoErr) && empty($infosErr)) {
            // Connexion à la base de données
            $conn = new mysqli("localhost", "root", "667", "aftp");

            // Vérifier la connexion
            if ($conn->connect_error) {
                die("Erreur de connexion à la base de données : " . $conn->connect_error);
            }

            // Préparer les données pour l'insertion dans la base de données
            $preuve1 = $conn->real_escape_string($preuve1);
            $preuve2 = $conn->real_escape_string($preuve2);
            $preuve3 = $conn->real_escape_string($preuve3);
            $photoVictime = $conn->real_escape_string($photoVictime);
            $nom = $conn->real_escape_string($_POST["nom"]);
            $prenom = $conn->real_escape_string($_POST["prenom"]);
            $dateNaissance = $conn->real_escape_string($_POST["dateNaissance"]);
            $adresse = $conn->real_escape_string($_POST["adresse"]);
            $numero = $conn->real_escape_string($_POST["numero"]);
            $pseudo = $conn->real_escape_string($_POST["pseudo"]);
            $infos = $conn->real_escape_string($_POST["infos"]);

            // Vérifications supplémentaires
if ($preuve1 != "") {
    // Vérification de l'extension de fichier pour preuve1
    $ext1 = strtolower(pathinfo($_FILES["preuve1"]["name"], PATHINFO_EXTENSION));
    if (!in_array($ext1, $extensionsAutorisees)) {
        $preuve1Err = "Seules les images de type JPG, JPEG, PNG et GIF sont autorisées pour preuve 1";
    }
}

// Vérification des champs nom, prénom, adresse, pseudo et infos
if (!preg_match("/^[a-zA-Z\- ]*$/", $nom)) {
    $nomErr = "Le nom ne peut contenir que des lettres, des espaces et des tirets";
}
if (!preg_match("/^[a-zA-Z\- ]*$/", $prenom)) {
    $prenomErr = "Le prénom ne peut contenir que des lettres, des espaces et des tirets";
}
if (!preg_match("/^[a-zA-Z0-9\- ]*$/", $adresse)) {
    $adresseErr = "L'adresse ne peut contenir que des lettres, des chiffres, des espaces et des tirets";
}
if (!preg_match("/^[a-zA-Z0-9\- ]*$/", $pseudo)) {
    $pseudoErr = "Le pseudo ne peut contenir que des lettres, des chiffres, des espaces et des tirets";
}
if (!preg_match("/^[a-zA-Z0-9\- ]*$/", $infos)) {
    $infosErr = "Les informations ne peuvent contenir que des lettres, des chiffres, des espaces et des tirets";
}


            // Insertion des données dans la table
            $sql = "INSERT INTO utilisateurs (preuve1, preuve2, preuve3, photoVictime, nom, prenom, date_naissance, adresse, ville, numero, pseudo, infos)
                    VALUES ('$preuve1', '$preuve2', '$preuve3', '$photoVictime', '$nom', '$prenom', '$dateNaissance', '$adresse', '$ville', '$numero', '$pseudo', '$infos')";

            if ($conn->query($sql) === true) {
                echo "Enregistrement réussi";
            } else {
                echo "Erreur lors de l'enregistrement : " . $conn->error;
            }

            // Fermer la connexion à la base de données
            $conn->close();
        }
    }
    ?>

    <h2>Formulaire</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" enctype="multipart/form-data">
        <div>
            <label for="photoVictime">Photo de la victime :</label>
            <input type="file" name="photoVictime" id="photoVictime" accept="image/*">
            <span><?php echo $photoVictimeErr; ?></span>
        </div>
        <div>
            <label for="preuve1">Preuve 1 :</label>
            <input type="file" name="preuve1" id="preuve1" accept="image/*" required>
            <span><?php echo $preuve1Err; ?></span>
        </div>
        <div>
            <label for="preuve2">Preuve 2 :</label>
            <input type="file" name="preuve2" id="preuve2" accept="image/*">
            <span><?php echo $preuve2Err; ?></span>
        </div>
        <div>
            <label for="preuve3">Preuve 3 :</label>
            <input type="file" name="preuve3" id="preuve3" accept="image/*">
            <span><?php echo $preuve3Err; ?></span>
        </div>
        <div>
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required>
            <span><?php echo $nomErr; ?></span>
        </div>
        <div>
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required>
            <span><?php echo $prenomErr; ?></span>
        </div>
        <div>
            <label for="dateNaissance">Date de naissance :</label>
            <input type="date" name="dateNaissance" id="dateNaissance" >
            <input type="checkbox" name="dateNaissanceInconnue" id="dateNaissanceInconnue"> Date de naissance inconnue
            <span><?php echo $dateNaissanceErr; ?></span>
        </div>
        <div>
            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" id="adresse" required>
            <span><?php echo $adresseErr; ?></span>
        </div>
        <div>
            <label for="adresse">Ville</label>
            <input type="text" name="ville" id="ville" required>
            <span><?php echo $adresseErr; ?></span>
        </div>
        <div>
            <label for="numero">Numéro :</label>
            <input type="text" name="numero" id="numero" pattern="[0-9]{10}" required>
            <span><?php echo $numeroErr; ?></span>
        </div>
        <div>
            <label for="pseudo">Pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" maxlength="20" required>
            <span><?php echo $pseudoErr; ?></span>
        </div>
        <div>
            <label for="infos">Informations :</label>
            <textarea name="infos" id="infos" maxlength="350" required></textarea>
            <span><?php echo $infosErr; ?></span>
        </div>
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
