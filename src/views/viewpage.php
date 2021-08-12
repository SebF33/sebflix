<!------------------->
<!-- Page du média -->
<!------------------->

<?php
// Initialisation de la session
session_start();

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

  // Requête de vérification de l'existence de l'ID selon le type de média défini
  if ($type == 'movie') {
    $checkid = check_id_movie($id);
  } elseif ($type == 'tvshow') {
    $checkid = check_id_tvshow($id);
  }
  if ($checkid === 0) {
    // Redirection
    header("location:/index.php");
    exit;
  }

  // Vérifications pour la watchlist
  if (isset($_SESSION["logged"]) || $_SESSION["logged"] == TRUE) {
    $user = $_SESSION['id'];
    connexion($dbco);
    try {
      $query = $dbco->prepare(
        "SELECT id FROM watchlist WHERE user_id = :user AND media_id = :id AND media_type = :type"
      );
      $query->bindValue(':user', $user, PDO::PARAM_INT);
      $query->bindValue(':id', $id, PDO::PARAM_INT);
      $query->bindValue(':type', $type, PDO::PARAM_STR);
      $query->execute();
      $checkWatchlist = $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Erreur : " . $e->getMessage();
    }
  }

  // Requêtes pour l'affichage selon le type de média défini
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

  <!-- Appel de GSAP -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&display=swap" rel="stylesheet">

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
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
      // Créer un tableau à 5 colonnes
      echo
      "<table class='table-rating'>
          <tr>
          <th>Note</th>
          <th>CSA</th>
          <th>Pays</th>
          <th>Année</th>
          <th>Fav</th>
          </tr>";
      // Note du média
      if (empty($rating['rating'])) {
        echo '<td class="trating"><img src="../thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée" draggable="false" ondragstart="return false"/></td>';
      } else {
        echo '<td class="trating"><img class="starsRating" src="../thumbnails/rating/' . $rating['rating'] . '.png" title="' . $rating['rating'] . '" alt="" draggable="false" ondragstart="return false"/></td>';
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
      // Favori pour watchlist
      echo '<td class="tfav">';
      if ($checkWatchlist) { ?>
        <a data-id="<?php echo $id; ?>" data-type="<?php echo $type; ?>" data-user="<?php echo $_SESSION['id']; ?>" class="watchlist-btn" onclick="removeFromWatchlist(this)" title="Retirer de la watchlist">
          <?php include "../templates/watch-on.html"; ?>
          <?php include "../templates/watch-off.html"; ?>
        </a>
        <script>
          $("#watch-off").hide();
        </script>
      <?php } else { ?>
        <a data-id="<?php echo $id; ?>" data-type="<?php echo $type; ?>" data-user="<?php echo $_SESSION['id']; ?>" class="watchlist-btn" onclick="addToWatchlist(this)" title="Ajouter à la watchlist">
          <?php include "../templates/watch-off.html"; ?>
          <?php include "../templates/watch-on.html"; ?>
        </a>
        <script>
          $("#watch-on").hide();
        </script>
      <?php } ?>
      </td>
      </tr>
      </table>
    </div>

    <div id="headerMain">
      <!-- Logo du média -->
      <?php
      if (!$logo or empty($logo['cachedurl'])) {
        echo $result->title;
      } else {
        echo '<img class="header-logo" src="../thumbnails/' . $logo['cachedurl'] . '" title="' . $result->title . '" alt="' . $result->title . '" height="124" width="320" draggable="false" ondragstart="return false"/>';
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
        echo '<td class="tdiscart"><div class="discart-container"><img id="discart-img" src="../thumbnails/placeholders/disc.png" alt="" height="180" width="180" draggable="false" ondragstart="return false"/></div></td>';
      } else {
        echo '<td class="tdiscart"><div class="discart-container"><img id="discart-img" src="../thumbnails/' . $discart['cachedurl'] . '" height="180" width="180" draggable="false" ondragstart="return false"/></div></td>';
      }
      echo "</table>";
      ?>
    </div>
    <script src="/js/discart.js"></script>
  </header>

  <!-- Main content -->
  <main>

    <section class="firstPage">
      <div class="mainTop">
        <!-- Bande-annonce -->
        <?php
        if (empty($result->embed)) {
          echo '<img class="embedPlaceholder" src="../thumbnails/placeholders/embed_nr.png" alt="" height="315" width="560" draggable="false" ondragstart="return false"/>';
        } else {
          echo '<iframe width="560" height="315" src="' . $result->embed . '" title="' . $result->title . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        ?>
      </div>
      <div class="mainBottom">
        <?php if ($type == 'movie') { ?>
          <!-- Phrase d'accroche -->
          <div class="catch-container">
            <div class="catch"><i class="fas fa-quote-left fa2"></i>
              <div class="text"><i class="fas fa-quote-right fa1"></i>
                <div>
                  <p><?= $result->catch ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </section>

    <section class="secondPage">
      <div class="mainTop">
        <?php
        // Tableau à 2 colonnes
        echo
        '<table class="table-rows" border="2">
            <tr>
            <th>Synopsis' . str_repeat('&nbsp;', 3) . '<a class="icon-viewpage readandspeech"><i class="fas fa-volume-up"></i></a></th>
            <th>Studio</th>
            </tr>';
        // Synopsis et studio
        if (empty($result->synopsis)) {
          echo '<td class="tsynopsis">Non disponible</td>';
        } else {
          echo '<td class="tsynopsis">' . $result->synopsis . '</td>';
        }
        if (empty($result->studio)) {
          echo '<td class="tstudio"><img src="../thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" width="128.8" height="87.2"/></td>';
        } else {
          echo '<td class="tstudio"><a href="display-results.php?type=achievement&id=' . $result->studio . '"><img src="../thumbnails/studios/' . $result->studio . '" title="' . $result->studio . '" alt="' . $result->studio . '" width="128.8" height="87.2"/></a></td>';
        }
        echo "</tr>";
        echo "</table>";
        ?>
      </div>

      <!-- Appel du script ReadAndSpeech -->
      <script src="/js/readandspeech.js"></script>

      <div class="mainBottom">
        <?php
        // Directrice/directeur
        if (!empty($director)) {
          echo
          "<table class='table-casting' border='2'>
            <tr>
            <th>Direction</th>
            </tr>";
          foreach ($director as $row) {
            if (empty($row['cachedurl'])) {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=direction&id=' . $row['actor_id'] . '"><img src="../thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="" width="144" height="216"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            } else {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=direction&id=' . $row['actor_id'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" width="144" height="216"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            }
          }
        }
        echo "</tr>";
        echo "</table>";

        // Actrices/acteurs/doubleurs
        if (!empty($actors)) {
          echo
          '<table class="table-casting" border="2">
            <tr>
            <th colspan="8">Casting' . str_repeat('&nbsp;', 3) . '<a class="icon-viewpage" href="display-results.php?type=' . $type . 'cast&id=' . $id . '"><i class="fa fa-bars"></i></a></th>
            </tr>';
          foreach ($actors as $row) {
            if (empty($row['cachedurl'])) {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><img src="../thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="" width="144" height="216"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            } else {
              echo '<td class="tcasting"><figure><a href="display-results.php?type=filmography&id=' . $row['actor_id'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" width="144" height="216"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
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

  <script type="text/javascript" src="/assets/js/lib/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="/assets/js/watchlist.js"></script>

</body>

</html>