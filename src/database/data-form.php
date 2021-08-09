<?php
///////////////////////////
// Données de formulaire //
///////////////////////////

// Appel du script de validation
require __DIR__ . '/validation.php';
// Appel du script des requêtes
require __DIR__ . '/datamanager.php';

// Définition de l'action choisie
if ($_GET['action'] == 'copy' or $_GET['action'] == 'edit') {
  if (isset($_GET['id'])) {
    $id = valid_data($_GET['id']);
    // Appel de la fonction de sélection d'un média type film
    $result = select_movie($id);
  }
}
if (isset($_GET['action'])) {
  $action = valid_data($_GET['action']);
}

// Traitement du formulaire
if (!empty($_POST["save_record"])) {

  $fields_required = array($_POST['title'], $_POST['synopsis'], $_POST['premiered']);
  $set_picture = $set_request = FALSE;

  // Vérification que les champs d'entrée ne sont pas vides
  if (in_array('', $fields_required)) :
    $msg = "Veuillez remplir les champs obligatoires (*).";
  else :
    // Déclaration et nettoyage des variables d'entrée
    $title = valid_data(mb_ucfirst($_POST['title']));
    $synopsis = valid_data($_POST['synopsis']);
    if (empty($_POST['catch'])) {
      $catch = "À découvrir...";
    } else {
      $catch = valid_data($_POST['catch']);
    }
    $premiered = valid_data($_POST['premiered']);
    $set_request = TRUE;

    // Traitement de l'image
    if (isset($_FILES["picture"]) && !empty($_FILES["picture"]["name"])) {
      $picture = $_FILES['picture'];

      // Définition des extensions de fichier d'image autorisées
      $ext = array('png', 'jpg', 'jpeg', 'gif');

      // Gestion des erreurs de la super globale $_FILES => https://www.php.net/manual/fr/features.file-upload.errors.php
      // Erreur 1 : La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini
      // Erreur 2 : La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire
      if ($picture['error'] > 0 && $picture['error'] < 3) :
        $msg = "Fichier trop volumineux (1Mo max).";
      // Erreur 3 : Le fichier n'a été que partiellement téléversé
      // Erreur 6 : Un dossier temporaire est manquant
      // Erreur 7 : Échec de l'écriture du fichier sur le disque
      // Erreur 8 : Une extension PHP a arrêté l'envoi de fichier
      elseif ($picture['error'] == 3 || $picture['error'] > 4) :
        $msg = "Un problème est survenu pendant le téléversement.";
      else :
        // Erreur 4 : Aucun fichier n'a été téléversé
        if ($picture['error'] == 4) :
          $picture_name = 'placeholders/generic_poster.jpg';
          $set_picture = TRUE;
        else :
          // Re-vérification de la taille de l'image côté serveur
          if ($picture['size'] > 1048576) {
            $msg = "Fichier trop volumineux (1Mo max)."; // Poids maxi : 1Mo => 1024*1024
          }
          // Vérification que l'extension est bien une image
          elseif (!in_array(strtolower(pathinfo($picture['name'], PATHINFO_EXTENSION)), $ext)) {
            $msg = "Le fichier n'est pas une image (Extensions autorisées : '.png', '.jpg', '.jpeg', '.gif').";
          }
          // Récupération et stockage du fichier image sur le serveur
          else {
            // Nouveau nom à l'image pour éviter les doublons
            $picture_name = uniqid() . '_' . $picture['name'];

            // Placement de l'image dans le dossier et droits de lecture/écriture
            $img_folder = dirname(__DIR__) . '/thumbnails/g/';
            @mkdir($img_folder, 0777);
            $dir = $img_folder . $picture_name;
            $move_file = @move_uploaded_file($picture['tmp_name'], $dir);

            // Nom du chemin de l'image pour la base de données
            $picture_name = 'g/' . $picture_name;

            if (!$move_file) {
              $msg = "Un problème est survenu pendant le téléversement, merci de renouveler votre envoi.";
            } else {
              $set_picture = TRUE;
            }
          }
        endif;
      endif;
    }
  endif;

  // Création de l'URL pour la redirection
  $referer = $_SERVER['HTTP_REFERER'];
  if (strpos($referer, '?') !== FALSE) {
    $req_get = strrchr($referer, '?');
    $referer = str_replace($req_get, '', $referer);
  }
  if (isset($msg)) {
    if ($action == 'add') {
      header("Location: $referer?action=$action&msg=$msg&error=true");
    } elseif ($action == 'copy' or $action == 'edit') {
      header("Location: $referer?id=$id&action=$action&msg=$msg&error=true");
    }
  } elseif ($set_request) {
    // Tableau pour les entrées du formulaire
    $datas = array(
      'title' => $title,
      'synopsis' => $synopsis,
      'catch' => $catch,
      'premiered' => $premiered,
      'picture' => $picture_name
    );

    // Définition de la fonction de requête selon l'action choisie
    if ($_GET['action'] == 'add' or $_GET['action'] == 'copy') {
      // Appel de la fonction d'ajout d'un média type film
      $exec = add_movie($datas);
    } elseif ($_GET['action'] == 'edit') {
      // Appel de la fonction de mise à jour d'un média type film
      $exec = update_movie($datas, $id, $set_picture);
    }

    // Définition du message d'erreur ou de succès
    if ($exec) {
      if ($_GET['action'] == 'add' or $_GET['action'] == 'copy') {
        $msg = "Le média a été ajouté avec succès.";
      } elseif ($_GET['action'] == 'edit') {
        $msg = "Le média a été modifié avec succès.";
      }
      $error = 'false';
    } else {
      $msg = "Oups, une erreur s'est produite.";
      $error = 'true';
    }
    // Redirection
    if ($action == 'add') {
      header("Location: $referer?action=$action&msg=$msg&error=$error");
    } elseif ($action == 'copy' or $action == 'edit') {
      header("Location: $referer?id=$id&action=$action&msg=$msg&error=$error");
    }
  }
}
