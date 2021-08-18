<?php
///////////////////////////
// Téléversement d'image //
///////////////////////////

function upload_img($picture, $default_picture_name, string $img_folder)
{
  $set_picture = FALSE;
  $msg_error = "";

  // Définition des extensions de fichier d'image autorisées
  $ext = array('png', 'jpg', 'jpeg', 'gif');

  // Gestion des erreurs de la super globale $_FILES => https://www.php.net/manual/fr/features.file-upload.errors.php
  // Erreur 1 : La taille du fichier téléchargé excède la valeur de upload_max_filesize, configurée dans le php.ini
  // Erreur 2 : La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire
  if ($picture['error'] > 0 && $picture['error'] < 3) :
    $msg_error = "Fichier trop volumineux (1Mo max).";
  // Erreur 3 : Le fichier n'a été que partiellement téléversé
  // Erreur 6 : Un dossier temporaire est manquant
  // Erreur 7 : Échec de l'écriture du fichier sur le disque
  // Erreur 8 : Une extension PHP a arrêté l'envoi de fichier
  elseif ($picture['error'] == 3 || $picture['error'] > 4) :
    $msg_error = "Un problème est survenu pendant le téléversement.";
  else :
    // Erreur 4 : Aucun fichier n'a été téléversé
    if ($picture['error'] == 4) :
      $picture_name = $default_picture_name;
      $set_picture = TRUE;
    else :
      // Re-vérification de la taille de l'image côté serveur
      if ($picture['size'] > 1048576) {
        $msg_error = "Fichier trop volumineux (1Mo max)."; // Poids maxi : 1Mo => 1024*1024
      }
      // Vérification que l'extension est bien une image
      elseif (!in_array(strtolower(pathinfo($picture['name'], PATHINFO_EXTENSION)), $ext)) {
        $msg_error = "Le fichier n'est pas une image (Extensions autorisées : '.png', '.jpg', '.jpeg', '.gif').";
      }
      // Récupération et stockage du fichier image sur le serveur
      else {
        // Nouveau nom à l'image pour éviter les doublons
        $picture_name = uniqid() . '_' . $picture['name'];

        // Placement de l'image dans le dossier et droits de lecture/écriture
        @mkdir($img_folder, 0777);
        $dir = $img_folder . $picture_name;
        $move_file = @move_uploaded_file($picture['tmp_name'], $dir);

        if (!$move_file) {
          $msg_error = "Un problème est survenu pendant le téléversement, merci de renouveler votre envoi.";
        } else {
          $set_picture = TRUE;
        }
      }
    endif;
  endif;

  return array($set_picture, $picture_name, $msg_error);
}
