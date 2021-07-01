<?php
// Appeler le script de connexion
require '../connect.php';
?>

<?php
$id = $_GET['id'];

// Requête de sélection MySQL
$query = $db->prepare(
  "SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE c00 LIKE '$id%'
    AND media_type = 'movie'
    AND type = 'poster'
    AND c14 LIKE '%Animation%'
    AND c14 NOT LIKE '%Court-métrage%'
    ORDER BY c00"
);
$query->execute();
$result = $query->fetchall();

// Redirection vers la page 404 si le résultat de la requête est nul
if (empty($result)) {
  header("HTTP/1.0 404 Not Found");
  include_once("../../src/not-found.html");
  exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?php echo 'Animation en \'' . strtoupper($id) . '\''; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Animation par lettre">

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
    foreach ($result as $row) {
      echo "<tr>";
      echo '<td class="tposter"><a href="../viewpage.php?id=' . $row['idMovie'] . '"><img src="../../src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['c00'] . '" alt="' . $row['c00'] . '" height="240" width="160"/></a></td>';
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