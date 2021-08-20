<?php
//////////////////////////////////////
// Requêtes d'affichage des données //
//////////////////////////////////////

//  01. Affichages des films
//    1.01 Sélection aléatoire de 18 médias type film par leur poster
//    1.02 Sélection des 7 films les plus récents par leur poster
//    1.03 Sélection aléatoire de 10 médias type film par leur fanart
//    1.04 Sélection d'un média type film par son identifiant
//    1.05 Sélection des titre, synopsis, phrase d'accroche, classification, studio, bande-annonce, pays et date de sortie du média type film
//    1.06 Sélection du logo du média type film
//    1.07 Sélection de la note du média type film
//    1.08 Sélection du discart du média type film
//    1.09 Sélection du fanart du média type film
//    1.10 Sélection jusqu'à 6 actrice(s)/acteur(s)/doubleur(s) du média type film
//    1.11 Sélection de tout(es) les actrice(s)/acteur(s)/doubleur(s) du média type film
//    1.12 Sélection des films selon le genre défini
//    1.13 Sélection des films dont le titre commence par le caractère défini
//    1.14 Sélection des films dont le titre commence par un chiffre
//    1.15 Sélection des genres pour les films
//    1.16 Sélection du nom d'un genre par son identifiant
//    1.17 Sélection des films selon le genre défini depuis la liste
//    1.18 Sélection des 200 films les plus populaires par leur poster
//    1.19 Sélection des 40 derniers médias type film ajoutés par leur poster

//  02. Affichages des séries
//    2.01 Sélection aléatoire de 3 médias type série par son poster
//    2.02 Sélection des 7 séries les plus récentes par leur poster
//    2.03 Sélection aléatoire de 2 médias type série par leur fanart
//    2.04 Sélection d'un média type série par son identifiant
//    2.05 Sélection des titre, synopsis, date de sortie, classification, studio, bande-annonce et pays du média type série
//    2.06 Sélection du logo du média type série
//    2.07 Sélection de la note du média type série
//    2.08 Sélection du fanart du média type série
//    2.09 Sélection jusqu'à 8 actrice(s)/acteur(s)/doubleur(s) du média type série
//    2.10 Sélection de tout(es) les actrice(s)/acteur(s)/doubleur(s) du média type série
//    2.11 Sélection des séries selon le genre défini
//    2.12 Sélection des séries dont le titre commence par le caractère défini
//    2.13 Sélection des séries dont le titre commence par un chiffre

//  03. Affichages des animes
//    3.01 Sélection des 7 animes de type film les plus récents par leur poster
//    3.02 Sélection des 7 animes de type série les plus récents par leur poster
//    3.03 Sélection des shōnen de type série
//    3.04 Sélection des shōnen de type film
//    3.05 Sélection des seinen de type série
//    3.06 Sélection des seinen de type film
//    3.07 Sélection des animes de type série dont le titre commence par le caractère défini
//    3.08 Sélection des animes de type film dont le titre commence par le caractère défini
//    3.09 Sélection des animes de type série dont le titre commence par un chiffre
//    3.10 Sélection des animes de type film dont le titre commence par un chiffre

//  04. Affichages des dessins animés
//    4.01 Sélection des 7 dessins animés les plus récents par leur poster
//    4.02 Sélection des dessins animés selon le genre défini
//    4.03 Sélection des dessins animés dont le titre commence par le caractère défini
//    4.04 Sélection des dessins animés dont le titre commence par un chiffre

//  05. Affichages des spectacles
//    5.01 Sélection des 7 spectacles les plus récents par leur poster
//    5.02 Sélection des spectacles selon le genre défini
//    5.03 Sélection des spectacles dont le titre commence par le caractère défini
//    5.04 Sélection des spectacles dont le titre commence par un chiffre

//  06. Affichages des collections
//    6.01 Sélection de toutes les collections par leur poster
//    6.02 Sélection de tous les médias de type film d'une collection par leur poster
//    6.03 Sélection de tous les médias de type série d'une collection par leur poster
//    6.04 Sélection du logo d'une collection
//    6.05 Sélection du fanart d'une collection

//  07. Affichages des studios
//    7.01 Sélection des films d'un studio par son nom
//    7.02 Sélection des séries d'un studio par son nom
//    7.03 Sélection des studios les plus populaires

//  08. Affichages des actrices/acteurs/doubleurs/directrice(s)/directeur(s)
//    8.01 Sélection du nom d'un(e) actrice/acteur/doubleur par son identifiant
//    8.02 Sélection des films d'un(e) actrice/acteur/doubleur par son identifiant
//    8.03 Sélection des séries d'un(e) actrice/acteur/doubleur par son identifiant
//    8.04 Sélection jusqu'à 1 directrice/directeur du média type film
//    8.05 Sélection des films d'un(e) directrice/directeur par son identifiant

//  09. Affichages de la watchlist
//    9.01 Sélection des médias type film de la watchlist
//    9.02 Sélection des médias type série de la watchlist
//    9.03 Sélection d'un pseudo d'utilisateur par son identifiant

//  10. Comptages
//   10.01 Comptage du nombre total de médias
//   10.02 Comptage des médias de la watchlist
//   10.03 Comptage du nombre total de fichiers image sur le serveur

// Appel du script d'administration des données
require __DIR__ . '/datamanager.php';

// 1.01 Sélection aléatoire de 18 médias type film par leur poster
function select_eighteen_random_movie(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      $sqlChild
      ORDER BY RAND()
      LIMIT 18"
    );
    $query->execute();
    $randMovie = $query->fetchAll(PDO::FETCH_ASSOC);
    return $randMovie;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.02 Sélection des 7 films les plus récents par leur poster
function select_seven_recent_movie(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      $sqlChild
      AND genre NOT LIKE '%Animation%'
      AND genre NOT LIKE '%Anime%'
      AND genre NOT LIKE '%Spectacle%'
      ORDER BY premiered DESC
      LIMIT 7"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.03 Sélection aléatoire de 10 médias type film par leur fanart
function select_ten_random_movie(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'fanart'
      $sqlChild
      ORDER BY RAND()
      LIMIT 10"
    );
    $query->execute();
    $randMovie = $query->fetchAll(PDO::FETCH_ASSOC);
    return $randMovie;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.04 Sélection d'un média type film par son identifiant
function select_movie_by_id(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT * FROM movie WHERE id = :id");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.05 Sélection des titre, synopsis, phrase d'accroche, classification, studio, bande-annonce, pays et date de sortie du média type film
function select_infos_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT title, synopsis, catch, classification, genre, studio, embed, country, premiered, profile
      FROM movie
      WHERE idMovie = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.06 Sélection du logo du média type film
function select_logo_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE idMovie = :id
      AND media_type = 'movie'
      AND type = 'clearlogo'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $logo = $query->fetch(PDO::FETCH_ASSOC);
    return $logo;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.07 Sélection de la note du média type film
function select_rating_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT rating
      FROM movie
      INNER JOIN rating ON movie.idMovie = rating.media_id
      WHERE idMovie = :id
      AND media_type = 'movie'
      ORDER BY media_id DESC LIMIT 1"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $rating = $query->fetch(PDO::FETCH_ASSOC);
    return $rating;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.08 Sélection du discart du média type film
function select_discart_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE idMovie = :id
      AND media_type = 'movie'
      AND type = 'discart'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $discart = $query->fetch(PDO::FETCH_ASSOC);
    return $discart;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.09 Sélection du fanart du média type film
function select_fanart_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE idMovie = :id
      AND media_type = 'movie'
      AND type = 'fanart'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $fanart = $query->fetch(PDO::FETCH_ASSOC);
    return $fanart;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.10 Sélection jusqu'à 6 actrice(s)/acteur(s)/doubleur(s) du média type film
function select_actors_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT *
      FROM actor_link
      INNER JOIN actor ON actor.actor_id = actor_link.actor_id
      WHERE media_id = :id
      AND media_type = 'movie'
      ORDER BY cast_order ASC
      LIMIT 6"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $actors = $query->fetchAll(PDO::FETCH_ASSOC);
    return $actors;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.11 Sélection de tout(es) les actrice(s)/acteur(s)/doubleur(s) du média type film
function select_all_actors_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT *
      FROM actor_link
      INNER JOIN actor ON actor.actor_id = actor_link.actor_id
      WHERE media_id = :id
      AND media_type = 'movie'
      ORDER BY cast_order ASC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $actors = $query->fetchAll(PDO::FETCH_ASSOC);
    return $actors;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.12 Sélection des films selon le genre défini
function select_movie_by_genre(string $genre)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE :genre
      AND genre NOT LIKE '%Animation%'
      AND genre NOT LIKE '%Anime%'
      AND genre NOT LIKE '%Spectacle%'
      ORDER BY title"
    );
    $query->bindValue(':genre', ('%' . $genre . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.13 Sélection des films dont le titre commence par le caractère défini
function select_movie_by_letter(string $letter)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE title LIKE :letter
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre NOT LIKE '%Animation%'
      AND genre NOT LIKE '%Anime%'
      AND genre NOT LIKE '%Spectacle%'
      ORDER BY title"
    );
    $query->bindValue(':letter', ($letter . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.14 Sélection des films dont le titre commence par un chiffre
function select_movie_by_numeric()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE (title LIKE '0%' OR
      title LIKE '1%' OR
      title LIKE '2%' OR
      title LIKE '3%' OR
      title LIKE '4%' OR
      title LIKE '5%' OR
      title LIKE '6%' OR
      title LIKE '7%' OR
      title LIKE '8%' OR
      title LIKE '9%')
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre NOT LIKE '%Animation%'
      AND genre NOT LIKE '%Anime%'
      AND genre NOT LIKE '%Spectacle%'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.15 Sélection des genres pour les films
function select_movie_genres()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT *
      FROM genre
      WHERE name NOT LIKE '%Adulte%'
      AND name NOT LIKE '%Arts Martiaux%'
      AND name NOT LIKE '%Erotique%'
      AND name NOT LIKE '%Peplum%'
      AND name NOT LIKE '%Suspense%'
      ORDER BY name"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.16 Sélection du nom d'un genre par son identifiant
function select_genre_name(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT name
      FROM genre
      WHERE genre_id = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $genre = $query->fetch(PDO::FETCH_ASSOC);
    return $genre;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.17 Sélection des films selon le genre défini depuis la liste
function select_genre_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, classification, genre, premiered, cachedurl
      FROM movie
      INNER JOIN genre_link ON movie.idMovie = genre_link.media_id
      INNER JOIN art ON genre_link.media_id = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre_id LIKE :id
      AND genre NOT LIKE '%Anime%'
      AND genre NOT LIKE '%Court-métrage%'
      AND genre NOT LIKE '%Spectacle%'
      ORDER BY premiered"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.18 Sélection des 200 films les plus populaires par leur poster
function select_best_movies()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, classification, genre, premiered, cachedurl
      FROM movie
      INNER JOIN rating ON movie.idMovie = rating.media_id
      INNER JOIN art ON movie.idMovie = art.media_id
      AND rating.media_type = 'movie'
      AND type = 'poster'
      ORDER BY votes DESC
      LIMIT 200"
    );
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);
    return $movies;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 1.19 Sélection des 40 derniers médias type film ajoutés par leur poster
function select_last_movie()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, premiered, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      ORDER BY idMovie DESC
      LIMIT 40"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.01 Sélection aléatoire de 3 médias type série par son poster
function select_three_random_tvshow(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'poster'
      $sqlChild
      ORDER BY RAND()
      LIMIT 3"
    );
    $query->execute();
    $randTvshow = $query->fetchAll(PDO::FETCH_ASSOC);
    return $randTvshow;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.02 Sélection des 7 séries les plus récentes par leur poster
function select_seven_recent_tvshow(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'poster'
      $sqlChild
      AND genre NOT LIKE '%Anime%'
      ORDER BY premiered DESC
      LIMIT 7"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.03 Sélection aléatoire de 2 médias type série par leur fanart
function select_two_random_tvshow(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'fanart'
      $sqlChild
      ORDER BY RAND()
      LIMIT 2"
    );
    $query->execute();
    $randTvshow = $query->fetchAll(PDO::FETCH_ASSOC);
    return $randTvshow;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.04 Sélection d'un média type série par son identifiant
function select_tvshow_by_id(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT * FROM tvshow WHERE id = :id");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.05 Sélection des titre, synopsis, date de sortie, classification, studio, bande-annonce et pays du média type série
function select_infos_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT title, synopsis, premiered, classification, genre, studio, embed, country, profile
      FROM tvshow
      WHERE idShow = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.06 Sélection du logo du média type série
function select_logo_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE idShow = :id
      AND media_type = 'tvshow'
      AND type = 'clearlogo'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $logo = $query->fetch(PDO::FETCH_ASSOC);
    return $logo;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.07 Sélection de la note du média type série
function select_rating_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT rating
      FROM tvshow
      INNER JOIN rating ON tvshow.idShow = rating.media_id
      WHERE idShow = :id
      AND media_type = 'tvshow'
      ORDER BY media_id DESC LIMIT 1"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $rating = $query->fetch(PDO::FETCH_ASSOC);
    return $rating;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.08 Sélection du fanart du média type série
function select_fanart_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE idShow = :id
      AND media_type = 'tvshow'
      AND type = 'fanart'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $fanart = $query->fetch(PDO::FETCH_ASSOC);
    return $fanart;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.09 Sélection jusqu'à 8 actrice(s)/acteur(s)/doubleur(s) du média type série
function select_actors_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT *
      FROM actor_link
      INNER JOIN actor ON actor.actor_id = actor_link.actor_id
      WHERE media_id = :id
      AND media_type = 'tvshow'
      ORDER BY cast_order ASC
      LIMIT 8"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $actors = $query->fetchAll(PDO::FETCH_ASSOC);
    return $actors;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.10 Sélection de tout(es) les actrice(s)/acteur(s)/doubleur(s) du média type série
function select_all_actors_tvshow(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT *
      FROM actor_link
      INNER JOIN actor ON actor.actor_id = actor_link.actor_id
      WHERE media_id = :id
      AND media_type = 'tvshow'
      ORDER BY cast_order ASC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $actors = $query->fetchAll(PDO::FETCH_ASSOC);
    return $actors;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.11 Sélection des séries selon le genre défini
function select_tvshow_by_genre(string $genre)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'poster'
      AND genre LIKE :genre
      AND genre NOT LIKE '%Anime%'
      ORDER BY title"
    );
    $query->bindValue(':genre', ('%' . $genre . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.12 Sélection des séries dont le titre commence par le caractère défini
function select_tvshow_by_letter(string $letter)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE title LIKE :letter
      AND media_type = 'tvshow'
      AND type = 'poster'
      AND genre NOT LIKE '%Anime%'
      ORDER BY title"
    );
    $query->bindValue(':letter', ($letter . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 2.13 Sélection des films dont le titre commence par un chiffre
function select_tvshow_by_numeric()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE (title LIKE '0%' OR
      title LIKE '1%' OR
      title LIKE '2%' OR
      title LIKE '3%' OR
      title LIKE '4%' OR
      title LIKE '5%' OR
      title LIKE '6%' OR
      title LIKE '7%' OR
      title LIKE '8%' OR
      title LIKE '9%')
      AND media_type = 'tvshow'
      AND type = 'poster'
      AND genre NOT LIKE '%Anime%'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.01 Sélection des 7 animes de type film les plus récents par leur poster
function select_seven_recent_anime_movie(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      $sqlChild
      ORDER BY premiered DESC
      LIMIT 7"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.02 Sélection des 7 animes de type série les plus récents par leur poster
function select_seven_recent_anime_tvshow()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      ORDER BY premiered DESC
      LIMIT 7"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.03 Sélection des shōnen de type série
function select_shonen_tvshow()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      AND classification NOT LIKE '%Rated R%'
      AND classification NOT LIKE '%Rated NC-17%'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.04 Sélection des shōnen de type film
function select_shonen_movie()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      AND classification NOT LIKE '%Rated R%'
      AND classification NOT LIKE '%Rated NC-17%'
      ORDER BY title"
    );
    $query->execute();
    $result2 = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result2;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.05 Sélection des seinen de type série
function select_seinen_tvshow()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE media_type = 'tvshow'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      AND classification NOT LIKE ''
      AND classification NOT LIKE '%PG%'
      AND classification NOT LIKE '%TV%'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.06 Sélection des seinen de type film
function select_seinen_movie()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      AND classification NOT LIKE ''
      AND classification NOT LIKE '%Rated Tous publics%'
      AND classification NOT LIKE '%Rated U%'
      AND classification NOT LIKE '%Rated PG%'
      ORDER BY title"
    );
    $query->execute();
    $result2 = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result2;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.07 Sélection des animes de type série dont le titre commence par le caractère défini
function select_anime_tvshow_by_letter(string $letter)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE title LIKE :letter
      AND media_type = 'tvshow'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      ORDER BY title"
    );
    $query->bindValue(':letter', ($letter . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.08 Sélection des animes de type film dont le titre commence par le caractère défini
function select_anime_movie_by_letter(string $letter)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE title LIKE :letter
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Anime%'
      ORDER BY title"
    );
    $query->bindValue(':letter', ($letter . '%'), PDO::PARAM_STR);
    $query->execute();
    $result2 = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result2;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.09 Sélection des animes de type série dont le titre commence par un chiffre
function select_anime_tvshow_by_numeric()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, synopsis, studio, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE (title LIKE '0%' OR
      title LIKE '1%' OR
      title LIKE '2%' OR
      title LIKE '3%' OR
      title LIKE '4%' OR
      title LIKE '5%' OR
      title LIKE '6%' OR
      title LIKE '7%' OR
      title LIKE '8%' OR
      title LIKE '9%')
      AND genre LIKE '%Anime%'
      AND media_type = 'tvshow'
      AND type = 'poster'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 3.10 Sélection des animes de type film dont le titre commence par un chiffre
function select_anime_movie_by_numeric()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE (title LIKE '0%' OR
      title LIKE '1%' OR
      title LIKE '2%' OR
      title LIKE '3%' OR
      title LIKE '4%' OR
      title LIKE '5%' OR
      title LIKE '6%' OR
      title LIKE '7%' OR
      title LIKE '8%' OR
      title LIKE '9%')
      AND genre LIKE '%Anime%'
      AND media_type = 'movie'
      AND type = 'poster'
      ORDER BY title"
    );
    $query->execute();
    $result2 = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result2;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 4.01 Sélection des 7 dessins animés les plus récents par leur poster
function select_seven_recent_animation()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Animation%'
      AND genre NOT LIKE '%Court-métrage%'
      ORDER BY premiered DESC
      LIMIT 7"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 4.02 Sélection des dessins animés selon le genre défini
function select_animation_by_genre(string $genre)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE :genre
      AND genre LIKE '%Animation%'
      ORDER BY title"
    );
    $query->bindValue(':genre', ('%' . $genre . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 4.03 Sélection des dessins animés dont le titre commence par le caractère défini
function select_animation_by_letter(string $letter)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE title LIKE :letter
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Animation%'
      AND genre NOT LIKE '%Court-métrage%'
      ORDER BY title"
    );
    $query->bindValue(':letter', ($letter . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 4.04 Sélection des dessins animés dont le titre commence par un chiffre
function select_animation_by_numeric()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE (title LIKE '0%' OR
      title LIKE '1%' OR
      title LIKE '2%' OR
      title LIKE '3%' OR
      title LIKE '4%' OR
      title LIKE '5%' OR
      title LIKE '6%' OR
      title LIKE '7%' OR
      title LIKE '8%' OR
      title LIKE '9%')
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Animation%'
      AND genre NOT LIKE '%Court-métrage%'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.01 Sélection des 7 spectacles les plus récents par leur poster
function select_seven_recent_spectacle()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Spectacle%'
      ORDER BY premiered DESC
      LIMIT 7"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.02 Sélection des spectacles selon le genre défini
function select_spectacle_by_genre(string $genre)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE :genre
      AND genre LIKE '%Spectacle%'
      ORDER BY title"
    );
    $query->bindValue(':genre', ('%' . $genre . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.03 Sélection des spectacles dont le titre commence par le caractère défini
function select_spectacle_by_letter(string $letter)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE title LIKE :letter
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Spectacle%'
      ORDER BY title"
    );
    $query->bindValue(':letter', ($letter . '%'), PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 5.04 Sélection des spectacles dont le titre commence par un chiffre
function select_spectacle_by_numeric()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, synopsis, studio, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE (title LIKE '0%' OR
      title LIKE '1%' OR
      title LIKE '2%' OR
      title LIKE '3%' OR
      title LIKE '4%' OR
      title LIKE '5%' OR
      title LIKE '6%' OR
      title LIKE '7%' OR
      title LIKE '8%' OR
      title LIKE '9%')
      AND media_type = 'movie'
      AND type = 'poster'
      AND genre LIKE '%Spectacle%'
      ORDER BY title"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 6.01 Sélection de toutes les collections par leur poster
function select_all_sets(string $sqlChild)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT s.idSet, m.title, m.premiered, a.cachedurl
      FROM sets AS s
      INNER JOIN art AS a ON s.idSet = a.media_id
      INNER JOIN movie AS m ON s.idSet = m.idSet
      WHERE a.media_type = 'set'
      AND a.type = 'poster'
      $sqlChild
      ORDER BY s.strSet, m.premiered DESC"
    );
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 6.02 Sélection de tous les médias de type film d'une collection par leur poster
function select_movies_collection(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE idSet = :id
      AND media_type = 'movie'
      AND type = 'poster'
      ORDER BY premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);
    return $movies;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 6.03 Sélection de tous les médias de type série d'une collection par leur poster
function select_tvshows_collection(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE idSet = :id
      AND media_type = 'tvshow'
      AND type = 'poster'
      ORDER BY premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $tvshows = $query->fetchAll(PDO::FETCH_ASSOC);
    return $tvshows;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 6.04 Sélection du logo d'une collection
function select_logo_collection(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idSet, strSet, cachedurl
      FROM sets
      INNER JOIN art ON sets.idSet = art.media_id
      WHERE idSet = :id
      AND media_type = 'set'
      AND type = 'clearlogo'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $logo = $query->fetch(PDO::FETCH_ASSOC);
    return $logo;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 6.05 Sélection du fanart d'une collection
function select_fanart_collection(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idSet, strSet, cachedurl
      FROM sets
      INNER JOIN art ON sets.idSet = art.media_id
      WHERE idSet = :id
      AND media_type = 'set'
      AND type = 'fanart'"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $fanart = $query->fetch(PDO::FETCH_ASSOC);
    return $fanart;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 7.01 Sélection des films d'un studio par son nom
function select_movie_from_studio(string $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      WHERE studio LIKE :id
      AND media_type = 'movie'
      AND type = 'poster'
      ORDER BY premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);
    return $movies;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 7.02 Sélection des séries d'un studio par son nom
function select_tvshow_from_studio(string $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      WHERE studio LIKE :id
      AND media_type = 'tvshow'
      AND type = 'poster'
      ORDER BY premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);
    return $movies;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 7.03 Sélection des studios les plus populaires
function select_best_studios()
{
  $sql = "(name LIKE '20th Century Fox' OR
  name LIKE 'Aardman Animations' OR
  name LIKE 'Amazon' OR
  name LIKE 'Amblin Entertainment' OR
  name LIKE 'Bad Robot' OR
  name LIKE 'Blue Sky Studios' OR
  name LIKE 'Blumhouse Productions' OR
  name LIKE 'Columbia Pictures' OR
  name LIKE 'DC Entertainment' OR
  name LIKE 'DreamWorks Animation' OR
  name LIKE 'Dune Entertainment' OR
  name LIKE 'Illumination Entertainment' OR
  name LIKE 'Legendary Entertainment' OR
  name LIKE 'LionsGate' OR
  name LIKE 'Lucasfilm' OR
  name LIKE 'Marvel Studios' OR
  name LIKE 'Metro-Goldwyn-Mayer' OR
  name LIKE 'Netflix' OR
  name LIKE 'New Line Cinema' OR
  name LIKE 'Paramount Pictures' OR
  name LIKE 'Pixar' OR
  name LIKE 'Sony Pictures Animation' OR
  name LIKE 'Summit Entertainment' OR
  name LIKE 'Touchstone Pictures' OR
  name LIKE 'Universal Pictures' OR
  name LIKE 'Walt Disney Pictures' OR
  name LIKE 'Warner Bros.')";
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT * FROM studio
      WHERE $sql
      ORDER BY name"
    );
    $query->execute();
    $studios = $query->fetchAll(PDO::FETCH_ASSOC);
    return $studios;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 8.01 Sélection du nom d'un(e) actrice/acteur/doubleur par son identifiant
function select_actor_name(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT name, cachedurl
      FROM actor
      WHERE actor_id = :id"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $actor = $query->fetch(PDO::FETCH_ASSOC);
    return $actor;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 8.02 Sélection des films d'un(e) actrice/acteur/doubleur par son identifiant
function select_movie_from_actor(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idMovie, title, cachedurl
      FROM movie
      INNER JOIN art ON movie.idMovie = art.media_id
      INNER JOIN actor_link_movie ON art.media_id = actor_link_movie.media_id
      WHERE actor_id = :id
      AND media_type = 'movie'
      AND type = 'poster'
      ORDER BY premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 8.03 Sélection des séries d'un(e) actrice/acteur/doubleur par son identifiant
function select_tvshow_from_actor(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT idShow, title, cachedurl
      FROM tvshow
      INNER JOIN art ON tvshow.idShow = art.media_id
      INNER JOIN actor_link_tvshow ON art.media_id = actor_link_tvshow.media_id
      WHERE actor_id = :id
      AND media_type = 'tvshow'
      AND type = 'poster'
      ORDER BY premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 8.04 Sélection jusqu'à 1 directrice/directeur du média type film
function select_director_movie(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT *
      FROM director_link
      INNER JOIN actor ON actor.actor_id = director_link.actor_id
      WHERE media_id = :id
      AND media_type = 'movie'
      ORDER BY name
      LIMIT 1"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $director = $query->fetchAll(PDO::FETCH_ASSOC);
    return $director;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 8.05 Sélection des films d'un(e) directrice/directeur par son identifiant
function select_movie_from_director(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT m.idMovie, m.title, m.synopsis, m.classification, m.genre, m.premiered, a.cachedurl
      FROM movie AS m
      JOIN director_link AS d
      ON m.idMovie = d.media_id
      JOIN art AS a
      ON d.media_id = a.media_id
      WHERE d.actor_id = :id
      AND a.media_type = 'movie'
      AND a.type = 'poster'
      ORDER BY m.premiered DESC"
    );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $movies = $query->fetchAll(PDO::FETCH_ASSOC);
    return $movies;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 9.01 Sélection des médias type film de la watchlist
function select_my_movie(int $user)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT m.idMovie, m.title, m.premiered, a.cachedurl, w.created_at
      FROM movie AS m
      JOIN watchlist AS w
      ON w.media_id = m.idMovie
      JOIN art AS a
      ON m.idMovie = a.media_id
      WHERE w.user_id = :user
      AND a.media_type = 'movie'
      AND a.type = 'poster'
      ORDER BY w.created_at DESC"
    );
    $query->bindValue(':user', $user, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 9.03 Sélection d'un pseudo d'utilisateur par son identifiant
function select_username_by_id(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT username FROM users WHERE id=:id");

    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 10.01 Comptage du nombre total de médias
function count_all_media(string $sqlChild)
{
  connexion($dbco);
  try {
    $nMovies = $dbco->query("SELECT COUNT(*) FROM movie $sqlChild")->fetchColumn();
    $nTvshows = $dbco->query("SELECT COUNT(*) FROM tvshow $sqlChild")->fetchColumn();
    $nCount = $nMovies + $nTvshows;
    return $nCount;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 10.02 Comptage des médias de la watchlist
function count_my_movie(int $user)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare(
      "SELECT COUNT(*) FROM watchlist WHERE user_id LIKE :user"
    );
    $query->bindValue(':user', $user, PDO::PARAM_INT);
    $query->execute();
    $count = $query->fetchColumn();
    $total_rows = (int) $count;
    return $total_rows;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// 10.03 Comptage du nombre total de fichiers image sur le serveur
function count_all_image()
{
  $img_folder = dirname(__DIR__) . '/thumbnails/*/';
  $images = glob("$img_folder{*.jpg,*.JPG,*.jpeg,*.JPEG,*.png,*.PNG}", GLOB_BRACE);
  $count = count($images);
  return $count;
}
