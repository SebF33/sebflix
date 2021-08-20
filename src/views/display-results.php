<!------------------------>
<!-- Page des résultats -->
<!------------------------>

<?php
// Initialisation de la session
session_start();
// Profil enfant
require dirname(__DIR__) . '/database/child.php';
// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
// Appel du script de validation des données
require dirname(__DIR__) . '/database/validation.php';

// Définition des valeurs autorisées dans le GET
$types = array('movies', 'tvshows', 'actors', 'studios', 'achievement', 'direction', 'filmography', 'genres', 'genre', 'beststudios', 'sets', 'collection', 'moviecast', 'tvshowcast');

// Vérification des GET ('type' obligatoire)
if (isset($_GET['type']) && !empty($_GET['type']) && in_array($_GET['type'], $types)) {
  // Traitement du GET 'type'
  $type = valid_get($_GET['type']);

  // Vérification des GET ('id' optionnel)
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Traitement du GET 'id'
    $id = valid_get($_GET['id']);
    // Requête de vérification de l'existence de l'ID selon le type défini
    if ($type == 'achievement') {
      $checkid = check_id_studio($id);
    } elseif ($type == 'direction' or $type == 'filmography') {
      $checkid = check_id_actor($id);
    } elseif ($type == 'genre') {
      $checkid = check_id_genre($id);
    } elseif ($type == 'collection') {
      $checkid = check_id_set($id);
    } elseif ($type == 'moviecast') {
      $checkid = check_id_movie($id);
    } elseif ($type == 'tvshowcast') {
      $checkid = check_id_tvshow($id);
    }
    if ($checkid === 0) {
      // Redirection
      header("location:/index.php");
      exit;
    }
  }

  // Requêtes et variables selon le type défini :
  // Type "moteur de recherche"
  if ($type == 'movies' or $type == 'tvshows' or $type == 'actors' or $type == 'studios') {
    // Appel du script du moteur de recherche
    require dirname(__DIR__) . '/database/search.php';
    $title = "Résultats de recherche";
    $h1 = $msg_result;
  }
  // Type "réalisations du studio"
  elseif ($type == 'achievement') {
    $movies = select_movie_from_studio($id);
    $tvshows = select_tvshow_from_studio($id);
    $title = $id;
    $h1 = 'Réalisation(s) de ' . $id . '';
  }
  // Type "œuvre(s) de la/du directrice/directeur"
  elseif ($type == 'direction') {
    $actor = select_actor_name($id);
    $movies = select_movie_from_director($id);
    $title = $actor['name'];
    $h1 = 'Œuvres réalisées par ' . $actor['name'] . '';
  }
  // Type "filmographie de l'actrice/acteur/doubleur"
  elseif ($type == 'filmography') {
    $actor = select_actor_name($id);
    $movies = select_movie_from_actor($id);
    $tvshows = select_tvshow_from_actor($id);
    $title = $actor['name'];
    $h1 = 'Filmographie pour ' . $actor['name'] . '';
  }
  // Type "liste des genres pour les films"
  elseif ($type == 'genres') {
    $genres = select_movie_genres();
    $h1 = $title = "Liste des genres pour les films";
  }
  // Type "films selon le genre défini"
  elseif ($type == 'genre') {
    $genre = select_genre_name($id);
    $movies = select_genre_movie($id);
    $h1 = $title = 'Genre "' . $genre['name'] . '"';
  }
  // Type "studios les plus populaires"
  elseif ($type == 'beststudios') {
    $studios = select_best_studios();
    $h1 = $title = "Studios populaires";
  }
  // Type "liste des collections"
  elseif ($type == 'sets') {
    $result = select_all_sets($sqlAndAliasChild);
    $h1 = $title = "Collections";
  }
  // Type "contenu d'une collection"
  elseif ($type == 'collection') {
    $logo = select_logo_collection($id);
    $fanart = select_fanart_collection($id);
    $movies = select_movies_collection($id);
    $tvshows = select_tvshows_collection($id);
    $h1 = $title = 'Collection  ' . $logo['strSet'] . '';
  }
  // Type "casting d'un média type film"
  elseif ($type == 'moviecast') {
    $result = select_infos_movie($id);
    $logo = select_logo_movie($id);
    $fanart = select_fanart_movie($id);
    $actors = select_all_actors_movie($id);
    $h1 = $title = 'Casting pour "' . $result->title . '"';
  }
  // Type "casting d'un média type série"
  elseif ($type == 'tvshowcast') {
    $result = select_infos_tvshow($id);
    $logo = select_logo_tvshow($id);
    $fanart = select_fanart_tvshow($id);
    $actors = select_all_actors_tvshow($id);
    $h1 = $title = 'Casting pour "' . $result->title . '"';
  }
} else {
  // Redirection
  header("location:/index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?= $title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Résultats">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/results.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/card.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/img/favicon-192.png">

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<!-- Corps de page -->

<body>
  <?php
  // Background selon le type défini
  include "../templates/background.php";
  ?>

  <!-- Header -->
  <header role="banner">
    <!-- Logo -->
    <div class="headerLogo">
      <?php
      if ($type == 'achievement') {
        // Logo du studio
        echo '<a class="logoStudio" href="/index.php"><img src="../thumbnails/studios/' . $id . '" title="' . $id . '" alt="' . $id . '" height="109" width="161"/></a>';
      } elseif ($type == 'direction' or $type == 'filmography') {
        // Photo directrice/directeur/actrice/acteur/doubleur
        if (empty($actor['cachedurl'])) {
          echo '<a class="logoCasting" href="/index.php"><img src="../thumbnails/placeholders/casting.png" title="' . $actor['name'] . '" alt="" height="180" width="120"/></a>';
        } else {
          echo '<a class="logoCasting" href="/index.php"><img src="../thumbnails/' . $actor['cachedurl'] . '" title="' . $actor['name'] . '" alt="' . $actor['name'] . '" height="180" width="120"/></a>';
        }
      } elseif ($type == 'genre') {
        // Logo du genre
        echo '<img src="../thumbnails/genres/' . $genre['name'] . '" title="' . $genre['name'] . '" alt="' . $genre['name'] . '" height="161" width="161" draggable="false" ondragstart="return false"/>';
      } elseif ($type == 'collection') {
        // Logo de la collection
        echo '<img src="../thumbnails/' . $logo['cachedurl'] . '" title="' . $logo['strSet'] . '" alt="' . $logo['strSet'] . '" height="124" width="320" draggable="false" ondragstart="return false"/>';
      } elseif ($type == 'moviecast' or $type == 'tvshowcast') {
        // Logo du média
        if (!$logo or empty($logo['cachedurl'])) {
          echo "";
        } else {
          echo '<img src="../thumbnails/' . $logo['cachedurl'] . '" title="' . $result->title . '" alt="' . $result->title . '" height="124" width="320" draggable="false" ondragstart="return false"/>';
        }
      } else {
        // Logo du site
        echo '<a href="/index.php"><img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>';
      }
      ?>
      <!-- En-tête de résultats -->
      <h1 class="results"><?= $h1 ?></h1>
    </div>
  </header>

  <!-- Main content -->
  <main class="main">

    <div id="gallery">
      <div class="gPoster">
        <!-- Affichage des données -->
        <?php
        // Affichage des films
        if (($type == 'movies' or $type == 'genre' or $type == 'direction') && !empty($movies)) {
          foreach ($movies as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row['premiered']);
            echo '<a class="card_button" href="viewpage.php?type=movie&id=' . $row['idMovie'] . '" draggable="false" ondragstart="return false">
            <div class="card">
            <div class="front_card" style="background-image: url(../thumbnails/' . $row['cachedurl'] . ');"></div>
            <div class="back_card">
              <div>
                <div class="release_date">' . $row['title'] . ' <span>';
            if (empty($row['premiered'])) {
              echo '';
            } else {
              echo '(' . $date->format("Y") . ')';
            }
            echo '</span></div>
                <div class="media_genres"> ' . $row['genre'] . '</div>';
            if (empty($row['classification'])) {
              echo '';
            } else {
              echo '<img src="../thumbnails/csa/' . $row['classification'] . '.png" title="' . $row['classification'] . '" alt="' . $row['classification'] . '" height="26" width="26"/>';
            }
            echo '<p class="overview">' . $row['synopsis'] . '</p>
            </div>
            </div>
          </div>
          </a>';
          }
        }
        // Affichage des séries
        elseif (($type == 'tvshows') && !empty($tvshows)) {
          foreach ($tvshows as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row['premiered']);
            echo '<a class="card_button" href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '" draggable="false" ondragstart="return false">
            <div class="card">
            <div class="front_card" style="background-image: url(../thumbnails/' . $row['cachedurl'] . ');"></div>
            <div class="back_card">
              <div>
                <div class="release_date">' . $row['title'] . ' <span>';
            if (empty($row['premiered'])) {
              echo '';
            } else {
              echo '(' . $date->format("Y") . ')';
            }
            echo '</span></div>
                <div class="media_genres"> ' . $row['genre'] . '</div>';
            if (empty($row['classification'])) {
              echo '';
            } else {
              echo '<img src="../thumbnails/csa/' . $row['classification'] . '.png" title="' . $row['classification'] . '" alt="' . $row['classification'] . '" height="26" width="26"/>';
            }
            echo '<p class="overview">' . $row['synopsis'] . '</p>
            </div>
            </div>
          </div>
          </a>';
          }
        }
        // Affichage des acteurs
        elseif (($type == 'actors' or $type == 'moviecast' or $type == 'tvshowcast') && !empty($actors)) {
          foreach ($actors as $row) {
            if (empty($row['cachedurl'])) {
              echo '<a class="gCasting" href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><figure><img src="../thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/><figcaption>' . $row['name'] . '</figcaption></figure></a>';
            } else {
              echo '<a class="gCasting" href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><figure><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/><figcaption>' . $row['name'] . '</figcaption></figure></a>';
            }
          }
        }
        // Affichage des studios
        elseif (($type == 'beststudios' or $type == 'studios') && !empty($studios)) {
          foreach ($studios as $row) {
            echo '<a href="display-results.php?type=achievement&id=' . $row['name'] . '"><img class="gStudio" src="../thumbnails/studios/' . $row['name'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="109" width="161"/></a>';
          }
        }
        // Affichage des réalisations d'un studio
        elseif ($type == 'achievement') {
          foreach ($movies as $row) {
            echo '<a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
          foreach ($tvshows as $row) {
            echo '<a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
        }
        // Affichage d'une filmographie
        elseif ($type == 'filmography') {
          foreach ($movies as $row) {
            echo '<a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
          foreach ($tvshows as $row) {
            echo '<a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
        }
        // Affichage des genres
        elseif ($type == 'genres') {
          foreach ($genres as $row) {
            echo '<a href="display-results.php?type=genre&id=' . $row['genre_id'] . '"><img class="gGenre" src="../thumbnails/genres/' . $row['name'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="125" width="125"/></a>';
          }
        }
        // Affichage des collections
        elseif ($type == 'sets') {
          foreach ($result as $set => $movies) {
            $logo = select_logo_collection($set);
            echo '<a class="card_button" href="display-results.php?type=collection&id=' . $set . '" draggable="false" ondragstart="return false">
            <div class="card">
            <div class="front_card" style="background-image: url(../thumbnails/' . $movies[0]['cachedurl'] . ');"></div>
            <div class="back_card back_collection">';
            echo '<img class="back_collection_logo" src="../thumbnails/' . $logo['cachedurl'] . '" alt="' . $logo['cachedurl'] . '" width="120" height="46.5"/>';
            foreach ($movies as $movie) {
              $date = DateTime::createFromFormat("Y-m-d", $movie['premiered']);
              echo '<div class="release_date">' . $movie['title'] . ' <span>';
              if (empty($movie['premiered'])) {
                echo '';
              } else {
                echo '(' . $date->format("Y") . ')';
              }
              echo '</span></div>';
            }
            echo '</div>
          </div>
          </a>';
          }
        }
        // Affichage du contenu d'une collection
        elseif ($type == 'collection') {
          foreach ($movies as $row) {
            echo '<a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
          foreach ($tvshows as $row) {
            echo '<a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
        }
        ?>
      </div>
    </div>

    <?php
    if ($type == 'movies' or $type == 'tvshows' or $type == 'actors' or $type == 'studios') {
      if ($total_rows > 0) { ?>
        <!-- Pagination -->
        <div class="pagination-container">
          <ul class="pagination-list">
            <?php
            // Lien vers la page précédente et/ou ellipse (sauf si on se trouve sur la 1ère page)
            if ($prev_page > 0) { ?>
              <li class="page-item">
                <a class="page-url" href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $prev_page ?>">«</a>
              </li>
            <?php }
            if ($page_from != 1) { ?>
              <li class="page-item">
                <a class="page-url" href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=1">1</a>
              </li>
              <li class="page-item disabled">
                <a class="page-url">...</a>
              </li>
            <?php }
            // Lien vers chacune des pages (activé si on se trouve sur la page correspondante)
            for ($p = $page_from; $p <= $page_to; $p++) { ?>
              <li class="page-item <?= ($current_page == $p) ? "active" : "" ?>">
                <a class="page-url" href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $p ?>"><?= $p ?></a>
              </li>
            <?php }
            // Lien vers la page suivante et/ou ellipse (sauf si on se trouve sur la dernière page)
            if ($page_to != $total_pages) { ?>
              <li class="page-item disabled">
                <a class="page-url">...</a>
              </li>
              <li class="page-item">
                <a class="page-url" href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $total_pages ?>"><?= $total_pages ?></a>
              </li>
            <?php }
            if ($next_page > 0) { ?>
              <li class="page-item">
                <a class="page-url" href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $next_page ?>">»</a>
              </li>
            <?php } ?>
          </ul>
        </div>
    <?php }
    } ?>

    <!-- Menu circulaire -->
    <?php include "../templates/menu.html"; ?>

    <!-- Bouton retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="/js/to-top.js"></script>

  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>