<?php
// Vérification des données de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier les informations de connexion ici
    // ...

    // Rediriger l'utilisateur vers une page de succès ou d'erreur
    header("Location: login_success.php");
    exit;
}
?>
