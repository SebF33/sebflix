<!------------------->
<!-- Page du média -->
<!------------------->

<?php
// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
// Appel du script de validation des données
require dirname(__DIR__) . '/database/validation.php';

// Vérification des GET
if (isset($_GET['id']) && !empty($_GET['id']) && ($_GET['type'] == 'movie' or $_GET['type'] == 'tvshow')) {
  // Récupération et validation de l'ID du média
  $id = valid_get($_GET['id']);
  // Récupération et validation du type du média
  $type = valid_get($_GET['type']);
  // Requêtes selon le type de média défini
  if ($type == 'movie') {
    $result = select_infos_movie($id);
    $logo = select_logo_movie($id);
    $rating = select_rating_movie($id);
    $discart = select_discart_movie($id);
    $fanart = select_fanart_movie($id);
    $director = select_director_movie($id);
    $actors = select_actors_movie($id);
  } elseif ($type == 'tvshow') {
    $result = select_infos_tvshow($id);
    $logo = select_logo_tvshow($id);
    $rating = select_rating_tvshow($id);
    $fanart = select_fanart_tvshow($id);
    $actors = select_actors_tvshow($id);
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
  <title><?= $result->title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Détails du média">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/viewpage.css" type="text/css" media="screen">
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<!-- Corps de page -->

<body>

  <!-- Fanart du média -->
  <style type="text/css">
    body {
      background-image: url(<?php if (empty($fanart['cachedurl'])) {
                              echo '"/img/bg.png"';
                            } else {
                              echo '"../thumbnails/' . $fanart['cachedurl'] . '"';
                            }

                            ?>);
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <!-- Header -->
  <header id="headerViewpage" role="banner">
    <div id="headerLeft">
      <?php
      // Créer un tableau à 4 colonnes
      echo
      "<table class='table-rating'>
          <tr>
          <th>Note</th>
          <th>CSA</th>
          <th>Pays</th>
          <th>Année</th>
          </tr>";
      // Note du média
      if (empty($rating['rating'])) {
        echo '<td class="trating"><img src="../thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée"/></td>';
      } else {
        echo '<td class="trating"><img class="starsRating" src="../thumbnails/rating/' . $rating['rating'] . '.png" title="' . $rating['rating'] . '" alt=""/></td>';
      }
      // Classification
      if (empty($result->classification)) {
        echo '<td class="tcsa"><img src="../thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée"/></td>';
      } else {
        echo '<td class="tcsa"><img src="../thumbnails/csa/' . $result->classification . '.png" title="' . $result->classification . '" alt="' . $result->classification . '"/></td>';
      }
      // Pays
      if (empty($result->country)) {
        echo '<td class="tcountry"><img src="../thumbnails/placeholders/nr.png" title="Non renseigné" alt="Non renseigné"/></td>';
      } else {
        echo '<td class="tcountry"><img src="../thumbnails/country/' . $result->country . '.png" title="' . $result->country . '" alt="' . $result->country . '"/></td>';
      }
      // Date de sortie
      $date = DateTime::createFromFormat("Y-m-d", $result->premiered);
      if (empty($result->premiered)) {
        echo '<td class="tpremiered"><img src="../thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée"/></td>';
      } else {
        echo '<td class="tpremiered">' . $date->format("Y") . '</td>';
      }
      echo "</tr>";
      echo "</table>";
      ?>
    </div>

    <div id="headerMain">
      <!-- Logo du média -->
      <?php
      if (!$logo or empty($logo['cachedurl'])) {
        echo $result->title;
      } else {
        echo '<img src="../thumbnails/' . $logo['cachedurl'] . '" title="' . $result->title . '" alt="' . $result->title . '" height="124" width="320"/>';
      }
      ?>
    </div>

    <div id="headerRight">
      <?php
      // Créer un tableau à 1 cellule
      echo
      "<table class='table-discart' border='6'>";
      // Discart du média
      if (empty($discart['cachedurl'])) {
        echo '<td class="tdiscart"><img src="../thumbnails/placeholders/disc.png" alt="" height="180" width="180"/></td>';
      } else {
        echo '<td class="tdiscart"><img src="../thumbnails/' . $discart['cachedurl'] . '" height="180" width="180"/></td>';
      }
      echo "</table>";
      ?>
    </div>
  </header>

  <!-- Main content -->
  <main>

    <section class="firstPage">
      <div class="mainTop">
        <!-- Bande-annonce -->
        <?php
        if (empty($result->embed)) {
          echo '<img class="embedPlaceholder" src="../thumbnails/placeholders/embed_nr.png" alt="" height="315" width="560"/>';
        } else {
          echo '<iframe width="560" height="315" src="' . $result->embed . '" title="' . $result->title . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        ?>
      </div>
      <div class="mainBottom">
        <?php if ($type == 'movie') { ?>
          <!-- Phrase d'accroche -->
        <?php echo '<div class="catch"><p>"' . $result->catch . '"</p></div>';
        }
        ?>
      </div>
    </section>

    <section class="secondPage">
      <div class="mainTop">
        <?php
        // Créer un tableau à 2 colonnes
        echo
        '<table class="table-rows" border="2">
            <tr>
            <th>Synopsis' . str_repeat('&nbsp;', 3) . '<a class="readandspeech"><i class="fas fa-volume-up"></i></i></a></th>
            <th>Studio</th>
            </tr>';
        // Synopsis et studio
        if (empty($result->synopsis)) {
          echo '<td class="tsynopsis">Non disponible</td>';
        } else {
          echo '<td class="tsynopsis">' . $result->synopsis . '</td>';
        }
        if (empty($result->studio)) {
          echo '<td class="tstudio"><img src="../thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
        } else {
          echo '<td class="tstudio"><a href="display-results.php?type=achievement&id=' . $result->studio . '"><img src="../thumbnails/studios/' . $result->studio . '" title="' . $result->studio . '" alt="' . $result->studio . '" height="109" width="161"/></a></td>';
        }
        echo "</tr>";
        echo "</table>";
        ?>
      </div>

      <!-- Appel du script ReadAndSpeech -->
      <script src="/js/readandspeech.js"></script>

      <div class="mainBottom">
        <?php
        // Directrice(s)/directeur(s)
        if (!empty($director)) {
          echo
          "<table class='table-casting' border='2'>
            <tr>
            </tr>";
          foreach ($director as $row) {
            if (empty($row['cachedurl'])) {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=direction&id=' . $row['actor_id'] . '"><img src="../thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="" height="288" width="192"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            } else {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=direction&id=' . $row['actor_id'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            }
          }
        }
        echo "</tr>";
        echo "</table>";

        // Actrices/acteurs/doubleurs
        if (!empty($actors)) {
          echo
          "<table class='table-casting' border='2'>
            <tr>
            </tr>";
          foreach ($actors as $row) {
            if (empty($row['cachedurl'])) {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><img src="../thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="" height="288" width="192"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            } else {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            }
          }
        }
        echo "</tr>";
        echo "</table>";
        ?>
      </div>
    </section>

    <!-- Menu circulaire -->
    <?php include "../templates/menu.html"; ?>

    <!-- Flèche retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="/js/to-top.js"></script>

  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>