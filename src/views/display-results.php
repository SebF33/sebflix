<!------------------------>
<!-- Page des résultats -->
<!------------------------>

<?php
if ($_GET['type'] == 'movies' or $_GET['type'] == 'tvshows' or $_GET['type'] == 'actors' or $_GET['type'] == 'studios') {
  // Définition de la page en cours
  if (isset($_GET['page']) && !empty($_GET['page'])) {
    $currentPage = (int) strip_tags($_GET['page']);
  } else {
    $currentPage = 1;
  }
}
?>

<?php
// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
// Appel du script de validation des données
require dirname(__DIR__) . '/database/validation.php';

// Définition des valeurs autorisées dans les GET
$types = array('movies', 'tvshows', 'actors', 'studios', 'achievement', 'direction', 'filmography', 'genres', 'genre', 'beststudios', 'sets', 'collection');

// Vérification des GET ('type' obligatoire)
if (isset($_GET['type']) && !empty($_GET['type']) && in_array($_GET['type'], $types)) {
  // Traitement du GET 'type'
  $type = valid_get($_GET['type']);

  // Vérification des GET ('id' optionnel)
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Traitement du GET 'id'
    $id = valid_get($_GET['id']);
  }

  // Requêtes et variables selon le type défini :

  // Type "moteur de recherche"
  if ($type == 'movies' or $type == 'tvshows' or $type == 'actors' or $type == 'studios') {
    // Appel du script du moteur de recherche
    require dirname(__DIR__) . '/database/search.php';
    $title = "Résultats de recherche";
    $h1 = $result;
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
    $result = select_all_sets();
    $h1 = $title = "Collections";
  }
  // Type "contenu d'une collection"
  elseif ($type == 'collection') {
    $logo = select_logo_collection($id);
    $fanart = select_fanart_collection($id);
    $result = select_movies_collection($id);
    $h1 = $title = 'Collection  ' . $logo['strSet'] . '';
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
  <link rel="stylesheet" href="/css/table.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/card.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/img/favicon-192.png">

  <!-- Appel de jQuery -->
  <script src="/js/jquery-3.6.0.min.js"></script>

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">

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
  <header id="header" role="banner">

    <div id="headerLeft">
    </div>

    <div id="headerMain">
      <!-- Logo -->
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
        echo '<img src="../thumbnails/genres/' . $genre['name'] . '" title="' . $genre['name'] . '" alt="' . $genre['name'] . '" height="161" width="161"/>';
      } elseif ($type == 'collection') {
        // Logo de la collection
        echo '<img src="../thumbnails/' . $logo['cachedurl'] . '" title="' . $logo['strSet'] . '" alt="' . $logo['strSet'] . '" height="124" width="320"/>';
      } else {
        // Logo du site
        echo '<a href="/index.php"><img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>';
      }
      ?>
    </div>

    <div id="headerRight">
    </div>

  </header>

  <!-- Main content -->
  <main class="main">

    <div class="results">
      <h1><?= $h1 ?></h1>
    </div>

    <div id="gallery">
      <div class="gPoster">
        <!-- Affichage des données -->
        <?php
        // Affichage des films
        if ($type == 'movies' or $type == 'genre' or $type == 'direction') {
          foreach ($movies as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row['premiered']);
            echo '<a class="card_button" href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><div class="card">
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
                <div class="media_genres"> ' . $row['genre'] . '</div>
                <div>';
            if (empty($row['classification'])) {
              echo '';
            } else {
              echo '<img src="../thumbnails/csa/' . $row['classification'] . '.png" title="' . $row['classification'] . '" alt="' . $row['classification'] . '" height="26" width="26"/>';
            }
            echo '</div>
            <p class="overview">' . $row['synopsis'] . '</p>
            </div>
            </div>
          </div>
          </a>';
          }
        }
        // Affichage des séries
        elseif ($type == 'tvshows') {
          foreach ($tvshows as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row['premiered']);
            echo '<a class="card_button" href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><div class="card">
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
                <div class="media_genres"> ' . $row['genre'] . '</div>
                <div>';
            if (empty($row['classification'])) {
              echo '';
            } else {
              echo '<img src="../thumbnails/csa/' . $row['classification'] . '.png" title="' . $row['classification'] . '" alt="' . $row['classification'] . '" height="26" width="26"/>';
            }
            echo '</div>
            <p class="overview">' . $row['synopsis'] . '</p>
            </div>
            </div>
          </div>
          </a>';
          }
        }
        // Affichage des acteurs
        elseif ($type == 'actors') {
          foreach ($actors as $row) {
            if (empty($row['cachedurl'])) {
              echo '<a class="gCasting" href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><figure><img src="../thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/><figcaption>' . $row['name'] . '</figcaption></figure></a>';
            } else {
              echo '<a class="gCasting" href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><figure><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/><figcaption>' . $row['name'] . '</figcaption></figure></a>';
            }
          }
        }
        // Affichage des studios
        elseif ($type == 'beststudios' or $type == 'studios') {
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
          foreach ($result as $row) {
            echo '<a href="display-results.php?type=collection&id=' . $row['idSet'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['strSet'] . '" height="360" width="240"/></a>';
          }
        }
        // Affichage du contenu d'une collection
        elseif ($type == 'collection') {
          foreach ($result as $row) {
            echo '<a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img class="gMedia" src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="360" width="240"/></a>';
          }
        }
        ?>
      </div>
    </div>

    <?php
    if ($type == 'movies' or $type == 'tvshows' or $type == 'actors' or $type == 'studios') {
      if ($count >= 1) { ?>
        <!-- Pagination -->
        <div class="pagination-container">
          <ul class="pagination">
            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
            <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
              <a href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $currentPage - 1 ?>" class="page-link">«</a>
            </li>
            <?php
            for ($page = 1; $page <= $pages; $page++) : ?>
              <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
              <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                <a href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
              </li>
            <?php endfor ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
              <a href="./display-results.php?type=<?= $type ?>&search=<?= $query ?>&page=<?= $currentPage + 1 ?>" class="page-link">»</a>
            </li>
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