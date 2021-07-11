<!------------------------->
<!-- Moteur de recherche -->
<!------------------------->

<?php
// Déclaration et nettoyage des variables d'entrée
$query = htmlspecialchars($_GET['search']);
$query = trim($query);

// Initialisation de la variable des résultats
$result = "";

// Vérification des termes entrés par l'utilisateur
if (isset($query) && !empty($query)) {

  // Traitement de l'option "films" choisie par l'utilisateur
  if ($type == 'movies') {
    // Requête de comptage pour la table "movie"
    $sql = 'SELECT COUNT(*) FROM movie WHERE title LIKE :query';
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->execute();

    // Comptage des résultats des films
    $count = $queryPrepared->fetchColumn();
    $nbMovies = (int) $count;

    // Affichage du résultat non nul
    if ($count >= 1) {
      $result .= "$count résultat(s) trouvé(s) pour <strong> '$query' </strong> <br/>";
    }
    // Affichage du résultat nul
    else {
      $result .= "\n <hr/> Aucun résultat trouvé pour <strong> '$query' </strong>";
    }

    // Définition du nombre de films par page
    $perPage = 30;
    // Calcul du nombre de pages total
    $pages = ceil($nbMovies / $perPage);
    // Calcul du 1er média de la page
    $first = ($currentPage * $perPage) - $perPage;

    // Requête de sélection dans la table "movie"
    $sql = "SELECT * FROM movie
            INNER JOIN art ON movie.idMovie = art.media_id
            WHERE title LIKE :query AND media_type = 'movie' AND type = 'poster'
            ORDER BY premiered DESC LIMIT :first, :perpage;";
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->bindValue(':first', $first, PDO::PARAM_INT);
    $queryPrepared->bindValue(':perpage', $perPage, PDO::PARAM_INT);
    $queryPrepared->execute();
    $movies = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
  }

  // Traitement de l'option "séries" choisie par l'utilisateur
  else if ($type == 'tvshows') {
    // Requête de comptage pour la table "tvshow"
    $sql = 'SELECT COUNT(*) FROM tvshow WHERE title LIKE :query';
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->execute();

    // Comptage des résultats des séries
    $count = $queryPrepared->fetchColumn();
    $nbTvshows = (int) $count;

    // Affichage du résultat non nul
    if ($count >= 1) {
      $result .= "$count résultat(s) trouvé(s) pour <strong> '$query' </strong> <br/>";
    }
    // Affichage du résultat nul
    else {
      $result .= "\n <hr/> Aucun résultat trouvé pour <strong> '$query' </strong>";
    }

    // Définition du nombre de séries par page
    $perPage = 30;
    // Calcul du nombre de pages total
    $pages = ceil($nbTvshows / $perPage);
    // Calcul de la 1ère série de la page
    $first = ($currentPage * $perPage) - $perPage;

    // Requête de sélection dans la table "tvshow"
    $sql = "SELECT * FROM tvshow
    INNER JOIN art ON tvshow.idShow = art.media_id
    WHERE title LIKE :query AND media_type = 'tvshow' AND type = 'poster'
    ORDER BY premiered DESC LIMIT :first, :perpage;";
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->bindValue(':first', $first, PDO::PARAM_INT);
    $queryPrepared->bindValue(':perpage', $perPage, PDO::PARAM_INT);
    $queryPrepared->execute();
    $tvshows = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
  }

  // Traitement de l'option "acteurs" choisie par l'utilisateur
  else if ($type == 'actors') {
    // Requête de comptage pour la table "actor"
    $sql = 'SELECT COUNT(*) FROM actor WHERE name LIKE :query';
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->execute();

    // Comptage des résultats pour la table "actor"
    $count = $queryPrepared->fetchColumn();
    $nbActors = (int) $count;

    // Affichage du résultat non nul
    if ($count >= 1) {
      $result .= "$count résultat(s) trouvé(s) pour <strong> '$query' </strong> <br/>";
    }
    // Affichage du résultat nul
    else {
      $result .= "\n <hr/> Aucun résultat trouvé pour <strong> '$query' </strong>";
    }

    // Définition du nombre d'acteurs par page
    $perPage = 60;
    // Calcul du nombre de pages total
    $pages = ceil($nbActors / $perPage);
    // Calcul du 1er acteur de la page
    $first = ($currentPage * $perPage) - $perPage;

    // Requête de sélection dans la table "actor"
    $sql = 'SELECT * FROM actor
            WHERE name LIKE :query
            ORDER BY name LIMIT :first, :perpage;';
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->bindValue(':first', $first, PDO::PARAM_INT);
    $queryPrepared->bindValue(':perpage', $perPage, PDO::PARAM_INT);
    $queryPrepared->execute();
    $actors = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
  }

  // Traitement de l'option "studios" choisie par l'utilisateur
  else if ($type == 'studios') {
    // Requête de comptage des studios
    $sql = 'SELECT COUNT(*) FROM studio WHERE name LIKE :query';
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->execute();

    // Comptage des résultats pour les studios
    $count = $queryPrepared->fetchColumn();
    $nbStudios = (int) $count;

    // Affichage du résultat non nul
    if ($count >= 1) {
      $result .= "$count résultat(s) trouvé(s) pour <strong> '$query' </strong> <br/>";
    }
    // Affichage du résultat nul
    else {
      $result .= "\n <hr/> Aucun résultat trouvé pour <strong> '$query' </strong>";
    }

    // Définition du nombre de studios par page
    $perPage = 60;
    // Calcul du nombre de pages total
    $pages = ceil($nbStudios / $perPage);
    // Calcul du 1er studio de la page
    $first = ($currentPage * $perPage) - $perPage;

    // Requête de sélection des studios
    $sql = 'SELECT * FROM studio
            WHERE name LIKE :query
            ORDER BY name LIMIT :first, :perpage;';
    connexion($dbco);
    $queryPrepared = $dbco->prepare($sql);
    $queryPrepared->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $queryPrepared->bindValue(':first', $first, PDO::PARAM_INT);
    $queryPrepared->bindValue(':perpage', $perPage, PDO::PARAM_INT);
    $queryPrepared->execute();
    $studios = $queryPrepared->fetchAll(PDO::FETCH_ASSOC);
  }
}
?>