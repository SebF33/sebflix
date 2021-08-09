<?php
///////////////////////////////////////////
// Requêtes d'administration des données //
///////////////////////////////////////////

// Appel du script de connexion à la base de données
require __DIR__ . '/connect.php';

// Ajout d'un média type film
function add_movie(array $datas)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("INSERT INTO movie(title, synopsis, catch, premiered)
		VALUES(:title, :synopsis, :catch, :premiered);
		INSERT INTO art(media_id, media_type, type, cachedurl)
		VALUES(LAST_INSERT_ID(), 'movie', 'poster', :picture)");

    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':synopsis', $datas['synopsis'], PDO::PARAM_STR);
    $req->bindValue(':catch', $datas['catch'], PDO::PARAM_STR);
    $req->bindValue(':premiered', $datas['premiered'], PDO::PARAM_STR);
    $req->bindValue(':picture', $datas['picture'], PDO::PARAM_STR);
    return $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Sélection d'un média type film
function select_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE idMovie = :id
    AND media_type = 'movie'
    AND type = 'poster'");

    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Sélection et tri de tous les articles
function select_all_bio_sort(string $sort)
{
  connexion($dbco);
  try {
    if ($sort == 'ASC') {
      $query = $dbco->prepare("SELECT * FROM products ORDER BY price ASC");
    } else {
      $query = $dbco->prepare("SELECT * FROM products ORDER BY price DESC");
    }

    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// [A REVOIR] Mise à jour d'un média type film
function update_movie(array $datas, int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT cachedurl FROM art
    WHERE media_id=:id
    AND media_type='movie'
    AND type='poster'");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result != "placeholders/generic_poster.jpg" or !empty($result)) {
      $file_exists = TRUE; // Le fichier existe
    } else {
      $file_exists = FALSE; // Le fichier n'existe pas
    }

    $sql_file = $file_exists ? "; UPDATE art SET cachedurl=:picture WHERE media_id=:id AND media_type='movie' AND type='poster'" : "";

    $req = $dbco->prepare("UPDATE movie
    SET title=:title, synopsis=:synopsis, catch=:catch, premiered=:premiered
    WHERE idMovie=:id
    $sql_file");
    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':synopsis', $datas['synopsis'], PDO::PARAM_STR);
    $req->bindValue(':catch', $datas['catch'], PDO::PARAM_STR);
    $req->bindValue(':premiered', $datas['premiered'], PDO::PARAM_STR);
    if ($file_exists) {
      $req->bindValue(':picture', $datas['picture'], PDO::PARAM_STR);
    }
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    return $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Suppression d'un média type film
function delete_movie(int $id)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("DELETE FROM movie
    WHERE id=:id;
    DELETE FROM art
    WHERE media_id=:id
    AND media_type='movie'");

    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
