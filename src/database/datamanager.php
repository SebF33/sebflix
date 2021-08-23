<?php
///////////////////////////////////////////
// Requêtes d'administration des données //
///////////////////////////////////////////

//  01. Sélections
//    1.01 Sélection d'un média type film
//    1.02 Sélection du fond d'un média type film

//  02. Ajouts
//    2.01 Ajout d'un média type film

//  03. Mises à jour
//    3.01 Mise à jour d'un média type film

//  04. Suppressions
//    4.01 Suppression d'un média type film

//  05. Vérifications
//    5.01 Vérification de l'existence de l'ID du média type film
//    5.02 Vérification de l'existence de l'ID du média type série
//    5.03 Vérification de l'existence du studio
//    5.04 Vérification de l'existence de l'ID d'un(e) actrice/acteur/doubleur/directrice/directeur
//    5.05 Vérification de l'existence de l'ID d'un genre
//    5.06 Vérification de l'existence de l'ID d'un set
//    5.07 Vérification de l'existence de l'ID d'un utilisateur

// Appel du script de connexion à la base de données
require __DIR__ . '/connect.php';

// 1.01 Sélection d'un média type film
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

// 1.02 Sélection du fond d'un média type film
function select_movie_background(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT cachedurl FROM art WHERE media_id = :id AND media_type = 'movie' AND type = 'fanart'");

    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.01 Ajout d'un média type film
function add_movie(array $datas)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("INSERT INTO movie(title, synopsis, catch, premiered)
		VALUES(:title, :synopsis, :catch, :premiered)");
    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':synopsis', $datas['synopsis'], PDO::PARAM_STR);
    $req->bindValue(':catch', $datas['catch'], PDO::PARAM_STR);
    $req->bindValue(':premiered', $datas['premiered'], PDO::PARAM_STR);
    $req->execute();
    $lastId = $dbco->lastInsertId();

    $reqArts = $dbco->prepare("INSERT INTO art(media_id, media_type, type, cachedurl)
		VALUES($lastId, 'movie', 'poster', :poster);
    INSERT INTO art(media_id, media_type, type, cachedurl)
		VALUES($lastId, 'movie', 'fanart', :background)");
    $reqArts->bindValue(':poster', $datas['poster'], PDO::PARAM_STR);
    $reqArts->bindValue(':background', $datas['background'], PDO::PARAM_STR);

    return $reqArts->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.01 Mise à jour d'un média type film
function update_movie(array $datas, int $id, bool $set_poster, string $default_poster_name, bool $set_background, string $default_background_name)
{
  $img_folder = dirname(dirname(__DIR__)) . '/src/thumbnails/';
  $sql_poster_file = $set_poster ? "; UPDATE art SET cachedurl=:poster WHERE media_id=:id AND media_type='movie' AND type='poster'" : "";
  $sql_background_file = $set_background ? "; UPDATE art SET cachedurl=:background WHERE media_id=:id AND media_type='movie' AND type='fanart'" : "";

  connexion($dbco);
  try {
    // Sélection de l'affiche actuelle
    $query = $dbco->prepare(
      "SELECT cachedurl FROM art WHERE media_id = :id AND media_type = 'movie' AND type = 'poster'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $current_poster = $query->fetch(PDO::FETCH_ASSOC);

    // Sélection du fond actuel
    $query = $dbco->prepare(
      "SELECT cachedurl FROM art WHERE media_id = :id AND media_type = 'movie' AND type = 'fanart'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $current_background = $query->fetch(PDO::FETCH_ASSOC);

    // Mise à jour de la base de données
    $req = $dbco->prepare("UPDATE movie
    SET title=:title, synopsis=:synopsis, catch=:catch, premiered=:premiered
    WHERE idMovie=:id
    $sql_poster_file
    $sql_background_file");
    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':synopsis', $datas['synopsis'], PDO::PARAM_STR);
    $req->bindValue(':catch', $datas['catch'], PDO::PARAM_STR);
    $req->bindValue(':premiered', $datas['premiered'], PDO::PARAM_STR);
    if ($set_poster) {
      $req->bindValue(':poster', $datas['poster'], PDO::PARAM_STR);
      // Suppression de l'ancienne affiche si celle-ci n'est pas par défaut
      if ($current_poster['cachedurl'] != $default_poster_name) {
        unlink($img_folder . $current_poster['cachedurl']);
      }
    }
    if ($set_background) {
      $req->bindValue(':background', $datas['background'], PDO::PARAM_STR);
      // Suppression de l'ancien fond si celui-ci n'est pas par défaut
      if ($current_background['cachedurl'] != $default_background_name) {
        unlink($img_folder . $current_background['cachedurl']);
      }
    }
    $req->bindValue(':id', $id, PDO::PARAM_INT);

    return $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 4.01 Suppression d'un média type film
function delete_movie(int $id)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("DELETE FROM movie
    WHERE idMovie=:id;
    DELETE FROM art
    WHERE media_id=:id
    AND media_type='movie'");

    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.01 Vérification de l'existence de l'ID du média type film
function check_id_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie
      FROM movie
      WHERE idMovie = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $checkid = $query->rowCount();
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.02 Vérification de l'existence de l'ID du média type série
function check_id_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow
      FROM tvshow
      WHERE idShow = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $checkid = $query->rowCount();
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.03 Vérification de l'existence du studio
function check_id_studio(string $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT studio
      FROM movie
      WHERE studio LIKE :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $checkidMovie = $query->rowCount();

    $query = $dbco->prepare(
      "SELECT studio
      FROM tvshow
      WHERE studio LIKE :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $checkidTvshow = $query->rowCount();

    $checkid = $checkidMovie + $checkidTvshow;
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.04 Vérification de l'existence de l'ID d'un(e) actrice/acteur/doubleur/directrice/directeur
function check_id_actor(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT actor_id
      FROM actor
      WHERE actor_id = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $checkid = $query->rowCount();
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.05 Vérification de l'existence de l'ID d'un genre
function check_id_genre(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT genre_id
      FROM genre
      WHERE genre_id = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $checkid = $query->rowCount();
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.06 Vérification de l'existence de l'ID d'un set
function check_id_set(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idSet
      FROM sets
      WHERE idSet = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $checkid = $query->rowCount();
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.07 Vérification de l'existence de l'ID d'un utilisateur
function check_id_user(int $user)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT id
      FROM users
      WHERE id = :user"
    );
    $query->bindValue(':user', $user, PDO::PARAM_INT);
    $query->execute();
    $checkid = $query->rowCount();
    return $checkid;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
