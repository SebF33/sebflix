<!----------------------->
<!-- Rubrique "Shōnen" -->
<!----------------------->

<?php
// Appeler le script de connexion
require '../connect.php';
?>

<?php
// Appeler les données des animes type série
$query = $db->prepare(
  "SELECT *
    FROM tvshow
    INNER JOIN art ON tvshow.idShow = art.media_id
    WHERE media_type = 'tvshow'
    AND type = 'poster'
    AND c08 LIKE '%Anime%'
    AND c13 NOT LIKE '%Rated R%'
    AND c13 NOT LIKE '%Rated NC-17%'
    ORDER BY c00"
);
$query->execute();
$result1 = $query->fetchall();

// Appeler les données des animes type film
$query = $db->prepare(
  "SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE media_type = 'movie'
    AND type = 'poster'
    AND c14 LIKE '%Anime%'
    AND c12 NOT LIKE '%Rated R%'
    AND c12 NOT LIKE '%Rated NC-17%'
    ORDER BY c00"
);
$query->execute();
$result2 = $query->fetchall();
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title>Animes shōnen</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Shōnen">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="../../css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../../css/tableau.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="../../img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="../../img/favicon-192.png">

  <!-- Appel des scripts JavaScript -->
  <script src="../../js/jquery-3.6.0.min.js"></script>

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
      <a href="../../index.php"><img src="../../img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>
    </div>

    <!-- Créer la rubrique contact -->
    <div id="headerRight">
    </div>

  </header>

  <!-- Main content -->
  <main class="main">

    <!-- Bouton retour en arrière -->
    <?php include "../../src/exit.html"; ?>

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
    foreach ($result1 as $row) {
      echo "<tr>";
      echo '<td class="tposter"><a href="../viewpage-show.php?id=' . $row['idShow'] . '"><img src="../../src/thumbnails/' . $row['cachedurl'] . '" alt="' . $row['c00'] . '" height="240" width="160"/></a></td>';
      echo '<td class="tname">' . $row['c00'] . '</td>';
      echo '<td class="tsynopsis"><div class="max-lines">' . $row['c01'] . '</div></td>';
      if (empty($row['c14'])) {
        echo '<td class="tstudio"><img src="../../src/thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
      } else {
        echo '<td class="tstudio"><a href="../display-studio-results.php?id=' . $row['c14'] . '"><img src="../../src/thumbnails/studios/' . $row['c14'] . '" title="' . $row['c14'] . '" alt="' . $row['c14'] . '" height="109" width="161"/></a></td>';
      }
    }
    foreach ($result2 as $row) {
      echo "<tr>";
      echo '<td class="tposter"><a href="../viewpage.php?id=' . $row['idMovie'] . '"><img src="../../src/thumbnails/' . $row['cachedurl'] . '" alt="' . $row['c00'] . '" height="240" width="160"/></a></td>';
      echo '<td class="tname">' . $row['c00'] . '</td>';
      echo '<td class="tsynopsis"><div class="max-lines">' . $row['c01'] . '</div></td>';
      if (empty($row['c18'])) {
        echo '<td class="tstudio"><img src="../../src/thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
      } else {
        echo '<td class="tstudio"><a href="../display-studio-results.php?id=' . $row['c18'] . '"><img src="../../src/thumbnails/studios/' . $row['c18'] . '" title="' . $row['c18'] . '" alt="' . $row['c18'] . '" height="109" width="161"/></a></td>';
      }
    }
    echo "</tr>";
    echo "</table>";
    ?>

    <!-- Flèche retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="../../js/to-top.js"></script>
  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>