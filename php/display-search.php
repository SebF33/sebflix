<!-------------------------------------->
<!-- Page résultat(s) de la recherche -->
<!-------------------------------------->

<?php
// Définition de la page en cours
if (isset($_GET['page']) && !empty($_GET['page'])) {
  $currentPage = (int) strip_tags($_GET['page']);
} else {
  $currentPage = 1;
}

// Appel du script de recherche
require 'search.php';
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title>Résultats de recherche</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Résultats de la recherche">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="../css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/tableau.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/responsive.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/card.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/img/favicon-192.png">

  <!-- Appel des scripts JavaScript -->
  <script src="../js/jquery-3.6.0.min.js"></script>

  <!-- Appel de la police "Truculenta" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta&display=swap" rel="stylesheet">
</head>

<!-- Corps de page -->

<body>

  <!-- Header -->
  <header id="header" role="banner">

    <div id="headerLeft">
    </div>

    <div id="headerMain">
      <!-- Logo du site -->
      <a href="../index.php"><img src="../img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>
    </div>

    <!-- Créer la rubrique contact -->
    <div id="headerRight">
    </div>

  </header>

  <!-- Main content -->
  <main class="main">

    <!-- Bouton retour en arrière -->
    <?php include "../src/exit.html"; ?>

    <!-- Résultats de la recherche -->
    <div class="results">
      <h1><?php echo "\n" . $result ?></h1>
    </div>

    <div id="gallery">
      <div class="gPoster">
        <!-- Affichage des données appelées -->
        <?php
        // Affichage des films
        if ($id == 'movies') {
          foreach ($movies as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row['premiered']);
            echo '<a class="card_button" href="viewpage.php?id=' . $row['idMovie'] . '"><div class="card">
            <div class="front_card" style="background-image: url(../src/thumbnails/' . $row['cachedurl'] . ');"></div>
            <div class="back_card">
              <div>
                <div class="release_date">' . $row['c00'] . ' <span>';
            if (empty($row['premiered'])) {
              echo '';
            } else {
              echo '(' . $date->format("Y") . ')';
            }
            echo '</span></div>
                <div class="media_genres"> ' . $row['c14'] . '</div>
                <div>';
            if (empty($row['c12'])) {
              echo '';
            } else {
              echo '<img src="../src/thumbnails/csa/' . $row['c12'] . '.png" title="' . $row['c12'] . '" alt="' . $row['c12'] . '" height="26" width="26"/>';
            }
            echo '</div>
            <p class="overview">' . $row['c01'] . '</p>
            </div>
            </div>
          </div>
          </a>';
          }
          // Affichage des séries
        } else if ($id == 'tvshows') {
          foreach ($tvshows as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row['c05']);
            echo '<a class="card_button" href="viewpage-show.php?id=' . $row['idShow'] . '"><div class="card">
            <div class="front_card" style="background-image: url(../src/thumbnails/' . $row['cachedurl'] . ');"></div>
            <div class="back_card">
              <div>
                <div class="release_date">' . $row['c00'] . ' <span>';
            if (empty($row['c05'])) {
              echo '';
            } else {
              echo '(' . $date->format("Y") . ')';
            }
            echo '</span></div>
                <div class="media_genres"> ' . $row['c08'] . '</div>
                <div>';
            if (empty($row['c13'])) {
              echo '';
            } else {
              echo '<img src="../src/thumbnails/csa/' . $row['c13'] . '.png" title="' . $row['c13'] . '" alt="' . $row['c13'] . '" height="26" width="26"/>';
            }
            echo '</div>
            <p class="overview">' . $row['c01'] . '</p>
            </div>
            </div>
          </div>
          </a>';
          }
          // Affichage des acteurs
        } else if ($id == 'actors') {
          foreach ($actors as $row) {
            if (empty($row['cachedurl'])) {
              echo '<a class="gCasting" href="display-actor-results.php?id=' . $row['actor_id'] . '"><figure><img src="../src/thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/><figcaption>' . $row['name'] . '</figcaption></figure></a>';
            } else {
              echo '<a class="gCasting" href="display-actor-results.php?id=' . $row['actor_id'] . '"><figure><img src="../src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/><figcaption>' . $row['name'] . '</figcaption></figure></a>';
            }
          }
          // Affichage des studios
        } else if ($id == 'studios') {
          foreach ($studios as $row) {
            echo '<a href="display-studio-results.php?id=' . $row['name'] . '"><img class="gStudio" src="../src/thumbnails/studios/' . $row['name'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="109" width="161"/></a>';
          }
        }
        ?>
      </div>
    </div>

    <?php if ($count >= 1) { ?>
      <!-- Pagination -->
      <div class="pagination-container">
        <ul class="pagination">
          <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
          <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
            <a href="./display-search.php?id=<?= $id ?>&search=<?= $query ?>&page=<?= $currentPage - 1 ?>" class="page-link">«</a>
          </li>
          <?php for ($page = 1; $page <= $pages; $page++) : ?>
            <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
            <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
              <a href="./display-search.php?id=<?= $id ?>&search=<?= $query ?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
            </li>
          <?php endfor ?>
          <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
          <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
            <a href="./display-search.php?id=<?= $id ?>&search=<?= $query ?>&page=<?= $currentPage + 1 ?>" class="page-link">»</a>
          </li>
        </ul>
      </div>
    <?php } ?>

    <!-- Bouton retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="../js/to-top.js"></script>
  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>