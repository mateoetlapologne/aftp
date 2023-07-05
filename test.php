<!DOCTYPE html>
<html>
<head>
    <title>Formulaire d'upload de fichiers</title>
</head>
<body>
    <h2>Formulaire d'upload de fichiers</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <input type="file" name="fichier" />
        <input type="submit" value="Télécharger" />
    </form>

    <?php
    if(isset($_FILES['fichier'])){
        $fichier = $_FILES['fichier'];
        $nomFichier = $fichier['name'];
        $emplacementTemporaire = $fichier['tmp_name'];
        $destination = "images/" . $nomFichier;
        
        // Déplace le fichier vers le dossier images
        if(move_uploaded_file($emplacementTemporaire, $destination)){
            echo "Le fichier a été téléchargé avec succès.";
        } else {
            echo "Une erreur s'est produite lors du téléchargement du fichier.";
        }
    }
    ?>
</body>
</html>
