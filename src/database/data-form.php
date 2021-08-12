<?php
///////////////////////////
// Données de formulaire //
///////////////////////////

// Appel du script de validation
require __DIR__ . '/validation.php';
// Appel du script d'administration des données
require __DIR__ . '/datamanager.php';
// Appel du script de l'upload
require __DIR__ . '/upload.php';

// Définition des valeurs autorisées dans le GET
$actions = array('add', 'copy', 'edit');
// Vérification du GET ('action' obligatoire)
if (isset($_GET['action']) && !empty($_GET['action']) || in_array($_GET['action'], $actions)) {
  $action = valid_data($_GET['action']);
} else {
  // Redirection si le GET n'est pas vérifié
  header("location:/src/views/crud.php");
  exit;
}
if ($_GET['action'] == 'copy' or $_GET['action'] == 'edit') {
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = valid_data($_GET['id']);
    // Requête de vérification de l'existence de l'ID du média type film
    $checkid = check_id_movie($id);
    if ($checkid === 0) {
      // Redirection
      header("location:/src/views/crud.php");
      exit;
    } else {
      // Appel de la fonction de sélection d'un média type film
      $result = select_movie($id);
    }
  } else {
    // Redirection
    header("location:/src/views/crud.php");
    exit;
  }
}

// Traitement du formulaire
if (!empty($_POST["save_record"])) {
  $set_picture = $set_request = FALSE;

  // Champs requis
  $fields_required = array($_POST['title'], $_POST['synopsis'], $_POST['premiered']);

  // Définition du dossier qui contiendra l'image
  $img_folder = '/src/thumbnails/g/';
  // Définition de l'image par défaut
  $default_picture_name = 'placeholders/generic_poster.jpg';

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
    if (($action == 'add' or $action == 'copy') && empty($_FILES["picture"]["name"])) {
      $picture_name = $default_picture_name;
    } elseif (isset($_FILES["picture"]) && !empty($_FILES["picture"]["name"])) {
      $upload_img = upload_img($_FILES['picture'], $default_picture_name, $img_folder);
      $set_picture = $upload_img[0]; // Autorisation de création de l'image
      $picture_name = 'g/' . $upload_img[1]; // Nom du chemin de l'image pour la base de données
      $msg = $upload_img[2]; // Message d'erreur de l'upload
    }
  endif;

  // Création de l'URL pour la redirection
  $referer = $_SERVER['HTTP_REFERER'];
  if (strpos($referer, '?') !== FALSE) {
    $req_get = strrchr($referer, '?');
    $referer = str_replace($req_get, '', $referer);
  }
  if (isset($msg) && !empty($msg)) {
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
