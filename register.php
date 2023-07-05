<?php
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
    $motdepasse = mysqli_real_escape_string($connexion, $motdepasse);
    
    // Créer la requête d'insertion
    $requete = "INSERT INTO utilisateurs (nom, email, motdepasse) VALUES ('$nom', '$email', '$motdepasse')";
    
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
    <title>Formulaire d'inscription</title>
</head>
<body>
    <?php if (isset($message)) : ?>
        <h3><?php echo $message; ?></h3>
    <?php endif; ?>
    
    <?php if (isset($erreur)) : ?>
        <h3><?php echo $erreur; ?></h3>
    <?php endif; ?>
    
    <h2>Inscription</h2>
    <form method="post">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br><br>
        
        <label for="email">Email :</label>
        <input type="email" name="email" required><br><br>
        
        <label for="motdepasse">Mot de passe :</label>
        <input type="password" name="motdepasse" required><br><br>
        
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>
