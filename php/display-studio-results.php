<!----------------->
<!-- Page studio -->
<!----------------->

<?php
// Appeler le script de connexion
require 'connect.php';
?>

<?php
$id = $_GET['id'];

// Requête de sélection MySQL de la table "movie"
$query = $db->prepare(
  "SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE c18 LIKE '$id'
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
    WHERE c14 LIKE '$id'
    AND media_type = 'tvshow'
    AND type = 'poster'
    ORDER BY c05 DESC"
);
$query->execute();
$tvshows = $query->fetchall();
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?php echo $id ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Page studio">

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
      <h1>
        <p></p>
      </h1>
    </div>

    <div id="headerMain">
      <!-- Logo du studio -->
      <?php echo '<a class="logoStudio" href="../index.php"><img src="../src/thumbnails/studios/' . $id . '" title="' . $id . '" alt="' . $id . '" height="109" width="161"/></a>'; ?>
    </div>

    <div id="headerRight">
    </div>

  </header>

  <!-- Main content -->
  <main class="main">

    <!-- Bouton retour en arrière -->
    <?php include "../src/exit.html"; ?>

    <div class="results">
      <h1>Réalisations de <?php echo '' . $id . ''; ?></h1>
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