<!DOCTYPE html>
<html>
<head>
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
<div class="navbar">
    <div class="left-button">
        <a href="#" class="nav-title">Affiche Ton Pedo</a>
    </div>
    <div class="right-buttons">
        <a href="post.php"><button class="rounded-button">Poster</button></a>
    </div>
</div>
    <h1>Formulaire d'inscription</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="image">Image :</label>
        <input type="file" name="image" id="image" required><br>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
        <span class="error"><?php echo $nomErr; ?></span><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required>
        <span class="error"><?php echo $prenomErr; ?></span><br>

        <label for="dateNaissance">Date de naissance :</label>
        <input type="text" name="dateNaissance" id="dateNaissance" placeholder="jj/mm/aaaa" required>
        <input type="checkbox" name="dateInconnue">Date inconnue
        <span class="error"><?php echo $dateNaissanceErr; ?></span><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" id="adresse" required>
        <span class="error"><?php echo $adresseErr; ?></span><br>

        <label for="numero">Numéro de téléphone :</label>
        <input type="text" name="numero" id="numero" required>
        <span class="error"><?php echo $numeroErr; ?></span><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" maxlength="20" required>
        <span class="error"><?php echo $pseudoErr; ?></span><br>

        <label for="infos">Informations :</label>
        <textarea name="infos" id="infos" maxlength="350" required></textarea>
        <span class="error"><?php echo $infosErr; ?></span><br>

        <input type="submit" value="Soumettre">
    </form>
</body>
</html>

<?php
$imageErr = $nomErr = $prenomErr = $dateNaissanceErr = $adresseErr = $numeroErr = $pseudoErr = $infosErr = "";
$image = $nom = $prenom = $dateNaissance = $adresse = $numero = $pseudo = $infos = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation de l'image
    if (empty($_FILES["image"]["name"])) {
        $imageErr = "Veuillez sélectionner une image";
    } else {
        $image = $_FILES["image"]["name"];
        $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        // Vérifier le type de fichier (uniquement les images sont autorisées)
        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedExtensions)) {
            $imageErr = "Seules les images au format JPG, JPEG, PNG et GIF sont autorisées";
        }

        // Vérifier la taille de l'image (maximum 2 Mo)
        if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $imageErr = "La taille de l'image ne doit pas dépasser 2 Mo";
        }

        // Déplacer l'image vers le dossier de destination
        move_uploaded_file($_FILES["image"]["tmp_name"], "chemin/vers/dossier/" . $image);
    }

    // Validation du nom
    if (empty($_POST["nom"])) {
        $nomErr = "Veuillez saisir votre nom";
    } else {
        $nom = test_input($_POST["nom"]);
        // Vérifier si le nom ne contient que des lettres et des espaces
        if (!preg_match("/^[a-zA-Z ]*$/", $nom)) {
            $nomErr = "Seules les lettres et les espaces sont autorisés";
        }
    }

    // Validation du prénom
    if (empty($_POST["prenom"])) {
        $prenomErr = "Veuillez saisir votre prénom";
    } else {
        $prenom = test_input($_POST["prenom"]);
        // Vérifier si le prénom ne contient que des lettres et des espaces
        if (!preg_match("/^[a-zA-Z ]*$/", $prenom)) {
            $prenomErr = "Seules les lettres et les espaces sont autorisés";
        }
    }

    // Validation de la date de naissance
    if (empty($_POST["dateNaissance"]) && !isset($_POST["dateInconnue"])) {
        $dateNaissanceErr = "Veuillez saisir votre date de naissance ou cocher 'Date inconnue'";
    } else {
        $dateNaissance = test_input($_POST["dateNaissance"]);
        // Vérifier si la date de naissance est au format valide (jj/mm/aaaa)
        if (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $dateNaissance)) {
            $dateNaissanceErr = "Le format de la date de naissance doit être jj/mm/aaaa";
        }
    }

    // Validation de l'adresse
    if (empty($_POST["adresse"])) {
        $adresseErr = "Veuillez saisir votre adresse";
    } else {
        $adresse = test_input($_POST["adresse"]);
    }

    // Validation du numéro de téléphone
    if (empty($_POST["numero"])) {
        $numeroErr = "Veuillez saisir votre numéro de téléphone";
    } else {
        $numero = test_input($_POST["numero"]);
        // Vérifier si le numéro de téléphone ne contient que des chiffres et a une longueur de 10
        if (!preg_match("/^[0-9]{10}$/", $numero)) {
            $numeroErr = "Le numéro de téléphone doit contenir 10 chiffres";
        }
    }

    // Validation du pseudo
    if (empty($_POST["pseudo"])) {
        $pseudoErr = "Veuillez saisir votre pseudo";
    } else {
        $pseudo = test_input($_POST["pseudo"]);
        // Vérifier si le pseudo ne dépasse pas 20 caractères
        if (strlen($pseudo) > 20) {
            $pseudoErr = "Le pseudo ne doit pas dépasser 20 caractères";
        }
    }

    // Validation des informations
    if (empty($_POST["infos"])) {
        $infosErr = "Veuillez saisir les informations";
    } else {
        $infos = test_input($_POST["infos"]);
        // Vérifier si les informations ne dépassent pas 350 caractères
        if (strlen($infos) > 350) {
            $infosErr = "Les informations ne doivent pas dépasser 350 caractères";
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
