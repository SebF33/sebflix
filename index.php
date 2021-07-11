<!-------------------->
<!-- Page d'accueil -->
<!-------------------->

<?php
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
  <link rel="stylesheet" href="css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/responsive.css" type="text/css" media="screen">
  <link rel="stylesheet" href="css/search-button.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/img/favicon-192.png">

  <!-- Appel des scripts JavaScript -->
  <script src="js/jquery-3.6.0.min.js"></script>

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300;1,400&display=swap" rel="stylesheet">

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<!-- Corps de page -->

<body>

  <!-- Header -->
  <?php
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
        $nCount = count_all_media();
        echo '<p>"<strong>Sebflix</strong> est un site internet culturel qui vous propose la découverte de <span>' . $nCount . '</span> œuvres cinématographiques ou télévisuelles."</p>';
        ?>
      </div>

      <!-- Appel des scripts pour le formulaire de recherche -->
      <script src="js/submit.js"></script>
      <script src="js/placeholder.js"></script>

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
      <!-- Bouton de rafraîchissement -->
      <h1 class="hRandom">Médias aléatoires <a onClick="window.location.reload()"><i class="fa fa-random"></i></a></h1>
      <?php
      // Appel des fonctions de sélection aléatoire de 6 médias
      $randMovie = select_five_random_movie();
      $randTvshow = select_one_random_tvshow();
      // Création d'un tableau à 1 colonne
      echo
      "<table class='tRandom' border='1'>
            <tr>
            </tr>";
      // Affichage dans le tableau des données appelées
      foreach ($randMovie as $row) {
        echo '<td><a href="src/views/viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" height="288" width="192"/></a></td>';
      }
      echo '<td><a href="src/views/viewpage.php?type=tvshow&id=' . $randTvshow['idShow'] . '"><img src="src/thumbnails/' . $randTvshow['cachedurl'] . '" title="' . $randTvshow['title'] . '" alt="' . $randTvshow['title'] . '" height="288" width="192"/></a></td>';
      echo "</tr>";
      echo "</table>";
      ?>
    </div>
  </main>

  <!-- Footer -->
  <?php include "src/templates/footer.php" ?>

</body>

</html>