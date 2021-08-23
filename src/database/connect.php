<?php
////////////////////////////////////
// Connexion à la base de données //
////////////////////////////////////

function connexion(&$dbco)
{
  // Informations de connexion à la base de données
  $host = "localhost";
  $user = "seblix100905";
  $pwd = "Dh5oiY54Kggt417kJnj5987?jhgFFpoT86*";

  // Tentative de connexion
  try {
    $dbco = new PDO("mysql:host=$host;dbname=182x2_myvideos;charset=utf8mb4", $user, $pwd);
    // Mode d'erreur de PDO sur 'Exception'
    $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  // Gestion des erreurs
  catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
