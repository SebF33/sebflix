<!------------------------------->
<!-- Page du média type séries -->
<!------------------------------->

<!-- Appel du script de connexion -->
<?php require 'connect.php'; ?>

<?php
// Récupération de l'ID du média
$id = $_GET['id'];

// Requête de sélection MySQL à partir de l'ID récupéré
$query = $db->prepare(
  "SELECT *
    FROM tvshow
    INNER JOIN art ON tvshow.idShow = art.media_id
    WHERE idShow = $id
    AND media_type = 'tvshow'
    AND type = 'clearlogo'"
);
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

// Requête de sélection MySQL de la note
$stmt = $db->prepare(
  "SELECT *
    FROM tvshow
    INNER JOIN rating ON tvshow.idShow = rating.media_id
    WHERE idShow = $id
    AND media_type = 'tvshow'
    ORDER BY media_id DESC LIMIT 1"
);
$stmt->execute();
$rating = $stmt->fetch();

// Requête de sélection MySQL du fanart
$stmt = $db->prepare(
  "SELECT *
    FROM tvshow
    INNER JOIN art ON tvshow.idShow = art.media_id
    WHERE idShow = $id
    AND media_type = 'tvshow'
    AND type = 'fanart'"
);
$stmt->execute();
$fanart = $stmt->fetch();

// Requête de sélection MySQL des acteurs
$query = $db->prepare(
  "SELECT *
    FROM actor_link
    INNER JOIN actor ON actor.actor_id = actor_link.actor_id
    WHERE media_id = $id
    AND media_type = 'tvshow'
    ORDER BY cast_order ASC
    LIMIT 7"
);
$query->execute();
$actors = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?php foreach ($rows as $row) {
            echo $row['c00'];
          } ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Viewpage">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="../css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/viewpage.css" type="text/css" media="screen">
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

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
</head>

<!-- Corps de page -->

<body>

  <!-- Fanart du média -->
  <style type="text/css">
    body {
      background-image: url(<?php if (empty($fanart['cachedurl'])) {
                              echo '"../img/bg.png"';
                            } else {
                              echo '"../src/thumbnails/' . $fanart['cachedurl'] . '"';
                            } ?>);
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <!-- Header -->
  <header id="headerViewpage" role="banner">
    <div id="headerLeft">
      <!-- Note du média -->
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
        echo '<td class="trating"><img src="../src/thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée"/></td>';
      } else {
        echo '<td class="trating"><img class="starsRating" src="../src/thumbnails/rating/' . $rating['rating'] . '.png" title="' . $rating['rating'] . '" alt="' . $rating['rating'] . '"/></td>';
      }
      // Classification
      foreach ($rows as $row) {
        if (empty($row['c13'])) {
          echo '<td class="tcsa"><img src="../src/thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée"/></td>';
        } else {
          echo '<td class="tcsa"><img src="../src/thumbnails/csa/' . $row['c13'] . '.png" alt="' . $row['c13'] . '"/></td>';
        }
      }
      // Pays
      if (empty($row['country'])) {
        echo '<td class="tcountry"><img src="../src/thumbnails/placeholders/nr.png" title="Non renseigné" alt="Non renseigné"/></td>';
      } else {
        echo '<td class="tcountry"><img src="../src/thumbnails/country/' . $row['country'] . '.png" title="' . $row['country'] . '" alt="' . $row['country'] . '"/></td>';
      }
      // Date de sortie
      foreach ($rows as $row) {
        $date = DateTime::createFromFormat("Y-m-d", $row['c05']);
        if (empty($row['c05'])) {
          echo '<td class="tpremiered"><img src="../src/thumbnails/placeholders/nr.png" title="Non renseignée" alt="Non renseignée"/></td>';
        } else {
          echo '<td class="tpremiered">' . $date->format("Y") . '</td>';
        }
      }
      echo "</tr>";
      echo "</table>";
      ?>
    </div>

    <div id="headerMain">
      <!-- Logo du média -->
      <?php
      if (!$rows) {
        echo $row['c00'];
      } else {
        foreach ($rows as $row) {
          if (empty($row['cachedurl'])) {
            echo $row['c00'];
          } else {
            echo '<img src="../src/thumbnails/' . $row['cachedurl'] . '" alt="' . $row['c00'] . '" height="124" width="320"/>';
          }
        }
      }
      ?>
    </div>

    <div id="headerRight">
    </div>
  </header>

  <!-- Main content -->
  <main>

    <!-- Bouton retour en arrière -->
    <?php include "../src/exit.html"; ?>

    <section class="firstPage">
      <div class="mainTop">
        <!-- Bande-annonce -->
        <?php
        if (empty($row['embed'])) {
          echo '<img src="../src/thumbnails/placeholders/error404.png" alt="" height="315" width="560"/>';
        } else {
          echo '<iframe width="560" height="315" src="' . $row['embed'] . '" title="' . $row['c00'] . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
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
        foreach ($rows as $row) {
          echo '<td class="tsynopsis">' . $row['c01'] . '</td>';
          if (empty($row['c14'])) {
            echo '<td class="tstudio"><img src="../src/thumbnails/placeholders/studio_nr.png" title="Non renseigné" alt="" height="109" width="161"/></td>';
          } else {
            echo '<td class="tstudio"><a href="display-studio-results.php?id=' . $row['c14'] . '"><img src="../src/thumbnails/studios/' . $row['c14'] . '" title="' . $row['c14'] . '" alt="' . $row['c14'] . '" height="109" width="161"/></a></td>';
          }
        }
        echo "</tr>";
        echo "</table>";
        ?>
      </div>

      <!-- Appel du script ReadAndSpeech -->
      <script src="../js/readandspeech.js"></script>

      <div class="mainBottom">
        <?php
        // Créer un tableau si la variable $actors n'est pas vide
        if (!empty($actors)) {
          echo
          "<table class='table-casting' border='2'>
            <tr>
            </tr>";
          // Actrices/acteurs/doubleurs
          foreach ($actors as $row) {
            if (empty($row['cachedurl'])) {
              echo '<td class="tcasting"><figure><a href="display-actor-results.php?id=' . $row['actor_id'] . '"><img src="../src/thumbnails/placeholders/casting.png" title="' . $row['name'] . '" alt="" height="288" width="192"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            } else {
              echo '<td class="tcasting"><figure><a href="display-actor-results.php?id=' . $row['actor_id'] . '"><img src="../src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['name'] . '" alt="' . $row['name'] . '" height="288" width="192"/></a><figcaption>' . $row['name'] . '</figcaption></figure></td>';
            }
          }
        }
        echo "</tr>";
        echo "</table>";
        ?>
      </div>
    </section>

    <!-- Flèche retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="../js/to-top.js"></script>

  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>