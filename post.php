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
        <input type="text" name="dateNaissance" id="dateNaissance" placeholder="jj/mm/aaaa" >
        <input type="checkbox" name="dateInconnue">Date inconnue
        <span class="error"><?php echo $dateNaissanceErr; ?></span><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" id="adresse" required>
        <span class="error"><?php echo $adresseErr; ?></span><br>

        <label for="adresse">Ville</label>
        <input type="text" name="ville" id="ville" required>
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
// Connexion à la base de données
$servername = "localhost";
$username = "nom_utilisateur";
$password = "mot_de_passe";
$dbname = "nom_base_de_donnees";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Traitement du formulaire lorsque celui-ci est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs du formulaire
    $image = $_FILES["image"]["name"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $dateNaissance = $_POST["dateNaissance"];
    $adresse = $_POST["adresse"];
    $ville = $_POST["ville"];
    $numero = $_POST["numero"];
    $pseudo = $_POST["pseudo"];
    $infos = $_POST["infos"];
    $ip = $_SERVER["REMOTE_ADDR"];
    // Insertion des données dans la table
    $sql = "INSERT INTO utilisateurs (image, nom, prenom, date_naissance, adresse, ville, numero, pseudo, infos, ip)
            VALUES ('$image', '$nom', '$prenom', '$dateNaissance', '$adresse', '$ville', '$numero', '$pseudo', '$infos', '$ip')";

    if ($conn->query($sql) === TRUE) {
        echo "Enregistrement réussi !";
    } else {
        echo "Erreur lors de l'enregistrement : " . $conn->error;
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>
