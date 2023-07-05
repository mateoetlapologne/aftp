<?php
// Traitement des données d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $passwd = $_POST['password'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $pp = "/pp/default.png";

    // Enregistrer les informations d'inscription ici
    // ...

    // Rediriger l'utilisateur vers une page de succès ou d'erreur
    header("Location: register_success.php");
    exit;
}
?>
<?php
// Récupération des données du formulaire
$username = $_POST['username'];
$passwd = $_POST['password'];
$ip = $_SERVER['REMOTE_ADDR'];
$pp = "/pp/default.png";

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "667";
$dbname = "aftp";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insertion des données dans la base de données
$sql = "INSERT INTO aftp (username, passwd, ip, pp) VALUES ('$username', '$passwd', '$ip', '$pp')";

if ($conn->query($sql) === TRUE) {
    echo "Données enregistrées avec succès";
} else {
    echo "Erreur lors de l'enregistrement des données: " . $conn->error;
}

// Fermeture de la connexion à la base de données
$conn->close();
?>