<?php
///////////////////////////////////////////
// Requêtes d'administration des données //
///////////////////////////////////////////

// Appel du script de connexion à la base de données
require __DIR__ . '/connect.php';

// Requête de sélection d'un film par son identifiant
function select_movie_by_id(int $id)
{
  $dbco = NULL;
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT * FROM movie WHERE id=:id");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
