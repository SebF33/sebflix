<!------------->
<!-- Galerie -->
<!------------->

<?php
// Initialisation de la session
session_start();
// Profil enfant
require dirname(__DIR__) . '/database/child.php';
// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title>Galerie</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Galerie">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/assets/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/results.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/gallery.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/assets/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicon-192.png">

  <!-- Appel de FlexSlider -->
  <link rel="stylesheet" href="/assets/css/lib/flexslider.css" type="text/css">
  <script src="/assets/js/lib/jquery-1.6.2.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
      $(".flexslider").flexslider();
    });
  </script>

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
</head>

<!-- Corps de page -->

<body>

  <!-- Header -->
  <header role="banner">
    <!-- Logo du site -->
    <div class="headerLogo">
      <a href="/index.php"><img src="/assets/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>
      <h1 class="results">Galerie</h1>
    </div>
  </header>

  <!-- Main content -->
  <main id="main">
    <!-- Bouton de rafraîchissement -->
    <div class="random-btn-container"><a class="random-slides-btn" onclick="window.location.reload()"><i class="fa fa-random"></i></a></div>

    <!-- FlexSlider -->
    <div class="flexslider">
      <ul class="slides">
        <?php
        // Appel des fonctions de sélection aléatoire de 12 médias
        $randMovie = select_ten_random_movie($sqlAndChild);
        $randTvshow = select_two_random_tvshow($sqlAndChild);
        foreach ($randMovie as $row) {
          echo '<li><a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="/src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="1920" height="1080"/></a></li>';
        }
        foreach ($randTvshow as $row) {
          echo '<li><a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img src="/src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="1920" height="1080"/></a></li>';
        }
        ?>
      </ul>
      <script src="/assets/js/lib/flexslider.js"></script>
    </div>

    <!-- Menu circulaire -->
    <?php include "../templates/menu.html"; ?>
  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>