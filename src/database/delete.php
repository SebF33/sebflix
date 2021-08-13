<?php
/////////////////////////////
// SUPPRESSION DES DONNÉES //
/////////////////////////////

// Appel du script de validation
require __DIR__ . '/validation.php';
// Appel du script d'administration des utilisateurs
require __DIR__ . '/usermanager.php';

// Définition des valeurs autorisées dans le GET
$types = array('movie', 'tvshow', 'user');

// Vérification des GET ('type' obligatoire)
if (isset($_GET['type']) && !empty($_GET['type']) && in_array($_GET['type'], $types)) {
  // Déclaration et nettoyage des GET
  $type = valid_get($_GET['type']);
  $id = valid_data($_GET['id']);

  if ($type == 'movie' && !isset($_GET['user'])) {
    // Appel de la fonction de suppression d'un média type film
    delete_movie($id);
    // Redirection
    header('location:/src/views/crud.php');
  } elseif ($type == 'user') {
    // Appel de la fonction de suppression d'un utilisateur
    delete_user($id);
    // Redirection
    header('location:/src/views/users-list.php');
  } elseif (isset($_GET['user']) && !empty($_GET['user'])) {
    $user = valid_data($_GET['user']);
    // Appel de la fonction de suppression d'un média type film de la watchlist
    delete_my_movie($user, $id, $type);
    // Redirection
    header('location:/src/views/watchlist.php');
  }
} else {
  // Redirection
  header("location:/index.php");
  exit;
}
