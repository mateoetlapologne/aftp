<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $age = ($_POST['age']);
    $ville = $_POST["ville"];
    $adresse = $_POST["adresse"];
    $numero = $_POST["numero"];
    $pseudo = $_POST["pseudo"];
    $infos = $_POST["infos"];

    // Vérification des extensions des fichiers uploadés
    $extensionsImages = array("jpg", "jpeg", "png");

    $erreur = false;

    // Vérification des extensions des images
    $imageExtension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    if (!in_array($imageExtension, $extensionsImages)) {
        $erreur = true;
        echo "L'extension de l'image n'est pas valide. Les extensions autorisées sont : " . implode(", ", $extensionsImages);
    }

    // Vérification des extensions des preuves
    $extensionsPreuves = array();
    foreach ($_FILES["preuve"]["tmp_name"] as $key => $tmp_name) {
        $extension = strtolower(pathinfo($_FILES["preuve"]["name"][$key], PATHINFO_EXTENSION));
        if (!empty($_FILES["preuve"]["name"][$key]) && !in_array($extension, $extensionsImages)) {
            $erreur = true;
            echo "L'extension de la preuve " . ($key + 1) . " n'est pas valide. Les extensions autorisées sont : " . implode(", ", $extensionsImages);
        }
        $extensionsPreuves[$key] = $extension;
    }

    // Vérification des champs de texte
    if (!empty($nom) && !preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $nom)) {
        $erreur = true;
        echo "Le nom ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (empty($age)) {
        $age = 999;
    }

    if(empty($nomPhotoVictime)) {
        $nomPhotoVictime = "default.jpg";
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $prenom)) {
        $erreur = true;
        echo "Le prénom ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!empty($ville) && !preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $ville)) {
        $erreur = true;
        echo "La ville ne doit contenir que des lettres, des espaces et des tirets.";
    }

    if (!empty($numero) && !preg_match("/^\d{10}$/", $numero)) {
        $erreur = true;
        echo "Le numéro de téléphone doit comporter 10 chiffres.";
    }
    

    if (!preg_match("/^[a-zA-Z0-9À-ÿ\s-]+$/", $pseudo)) {
        $erreur = true;
        echo "Le pseudo ne doit contenir que des lettres, des chiffres, des espaces et des tirets.";
    }

    if (strlen($infos) > 350) {
        $erreur = true;
        echo "Les informations ne doivent pas dépasser 350 caractères.";
    }

    // Si aucune erreur, enregistrement des données dans la base de données et upload des images
    if (!$erreur) {
        // Connexion à la base de données
        $connexion = mysqli_connect("localhost", "root", "Pologne667", "aftp");

        // Vérification de la connexion
        if (!$connexion) {
            die("Erreur de connexion à la base de données : " . mysqli_connect_error());
        }

        // Génération d'un nom unique pour l'image recadrée
        $nomPhotoVictime = uniqid() . ".png"; // ou ".jpg" ou ".jpeg" selon votre besoin
        
        // Récupérer les données de l'image recadrée
        $croppedImageData = $_POST['cropped_image'];
        
        // Supprimer l'en-tête de l'encodage base64
        $croppedImageData = str_replace('data:image/jpeg;base64,', '', $croppedImageData);
        $croppedImageData = str_replace(' ', '+', $croppedImageData);
        
        // Décoder les données base64
        $decodedData = base64_decode($croppedImageData);
        
        // Créer une image à partir des données décodées
        $image = imagecreatefromstring($decodedData);
        
        // Chemin de destination pour l'enregistrement de l'image recadrée
        $dossierDestination = 'image/';
        $imagePath = $dossierDestination . $nomPhotoVictime;
        
        // Enregistrer l'image recadrée au format PNG
        if (imagepng($image, $imagePath)) {
            echo "L'image recadrée a été enregistrée avec succès au format PNG.";
            // Vous pouvez maintenant utiliser le fichier $imagePath qui contient l'image au format PNG
        } else {
            echo "Erreur lors de l'enregistrement de l'image recadrée.";
        }
        
        // Libérer la mémoire utilisée par l'image
        imagedestroy($image);
        


        // Génération de noms uniques pour les preuves et enregistrement dans la base de données
        $preuveNoms = array();
        foreach ($_FILES["preuve"]["tmp_name"] as $key => $tmp_name) {
            if (!empty($_FILES["preuve"]["name"][$key])) {
                $preuveNom = uniqid() . "." . $extensionsPreuves[$key];
                move_uploaded_file($tmp_name, $dossierDestination . $preuveNom);
                $preuveNoms[] = $preuveNom;
            }
        }

        // Requête SQL pour insérer les données dans la base de données
        $requete = "INSERT INTO utilisateurs (photoVictime, preuve1, preuve2, preuve3, nom, prenom, age, ville, adresse, numero, pseudo, infos, ip)
            VALUES ('$nomPhotoVictime', '$preuveNoms[0]', '$preuveNoms[1]', '$preuveNoms[2]', '$nom', '$prenom', '$age', '$ville', '$adresse', '$numero', '$pseudo', '$infos', '" . $_SERVER["REMOTE_ADDR"] . "')";

        // Exécution de la requête
        if (mysqli_query($connexion, $requete)) {
            echo "Le post a été enregistré avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement du post : " . mysqli_error($connexion);
        }

        // Fermeture de la connexion à la base de données
        mysqli_close($connexion);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Affiche ton pedo</title>
    <link rel="stylesheet" type="text/css" href="css/post.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
</head>
<body>
<div class="navbar">
    <div class="left-button">
        <a href="#" class="nav-title">Affiche Ton Pedo</a>
    </div>
    <div class="right-buttons">
        <a href="index.php"><button class="rounded-button">Menu</button></a>
        <a href="recherche.php"><button class="rounded-button">Rechercher</button></a>
    </div>
</div>
    <h1>Fais peté la data man</h1>
    <h2>Si tu n'as pas une infos, laisse la case vide</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="image">Photo de la tete du pedo*</label>
        <input type="file" name="image" id="photopedo" required>
        <button id="crop-button" style="display: none;" class="recadrer">Recadrer</button>
        <div id="cropped-image-container"></div>
        <script>
            const inputElement = document.getElementById('photopedo');
const cropButton = document.getElementById('crop-button');
const croppedImageContainer = document.getElementById('cropped-image-container');

let cropper;

inputElement.addEventListener('change', (event) => {
  const file = event.target.files[0];
  const reader = new FileReader();

  reader.onload = function (e) {
    const image = new Image();

    image.onload = function () {
      if (cropper) {
        cropper.destroy();
      }

      croppedImageContainer.innerHTML = '';

      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');
      const maxWidth = 500;
      const maxHeight = 281;
      let width = image.width;
      let height = image.height;

      if (width > maxWidth) {
        height *= maxWidth / width;
        width = maxWidth;
      }

      if (height > maxHeight) {
        width *= maxHeight / height;
        height = maxHeight;
      }

      canvas.width = width;
      canvas.height = height;

      context.drawImage(image, 0, 0, width, height);

      croppedImageContainer.appendChild(canvas);

      cropper = new Cropper(canvas, {
        aspectRatio: 500 / 281,
        viewMode: 2,
      });

      cropButton.style.display = 'block';
    };

    image.src = e.target.result;
  };

  reader.readAsDataURL(file);
});

cropButton.addEventListener('click', () => {
  const canvas = cropper.getCroppedCanvas({
    width: 500,
    height: 281,
  });

  croppedImageContainer.innerHTML = '';
  croppedImageContainer.appendChild(canvas);
});
        </script>
        <br><br>
        <div id="cropped-image-container"></div>

        <label for="preuve">Preuve(s)*</label>
        <input type="file" name="preuve[]" multiple required>
        <br><br>
       
        <label for="nom">Nom</label>
        <input type="text" name="nom">
        <br><br>

        <label for="prenom">Prénom*</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="date_naissance">Age</label>
        <input type="number" id="age" name="age" min="18" max="100">
        <br><br>

        <label for="ville">Ville</label>
        <input type="text" name="ville">
        <br><br>

        <label for="adresse">Adresse</label>
        <input type="text" name="adresse">
        <br><br>

        <label for="numero">Numéro de téléphone du pedo</label>
        <input type="text" name="numero" >
        <br><br>

        <label for="pseudo">Pseudo :*</label>
        <input type="text" name="pseudo" required>
        <br><br>

        <label for="infos">Informations :</label>
        <textarea name="infos"></textarea>
        <br><br>

        <input type="submit" value="Enregistrer le Post">
        <h2>Les champs marqués d'un * sont obligatoires.</h2>
    </form>
</body>
</html>