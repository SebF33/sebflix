<!------------->
<!-- Galerie -->
<!------------->

<?php
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
  <link rel="stylesheet" href="/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/gallery.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/img/favicon-192.png">

  <!-- Appel de FlexSlider -->
  <link rel="stylesheet" href="/css/flexslider.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script src="/js/jquery.flexslider.js"></script>
  <script type="text/javascript" charset="utf-8">
    $(window).load(function() {
      $('.flexslider').flexslider();
    });
  </script>

  <!-- Appel de la police "Truculenta" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta&display=swap" rel="stylesheet">

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<!-- Corps de page -->

<body>

  <!-- Header -->
  <header id="header" role="banner">
    <div id="headerLeft">
    </div>

    <div id="headerMain">
      <!-- Logo du site -->
      <a href="/index.php"><img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>
    </div>

    <div id="headerRight">
    </div>
  </header>

  <!-- Main content -->
  <main id="main">

    <!-- Bouton retour en arrière -->
    <?php include "../templates/exit.html"; ?>

    <h1>Galerie <a onClick="window.location.reload()"><i class="fa fa-random"></i></a></h1>
    <div class="flexslider">
      <ul class="slides">
        <?php
        // Appel des fonctions de sélection aléatoire de 12 médias
        $randMovie = select_ten_random_movie();
        $randTvshow = select_two_random_tvshow();
        foreach ($randMovie as $row) {
          echo '<li><a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="1920" height="1080"/></a></li>';
        }
        foreach ($randTvshow as $row) {
          echo '<li><a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img src="../thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="1920" height="1080"/></a></li>';
        }
        ?>
      </ul>
    </div>
  </main>

  <!-- Footer -->
  <footer>
  </footer>

</body>

</html>