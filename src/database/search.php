<?php
/////////////////////////
// Moteur de recherche //
/////////////////////////

// Initialisation des variables
$result = $studios = $actors = $tvshows = $movies = $msg_result = "";

// Déclaration et nettoyage de la variable d'entrée
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $query = htmlspecialchars($_GET['search']);
  $query = trim($query);
}

// Définition de la page en cours
if (isset($_GET['page']) && !empty($_GET['page'])) {
  $current_page = (int) strip_tags($_GET['page']);
} else {
  $current_page = 1;
}

// Définition du nombre de résultats par page
$max_rows = 30;
// Valeurs par défaut pour la pagination
$total_rows = 0;
$total_pages = 1;
$prev_page = 0;
$next_page = 0;
$page_from = 1;
$page_to = 0;

if (isset($query) && !empty($query)) {
  // Traitement de l'option "films" choisie par l'utilisateur pour la requête de comptage
  if (isset($type) && $type == 'movies') {
    $sqlCount = "SELECT COUNT(*) FROM movie WHERE title LIKE :query";
  }
  // Traitement de l'option "séries" choisie par l'utilisateur pour la requête de comptage
  elseif (isset($type) && $type == 'tvshows') {
    $sqlCount = "SELECT COUNT(*) FROM tvshow WHERE title LIKE :query";
  }
  // Traitement de l'option "acteurs" choisie par l'utilisateur pour la requête de comptage
  elseif (isset($type) && $type == 'actors') {
    $sqlCount = "SELECT COUNT(*) FROM actor WHERE name LIKE :query";
  }
  // Traitement de l'option "studios" choisie par l'utilisateur pour la requête de comptage
  elseif (isset($type) && $type == 'studios') {
    $sqlCount = "SELECT COUNT(*) FROM studio WHERE name LIKE :query";
  }
  // Traitement pour la requête de comptage du CRUD
  if (substr($_SERVER['PHP_SELF'], -8) == 'crud.php') {
    $sqlCount = "SELECT COUNT(*) FROM movie WHERE title LIKE :query";
  }

  // Requête de comptage
  connexion($dbco);
  $stmt = $dbco->prepare($sqlCount);
  $stmt->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
  $stmt->execute();
  // Comptage des résultats
  $count = $stmt->fetchColumn();
  $total_rows = (int) $count;

  // Affichage du résultat non nul
  if ($total_rows >= 1) {
    $msg_result .= "$count résultat(s) trouvé(s) pour <strong> '$query' </strong>";
  }
  // Affichage du résultat nul
  else {
    $msg_result .= "\n <hr/> Aucun résultat trouvé pour <strong> '$query' </strong>";
  }

  if ($total_rows > 0) {
    // Calcul du nombre de pages total
    $total_pages = ceil($total_rows / $max_rows);
    // Re-définition de la page en cours si elle est plus élevée que le nombre de pages total
    if ($current_page > $total_pages) $current_page = $total_pages;
    // Calcul du 1er résultat de la page
    $db_start_row = ($current_page * $max_rows) - $max_rows;

    // Traitement de l'option "films" choisie par l'utilisateur pour la requête de sélection
    if (isset($type) && $type == 'movies') {
      $sqlSelect = "SELECT * FROM movie
                INNER JOIN art ON movie.idMovie = art.media_id
                WHERE title LIKE :query AND media_type = 'movie' AND type = 'poster'
                ORDER BY premiered DESC LIMIT :first, :perpage";
    }
    // Traitement de l'option "séries" choisie par l'utilisateur pour la requête de sélection
    elseif (isset($type) && $type == 'tvshows') {
      $sqlSelect = "SELECT * FROM tvshow
                INNER JOIN art ON tvshow.idShow = art.media_id
                WHERE title LIKE :query AND media_type = 'tvshow' AND type = 'poster'
                ORDER BY premiered DESC LIMIT :first, :perpage";
    }
    // Traitement de l'option "acteurs" choisie par l'utilisateur pour la requête de sélection
    elseif (isset($type) && $type == 'actors') {
      $sqlSelect = "SELECT * FROM actor
                WHERE name LIKE :query
                ORDER BY name LIMIT :first, :perpage";
    }
    // Traitement de l'option "studios" choisie par l'utilisateur pour la requête de sélection
    elseif (isset($type) && $type == 'studios') {
      $sqlSelect = "SELECT * FROM studio
                WHERE name LIKE :query
                ORDER BY name LIMIT :first, :perpage";
    }
    // Traitement pour la requête de sélection du CRUD
    if (substr($_SERVER['PHP_SELF'], -8) == 'crud.php') {
      $sqlSelect = "SELECT idMovie, title, synopsis, premiered, cachedurl
                FROM movie
                INNER JOIN art ON movie.idMovie = art.media_id
                WHERE title LIKE :query AND media_type = 'movie' AND type = 'poster'
                ORDER BY idMovie DESC LIMIT :first, :perpage";
    }

    // Requête de sélection
    connexion($dbco);
    $stmt = $dbco->prepare($sqlSelect);
    $stmt->bindValue(':query', ('%' . $query . '%'), PDO::PARAM_STR);
    $stmt->bindValue(':first', $db_start_row, PDO::PARAM_INT);
    $stmt->bindValue(':perpage', $max_rows, PDO::PARAM_INT);
    $stmt->execute();
    if (isset($type) && $type == 'movies') {
      $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($type) && $type == 'tvshows') {
      $tvshows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($type) && $type == 'actors') {
      $actors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif (isset($type) && $type == 'studios') {
      $studios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    if (substr($_SERVER['PHP_SELF'], -8) == 'crud.php') {
      $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Définition des variables pour contrôler les numéros de page affichés
    $page_to = $total_pages;
    if ($current_page > 1) $prev_page = $current_page - 1;
    if ($total_pages > $current_page) $next_page = $current_page + 1;
    if ($total_pages > 5) {
      if (($current_page - 2) > 1) $page_from = $current_page - 2;
      if (($current_page + 2) < $total_pages) $page_to = $current_page + 2;
    }
  }
}
