<!----------------------------------------------->
<!-- Page filmographie actrice/acteur/doubleur -->
<!----------------------------------------------->

<?php
// Appeler le script de connexion
require 'connect.php';
?>

<?php
$id = $_GET['id'];

// Requête de sélection MySQL de la table "actor"
$stmt = $db->prepare(
  "SELECT *
    FROM actor
    WHERE actor_id = $id"
);
$stmt->execute();
$actor = $stmt->fetch();

// Requête de sélection MySQL de la table "movie"
$query = $db->prepare(
  "SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    INNER JOIN actor_link_movie ON art.media_id = actor_link_movie.media_id
    WHERE actor_id = $id
    AND media_type = 'movie'
    AND type = 'poster'
    ORDER BY premiered DESC"
);
$query->execute();
$movies = $query->fetchall();

// Requête de sélection MySQL de la table "tvshow"
$query = $db->prepare(
  "SELECT *
    FROM tvshow
    INNER JOIN art ON tvshow.idShow = art.media_id
    INNER JOIN actor_link_tvshow ON art.media_id = actor_link_tvshow.media_id
    WHERE actor_id = $id
    AND media_type = 'tvshow'
    AND type = 'poster'
    ORDER BY c05 DESC"
);
$query->execute();
$tvshows = $query->fetchall();

// Redirection vers la page 404 si le résultat de la requête est nul
if (empty($movies) and empty($tvshows)) {
  header("HTTP/1.0 404 Not Found");
  include_once("../src/not-found.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?php echo $actor['name'] ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Page filmographie">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="../css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/tableau.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="../img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="../img/favicon-192.png">

  <!-- Appel des scripts JavaScript -->
  <script src="../js/jquery-3.6.0.min.js"></script>

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
    </div>

    <div id="headerMain">
      <!-- Photo actrice/acteur/doubleur -->
      <?php
      if (empty($actor['cachedurl'])) {
        echo '<a class="logoCasting" href="../index.php"><img src="../src/thumbnails/placeholders/casting.png" title="' . $actor['name'] . '" alt="" height="180" width="120"/></a>';
      } else {
        echo '<a class="logoCasting" href="../index.php"><img src="../src/thumbnails/' . $actor['cachedurl'] . '" title="' . $actor['name'] . '" alt="' . $actor['name'] . '" height="180" width="120"/></a>';
      }
      ?>
    </div>

    <div id="headerRight">
    </div>

  </header>

  <!-- Main content -->
  <main class="main">

    <!-- Bouton retour en arrière -->
    <?php include "../src/exit.html"; ?>

    <div class="results">
      <h1>Filmographie pour <?php echo '' . $actor['name'] . ''; ?></h1>
    </div>

    <div id="gallery">
      <div class="gPoster">
        <!-- Affichage des posters des données appelées -->
        <?php
        foreach ($movies as $row) {
          echo '<a href="viewpage.php?id=' . $row['idMovie'] . '"><img class="gMedia" src="../src/thumbnails/' . $row['cachedurl'] . '" alt="' . $row['c00'] . '" height="360" width="240"/></a>';
        }
        foreach ($tvshows as $row) {
          echo '<a href="viewpage-show.php?id=' . $row['idShow'] . '"><img class="gMedia" src="../src/thumbnails/' . $row['cachedurl'] . '" alt="' . $row['c00'] . '" height="360" width="240"/></a>';
        }
        ?>
      </div>
    </div>

    <!-- Flèche retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="../js/to-top.js"></script>

  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>