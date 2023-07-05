<?php
$servername = "localhost"; // Remplacez par le nom de votre serveur MySQL
$username = "nom_utilisateur"; // Remplacez par votre nom d'utilisateur MySQL
$password = "mot_de_passe"; // Remplacez par votre mot de passe MySQL
$dbname = "nom_base_de_donnees"; // Remplacez par le nom de votre base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_utilisateur = $_POST['username'];
    $mot_de_passe = $_POST['password'];

    // Hasher le mot de passe
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Requête SQL d'insertion
    $sql = "INSERT INTO users (nom_utilisateur, mot_de_passe) VALUES ('$nom_utilisateur', '$mot_de_passe_hash')";

    if ($conn->query($sql) === TRUE) {
        echo "Inscription réussie.";
    } else {
        echo "Erreur lors de l'inscription : " . $conn->error;
    }

    // Fermer la connexion
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
</head>
<link rel="stylesheet" type="text/css" href="css/inscription.css">
<body>

<div class="navbar">
    <div class="left-button">
        <a href="#" class="nav-title">Affiche Ton Pédo</a>
    </div>
    <div class="right-buttons">
        <a href="connexion.html"><button class="rounded-button">Poster</button></a>
        <a href="connexion.html"><button class="rounded-button">Connexion</button></a>
    </div>
</div>

    <h2>Registration</h2>
    <form method="post" action="register.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
