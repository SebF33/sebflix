<?php
//////////////////////////////////////
// Requêtes d'affichage des données //
//////////////////////////////////////

//  1. Affichages
//    1.1 Sélection aléatoire de 6 films par leur poster
//    1.2 Sélection aléatoire de 12 films par leur fanart
//  2. Comptages
//    2.1 Comptage du nombre total de médias
//    2.2 Comptage du nombre total de fichiers image sur le serveur


// Appel du script de connexion à la base de données
require __DIR__ . '/datamanager.php';

// 1.1 Sélection aléatoire de 6 films par leur poster
function select_six_random_movie()
{
  $dbco = NULL;
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE media_type = 'movie'
    AND type = 'poster'
    ORDER BY RAND()
    LIMIT 6");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.2 Sélection aléatoire de 12 films par leur fanart
function select_twelve_random_movie()
{
  $dbco = NULL;
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE media_type = 'movie'
    AND type = 'fanart'
    ORDER BY RAND()
    LIMIT 12");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.1 Comptage du nombre total de médias
function count_all_media()
{
  $dbco = NULL;
  connexion($dbco);
  try {
    $nMovies = $dbco->query("SELECT COUNT(*) FROM movie")->fetchColumn();
    $nTvshows = $dbco->query("SELECT COUNT(*) FROM tvshow")->fetchColumn();
    $nCount = $nMovies + $nTvshows;

    return $nCount;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.2 Comptage du nombre total de fichiers image sur le serveur
function count_all_image()
{
  $img_folder = dirname(__DIR__) . '/thumbnails/*/';
  $images = glob("$img_folder{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG}", GLOB_BRACE);
  $count = count($images);

  return $count;
}
