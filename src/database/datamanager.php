<?php
///////////////////////////////////////////
// Requêtes d'administration des données //
///////////////////////////////////////////

//  01. Sélections
//    1.01 Sélection d'un média type film
//    1.02 Sélection et tri de tous les articles

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

// 1.02 Sélection et tri de tous les articles
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

// 2.01 Ajout d'un média type film
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

// 3.01 Mise à jour d'un média type film
function update_movie(array $datas, int $id, bool $set_picture)
{
  connexion($dbco);
  try {
    $sql_file = $set_picture ? "; UPDATE art SET cachedurl=:picture WHERE media_id=:id AND media_type='movie' AND type='poster'" : "";

    $req = $dbco->prepare("UPDATE movie
    SET title=:title, synopsis=:synopsis, catch=:catch, premiered=:premiered
    WHERE idMovie=:id
    $sql_file");
    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':synopsis', $datas['synopsis'], PDO::PARAM_STR);
    $req->bindValue(':catch', $datas['catch'], PDO::PARAM_STR);
    $req->bindValue(':premiered', $datas['premiered'], PDO::PARAM_STR);
    if ($set_picture) {
      $req->bindValue(':picture', $datas['picture'], PDO::PARAM_STR);
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
