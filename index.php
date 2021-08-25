<!-------------------->
<!-- Page d'accueil -->
<!-------------------->

<?php
// Initialisation de la session
session_start();
// Profil enfant
require __DIR__ . '/src/database/child.php';
// Appel du script d'affichage des données
require __DIR__ . '/src/database/viewmanager.php';
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title>Sebflix</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="Description" content="Page d'accueil Sebflix">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="assets/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen">
  <link rel="stylesheet" href="assets/css/footer.css" type="text/css" media="screen">
  <link rel="stylesheet" href="assets/css/search-button.css" type="text/css" media="screen">
  <link rel="stylesheet" href="assets/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="assets/css/carousel.css" type="text/css" media="screen">
  <link rel="stylesheet" href="assets/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="assets//img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="assets//img/favicon-192.png">

  <!-- Appel de jQuery -->
  <script src="assets/js/lib/jquery-3.6.0.min.js"></script>

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
</head>

<!-- Corps de page -->

<body>
  <style type="text/css">
    body {
      background-image: url(<?php if ($set_child) {
                              // Profil enfant
                              echo '"/assets/img/bg_child.png"';
                            } else {
                              echo '"/assets/img/bg_index.png"';
                            } ?>);
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <!-- Header -->
  <?php
  // Construction de la barre de navigation
  $navLeft = [
    'Films' => 'src/views/alphabet.php?category=movies',
    'Séries' => 'src/views/alphabet.php?category=tvshows',
    'Animes' => 'src/views/alphabet.php?category=animes'
  ];
  $navRight = [
    'Dessins animés' => 'src/views/alphabet.php?category=animation',
    'Spectacles' => 'src/views/alphabet.php?category=spectacles'
  ];
  $btnNavClass = "";
  include "src/templates/navigation.php"
  ?>

  <!-- Main content -->
  <main class="main">
    <div class="mainTop">

      <!-- Texte d'accueil -->
      <div class="welcome-text">
        <?php
        // Appel de la fonction de comptage du nombre total de médias
        $nCount = count_all_media($sqlWhereChild);
        ?>
        <p>"<strong>Sebflix</strong> est un site internet culturel qui <?php if ($set_child) {
                                                                          echo "te";
                                                                        } else {
                                                                          echo "vous";
                                                                        } ?> propose la découverte de <span><?= $nCount ?></span> œuvres cinématographiques ou télévisuelles<?php if ($set_child) {
                                                                                                                                                                              echo " pour enfants";
                                                                                                                                                                            } ?>."</p>
      </div>

      <!-- Appel des scripts pour le formulaire de recherche -->
      <script src="assets/js/submit.js"></script>
      <script src="assets/js/placeholder.js"></script>

      <!-- Bloc de recherche -->
      <div id="cover">
        <form name="formText" method="post" onsubmit="submitForm()">
          <div class="tb">
            <div class="td"><input type="text" id="searchInput" name="search" placeholder="Titre(s)" required></div>
            <div class="td" id="s-cover">
              <button type="submit" name="submit">
                <div id="s-circle"></div>
                <span></span>
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Boutons radio -->
      <?php include "./src/templates/radios.html"; ?>
    </div>

    <div class="mainBottom">
      <!-- Bouton de rafraîchissement du carrousel -->
      <div class="random-btn-container"><a class="random-carousel-btn"><i class="fa fa-random"></i></a></div>
      <script>
        $(".random-carousel-btn").click(function() {
          $(".my-card").removeClass("prev active next");
          $.ajax({
            type: "GET",
            url: "src/templates/carousel.php",
            success: function(data) {
              setTimeout(function() {
                delaySuccess(data);
              }, 100);
            }
          });
        });

        function delaySuccess(data) {
          $(".card-carousel").css("left", "0px");
          $(".card-carousel").html(data);
        };
      </script>

      <!-- Carrousel -->
      <div class="card-carousel">
        <?php
        // Appel des fonctions de sélection aléatoire de 21 médias
        $randMovie = select_eighteen_random_movie($sqlAndChild);
        $randTvshow = select_three_random_tvshow($sqlAndChild);
        // Affichage dans le carrousel des données appelées
        foreach ($randMovie as $row) {
          echo '<div class="my-card"><a class="my-btn-card" href="src/views/viewpage.php?type=movie&id=' . $row['idMovie'] . '" draggable="false" ondragstart="return false"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="160" height="240" draggable="false" ondragstart="return false"/></a></div>';
        }
        foreach ($randTvshow as $row) {
          echo '<div class="my-card"><a class="my-btn-card" href="src/views/viewpage.php?type=tvshow&id=' . $row['idShow'] . '" draggable="false" ondragstart="return false"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="160" height="240" draggable="false" ondragstart="return false"/></a></div>';
        }
        ?>
        <script src="/assets/js/carousel.js"></script>
      </div>
      <script src="/assets/js/keyboard.js"></script>
    </div>

    <!-- Menu circulaire -->
    <?php include "./src/templates/menu.html"; ?>

  </main>

  <!-- Footer -->
  <?php include "./src/templates/footer.php" ?>

</body>

</html>