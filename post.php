<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de Post</title>
</head>
<body>
    <h1>Formulaire de Post</h1>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérification des champs et traitement des données
        $erreur = false;

        // Vérification de l'image de la victime
        if (isset($_FILES["image"])) {
            $image = $_FILES["image"];
            $extensionImage = strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));

            if (!in_array($extensionImage, ["jpg", "jpeg", "png", "gif"])) {
                $erreur = true;
                echo "L'image de la victime doit être au format JPG, JPEG, PNG ou GIF.";
            }
        } else {
            $erreur = true;
            echo "Veuillez sélectionner une image de la victime.";
        }

        // Vérification des preuves
        $preuves = [];
        $extensionsPreuves = [];

        for ($i = 1; $i <= 3; $i++) {
            $nomChampPreuve = "preuve" . $i;
            if (isset($_FILES[$nomChampPreuve])) {
                $preuve = $_FILES[$nomChampPreuve];
                $extensionPreuve = strtolower(pathinfo($preuve["name"], PATHINFO_EXTENSION));

                if (in_array($extensionPreuve, ["jpg", "jpeg", "png", "gif"])) {
                    $preuves[$nomChampPreuve] = $preuve;
                    $extensionsPreuves[$nomChampPreuve] = $extensionPreuve;
                }
            }
        }

        // Vérification des autres champs
        $nom = $_POST["nom"] ?? "";
        $prenom = $_POST["prenom"] ?? "";
        $dateNaissance = $_POST["date_naissance"] ?? "";
        $ville = $_POST["ville"] ?? "";
        $adresse = $_POST["adresse"] ?? "";
        $numero = $_POST["numero"] ?? "";
        $reseauxSociaux = $_POST["reseaux_sociaux"] ?? "";
        $pseudo = $_POST["pseudo"] ?? "";
        $infos = $_POST["infos"] ?? "";

        // Validation des champs
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $nom)) {
            $erreur = true;
            echo "Le nom ne doit contenir que des lettres, des espaces et des tirets.";
        }

        if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $prenom)) {
            $erreur = true;
            echo "Le prénom ne doit contenir que des lettres, des espaces et des tirets.";
        }

        if (!empty($dateNaissance) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $dateNaissance)) {
            $erreur = true;
            echo "La date de naissance doit être au format 'AAAA-MM-JJ'.";
        }

        if (!preg_match("/^[a-zA-ZÀ-ÿ\s-]+$/", $ville)) {
            $erreur = true;
            echo "La ville ne doit contenir que des lettres, des espaces et des tirets.";
        }

        if (!preg_match("/^[a-zA-Z0-9À-ÿ\s-]+$/", $adresse)) {
            $erreur = true;
            echo "L'adresse doit contenir que des lettres, des chiffres, des espaces et des tirets.";
        }

        if (!preg_match("/^\d{10}$/", $numero)) {
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

        // Si aucune erreur, enregistrement des données dans la base de données
        if (!$erreur) {
            // Connexion à la base de données (remplacez les valeurs par vos propres paramètres)
            $serveur = "localhost";
            $utilisateur = "root";
            $motDePasse = "667";
            $nomBDD = "aftp";

            $connexion = mysqli_connect($serveur, $utilisateur, $motDePasse, $nomBDD);

            // Vérification de la connexion à la base de données
            if (!$connexion) {
                die("Erreur de connexion à la base de données : " . mysqli_connect_error());
            }

            // Préparation des données pour l'insertion dans la base de données
            $imageNom = uniqid() . "." . $extensionImage;
            $imageChemin = "images/" . $imageNom;

            $preuve1Nom = uniqid() . "." . $extensionsPreuves["preuve1"];
            $preuve1Chemin = "images/" . $preuve1Nom;

            $preuve2Nom = "";
            $preuve2Chemin = "";
            if (isset($preuves["preuve2"])) {
                $preuve2Nom = uniqid() . "." . $extensionsPreuves["preuve2"];
                $preuve2Chemin = "images/" . $preuve2Nom;
            }

            $preuve3Nom = "";
            $preuve3Chemin = "";
            if (isset($preuves["preuve3"])) {
                $preuve3Nom = uniqid() . "." . $extensionsPreuves["preuve3"];
                $preuve3Chemin = "images/" . $preuve3Nom;
            }

            // Déplacement des fichiers téléchargés vers le dossier de destination
            move_uploaded_file($image["tmp_name"], $imageChemin);
            move_uploaded_file($preuves["preuve1"]["tmp_name"], $preuve1Chemin);
            if (!empty($preuves["preuve2"])) {
                move_uploaded_file($preuves["preuve2"]["tmp_name"], $preuve2Chemin);
            }
            if (!empty($preuves["preuve3"])) {
                move_uploaded_file($preuves["preuve3"]["tmp_name"], $preuve3Chemin);
            }
            $ip = $_SERVER["REMOTE_ADDR"];
            // Requête SQL pour insérer les données dans la base de données
            $requete = "INSERT INTO utilisateurs (photoVictime, preuve1, preuve2, preuve3, nom, prenom, date_naissance, ville, adresse, numero, pseudo, infos, ip) VALUES ('$imageNom', '$preuve1Nom', '$preuve2Nom', '$preuve3Nom', '$nom', '$prenom', '$dateNaissance', '$ville', '$adresse', '$numero', '$pseudo', '$infos', '$ip')";

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
    <form method="POST" enctype="multipart/form-data">
        <label for="image">Image de la victime :</label>
        <input type="file" name="image" required>
        <br><br>

        <label for="preuve1">Preuve 1 :</label>
        <input type="file" name="preuve1" required>
        <br><br>

        <label for="preuve2">Preuve 2 :</label>
        <input type="file" name="preuve2">
        <br><br>

        <label for="preuve3">Preuve 3 :</label>
        <input type="file" name="preuve3">
        <br><br>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="date_naissance">Date de naissance :</label>
        <input type="date" name="date_naissance">
        <input type="checkbox" name="date_naissance_inconnue" value="1"> Date de naissance inconnue
        <br><br>

        <label for="ville">Ville :</label>
        <input type="text" name="ville" required>
        <br><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
        <br><br>

        <label for="numero">Numéro de téléphone :</label>
        <input type="text" name="numero" required>
        <br><br>

        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required>
        <br><br>

        <label for="infos">Informations :</label>
        <textarea name="infos" rows="5" cols="40"></textarea>
        <br><br>

        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
