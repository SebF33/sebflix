<?php
////////////////////////////////////
// Connexion à la base de données //
////////////////////////////////////

function connexion(&$conn)
{
  // Informations de connexion à la base de données
  $host = "localhost";
  $user = "seblix100905";
  $pwd = "Dh5oiY54Kggt417kJnj5987?jhgFFpoT86*";

  // Tentative de connexion
  try {
    $conn = new PDO("mysql:host=$host;dbname=myvideos", $user, $pwd);
    // Charset = 'UTF-8'
    $conn->exec('SET NAMES utf8');
    // Mode d'erreur de PDO sur 'Exception'
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  // Gestion des erreurs
  catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
