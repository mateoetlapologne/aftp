<?php
// Fonction de hachage de mot de passe avec un salt aléatoire
function hashMotDePasse($motdepasse) {
    $salt = generateSalt(); // Génère un salt aléatoire
    $hashed_password = sha1($motdepasse . $salt); // Applique le hachage avec le salt
    return array("motdepasse" => $hashed_password, "salt" => $salt);
}

// Fonction pour générer un salt aléatoire
function generateSalt($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $salt = '';
    for ($i = 0; $i < $length; $i++) {
        $salt .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $salt;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $nom = $_POST["nom"];
    $email = $_POST["email"];
    $motdepasse = $_POST["motdepasse"];
    
    // Connexion à la base de données
    $serveur = "localhost"; // Remplacez par le nom de votre serveur MySQL
    $utilisateur = "root"; // Remplacez par votre nom d'utilisateur MySQL
    $mdp = "667"; // Remplacez par votre mot de passe MySQL
    $bdd = "aftp"; // Remplacez par le nom de votre base de données
    
    $connexion = mysqli_connect($serveur, $utilisateur, $mdp, $bdd);
    
    // Vérifier la connexion
    if (!$connexion) {
        die("La connexion à la base de données a échoué: " . mysqli_connect_error());
    }
    
    // Échapper les caractères spéciaux pour éviter les injections SQL
    $nom = mysqli_real_escape_string($connexion, $nom);
    $email = mysqli_real_escape_string($connexion, $email);
    
    // Hasher le mot de passe
    $hashed_data = hashMotDePasse($motdepasse);
    $motdepasse_hash = $hashed_data["motdepasse"];
    $salt = $hashed_data["salt"];
    
    // Créer la requête d'insertion
    $requete = "INSERT INTO utilisateurs (pseudo, motdepasse, salt) VALUES ('$nom', '$motdepasse_hash', '$salt')";
    
    // Exécuter la requête d'insertion
    if (mysqli_query($connexion, $requete)) {
        $message = "Inscription réussie !";
    } else {
        $erreur = "Erreur lors de l'inscription : " . mysqli_error($connexion);
    }
    
    // Fermer la connexion à la base de données
    mysqli_close($connexion);
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
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br><br>        
        <label for="motdepasse">Mot de passe :</label>
        <input type="password" name="motdepasse" required><br><br>
        
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
