<?php
// Traitement des données d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Enregistrer les informations d'inscription ici
    // ...

    // Rediriger l'utilisateur vers une page de succès ou d'erreur
    header("Location: register_success.php");
    exit;
}
?>
<?php
// Récupération des données du formulaire
$nom = $_POST['nom'];
$email = $_POST['email'];

// Connexion à la base de données
$servername = "localhost";
$username = "nom_utilisateur";
$password = "mot_de_passe";
$dbname = "nom_base_de_données";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insertion des données dans la base de données
$sql = "INSERT INTO table_utilisateurs (nom, email) VALUES ('$nom', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "Données enregistrées avec succès";
} else {
    echo "Erreur lors de l'enregistrement des données: " . $conn->error;
}

// Fermeture de la connexion à la base de données
$conn->close();
?>