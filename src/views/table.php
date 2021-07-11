<!--------------------->
<!-- Page du tableau -->
<!--------------------->

<?php
// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
// Appel du script de validation des données
require dirname(__DIR__) . '/database/validation.php';

// Définition des valeurs autorisées dans les GET
$categories = array('movies', 'tvshows', 'animes', 'animation', 'spectacles');
$genres = array('comedy', 'thriller', 'sf', 'horror', 'war', 'peplum', 'western', 'martial', 'animation', 'shonen', 'seinen', 'familial', 'short', 'man', 'woman');
$alphabet = array('numeric', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

// Vérification des GET ('category' obligatoire et soit 'genre' soit 'letter')
if (isset($_GET['category']) && !empty($_GET['category']) && in_array($_GET['category'], $categories) && ((isset($_GET['genre']) && !empty($_GET['genre']) && in_array($_GET['genre'], $genres)) || (isset($_GET['letter']) && !empty($_GET['letter']) && in_array($_GET['letter'], $alphabet)))) {

  // Traitement du GET 'category'
  $category = valid_get($_GET['category']);

  // Traitement du GET 'genre'
  if (isset($_GET['genre']) && !empty($_GET['genre'])) {
    $genre = valid_get($_GET['genre']);
    if ($genre == 'comedy') {
      $genre = "Comédie";
    } elseif ($genre == 'thriller') {
      $genre = "Thriller";
    } elseif ($genre == 'sf') {
      $genre = "Science-Fiction";
    } elseif ($genre == 'horror') {
      $genre = "Horreur";
    } elseif ($genre == 'war') {
      $genre = "Guerre";
    } elseif ($genre == 'peplum') {
      $genre = "Péplum";
    } elseif ($genre == 'western') {
      $genre = "Western";
    } elseif ($genre == 'martial') {
      $genre = "Arts Martiaux";
    } elseif ($genre == 'animation') {
      $genre = "Animation";
    } elseif ($genre == 'shonen') {
      $genre = "Shōnen";
    } elseif ($genre == 'seinen') {
      $genre = "Seinen";
    } elseif ($genre == 'familial') {
      $genre = "Familial";
    } elseif ($genre == 'short') {
      $genre = "Court-métrage";
    } elseif ($genre == 'man') {
      $genre = "One-man-show";
    } elseif ($genre == 'woman') {
      $genre = "One-woman-show";
    }
    // Titre et requête de sélection selon le genre défini
    if ($category == 'movies') {
      $title = "Films";
      $result = select_movie_by_genre($genre);
    } elseif ($category == 'tvshows') {
      $title = "Séries";
      $result = select_tvshow_by_genre($genre);
    } elseif ($category == 'animes' and $genre == 'Shōnen') {
      $title = "Animes";
      $result = select_shonen_tvshow();
      $result2 = select_shonen_movie();
    } elseif ($category == 'animes' and $genre == 'Seinen') {
      $title = "Animes";
      $result = select_seinen_tvshow();
      $result2 = select_seinen_movie();
    } elseif ($category == 'animation') {
      $title = "Dessins animés";
      $result = select_animation_by_genre($genre);
    } elseif ($category == 'spectacles') {
      $title = "Spectacles";
      $result = select_spectacle_by_genre($genre);
    }
  }

  // Traitement du GET 'letter'
  if (isset($_GET['letter']) && !empty($_GET['letter'])) {
    $letter = valid_get($_GET['letter']);
    // Titre et requête de sélection selon la lettre définie
    if ($category == 'movies') {
      $title = "Films";
      if ($letter == 'numeric') {
        $result = select_movie_by_numeric();
        $letter = "0 à 9";
      } else {
        $result = select_movie_by_letter($letter);
      }
    } elseif ($category == 'tvshows') {
      $title = "Séries";
      if ($letter == 'numeric') {
        $result = select_tvshow_by_numeric();
        $letter = "0 à 9";
      } else {
        $result = select_tvshow_by_letter($letter);
      }
    } elseif ($category == 'animes') {
      $title = "Animes";
      if ($letter == 'numeric') {
        $result = select_anime_tvshow_by_numeric();
        $result2 = select_anime_movie_by_numeric();
        $letter = "0 à 9";
      } else {
        $result = select_anime_tvshow_by_letter($letter);
        $result2 = select_anime_movie_by_letter($letter);
      }
    } elseif ($category == 'animation') {
      $title = "Dessins animés";
      if ($letter == 'numeric') {
        $result = select_animation_by_numeric();
        $letter = "0 à 9";
      } else {
        $result = select_animation_by_letter($letter);
      }
    } elseif ($category == 'spectacles') {
      $title = "Spectacles";
      if ($letter == 'numeric') {
        $result = select_spectacle_by_numeric();
        $letter = "0 à 9";
      } else {
        $result = select_spectacle_by_letter($letter);
      }
    }
  }
} else {
  // Redirection si un GET n'est pas vérifié
  header("location:/index.php");
  exit;
}
// Redirection vers la page 404 si le résultat de la requête est nul
if (empty($result) && empty($result2)) {
  header("HTTP/1.0 404 Not Found");
  include_once("404.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?php if (isset($genre)) {
            echo '' . $title . ' genre ' . mb_strtolower($genre, 'UTF-8') . '';
          } elseif (isset($letter)) {
            echo '' . $title . ' en \'' . strtoupper($letter) . '\'';
          } ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Médias catégorisés">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/tableau.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/img/favicon-192.png">

  <!-- Appel des scripts JavaScript -->
  <script src="/js/jquery-3.6.0.min.js"></script>

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">
</head>

<!-- Corps de page -->

<body>

  <!-- Header -->
  <header id="header" role="banner">

    <div id="headerLeft">
      <h1>
        <p></p>
      </h1>
    </div>

    <div id="headerMain">
      <!-- Logo du site -->
      <a href="/index.php"><img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>
    </div>

    <!-- Créer la rubrique contact -->
    <div id="headerRight">
    </div>

  </header>

  <!-- Main content -->
  <main class="main">

    <!-- Bouton retour en arrière -->
    <?php include "../templates/exit.html"; ?>

    <?php
    // Créer un tableau à X colonnes
    echo
    "<table border='4'>
        <tr>
        <th>Affiche</th>
        <th>Nom</th>
        <th>Synopsis</th>
        <th>Studio</th>
        </tr>";
    // Afficher dans le tableau les données appelées
    if ($category == 'movies' or $category == 'animation' or $category == 'spectacles') {
      foreach ($result as $row) {
        echo "<tr>";
        echo '<td class="tposter"><a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="240" width="160"/></a></td>';
        echo '<td class="tname">' . $row['title'] . '</td>';
        echo '<td class="tsynopsis"><div class="max-lines">' . $row['synopsis'] . '</div></td>';
        if (empty($row['studio'])) {
          echo '<td class="tstudio"><img src="../thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
        } else {
          echo '<td class="tstudio"><a href="display-results.php?type=achievement&id=' . $row['studio'] . '"><img src="../thumbnails/studios/' . $row['studio'] . '" title="' . $row['studio'] . '" alt="' . $row['studio'] . '" height="109" width="161"/></a></td>';
        }
      }
    } elseif ($category == 'tvshows') {
      foreach ($result as $row) {
        echo "<tr>";
        echo '<td class="tposter"><a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="240" width="160"/></a></td>';
        echo '<td class="tname">' . $row['title'] . '</td>';
        echo '<td class="tsynopsis"><div class="max-lines">' . $row['synopsis'] . '</div></td>';
        if (empty($row['studio'])) {
          echo '<td class="tstudio"><img src="../thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
        } else {
          echo '<td class="tstudio"><a href="display-results.php?type=achievement&id=' . $row['studio'] . '"><img src="../thumbnails/studios/' . $row['studio'] . '" title="' . $row['studio'] . '" alt="' . $row['studio'] . '" height="109" width="161"/></a></td>';
        }
      }
    } elseif ($category == 'animes') {
      foreach ($result as $row) {
        echo "<tr>";
        echo '<td class="tposter"><a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="240" width="160"/></a></td>';
        echo '<td class="tname">' . $row['title'] . '</td>';
        echo '<td class="tsynopsis"><div class="max-lines">' . $row['synopsis'] . '</div></td>';
        if (empty($row['studio'])) {
          echo '<td class="tstudio"><img src="../thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
        } else {
          echo '<td class="tstudio"><a href="display-results.php?type=achievement&id=' . $row['studio'] . '"><img src="../thumbnails/studios/' . $row['studio'] . '" title="' . $row['studio'] . '" alt="' . $row['studio'] . '" height="109" width="161"/></a></td>';
        }
      }
      foreach ($result2 as $row) {
        echo "<tr>";
        echo '<td class="tposter"><a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="240" width="160"/></a></td>';
        echo '<td class="tname">' . $row['title'] . '</td>';
        echo '<td class="tsynopsis"><div class="max-lines">' . $row['synopsis'] . '</div></td>';
        if (empty($row['studio'])) {
          echo '<td class="tstudio"><img src="../thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
        } else {
          echo '<td class="tstudio"><a href="display-results.php?type=achievement&id=' . $row['studio'] . '"><img src="../thumbnails/studios/' . $row['studio'] . '" title="' . $row['studio'] . '" alt="' . $row['studio'] . '" height="109" width="161"/></a></td>';
        }
      }
    }
    echo "</tr>";
    echo "</table>";
    ?>

    <!-- Flèche retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="/js/to-top.js"></script>

  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>