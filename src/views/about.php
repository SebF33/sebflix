<!------------------->
<!-- Page à propos -->
<!------------------->

<?php
// Initialisation de la session
session_start();
if (isset($_SESSION["logged"]) && $_SESSION["logged"] == TRUE) {
  $user = $_SESSION["id"];
} else {
  $user = '';
}

// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
$nbUser = count_all_user();
$nbMovie = count_all_movie();
$nbTvshow = count_all_tvshow();
$nbAnime = count_all_anime();
$nbAnimation = count_all_animation();
$nbSpectacle = count_all_spectacle();
$nbActor = count_all_actor();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>À propos</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="Description" content="Page à propos">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">
  <link rel="stylesheet" href="/assets/css/footer.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/about.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de jQuery -->
  <script src="/assets/js/lib/jquery-3.6.0.min.js"></script>

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
</head>

<body>
  <header>
    <h1>À propos de <strong>Sebflix...</strong></h1>
    <script>
      var $h1 = $('h1');
      $(window).scroll(function() {
        if ($(this).scrollTop() > 0) {
          $h1.fadeOut(200);
        } else {
          $h1.fadeIn(200);
        }
      });
    </script>
  </header>

  <main>
    <div class="about-container">
      <div class="wrapper-about">
        <img src="/assets/img/favicon-192.png" alt="Icon" draggable="false" ondragstart="return false">
        <p><a href="/src/templates/forms/register-form.php">Inscrivez-vous</a> gratuitement sur <a href="/index.php">www.sebflix.fr</a> et installez-vous bien confortablement devant votre écran, accompagné d’un peu de pop-corn...</p>
        <p>Recherchez vos médias préférés, puis regardez les bandes-annonces comme si vous étiez au cinéma… parmi <?= $nbMovie ?> fiches de film, <?= $nbAnimation ?> de dessin animé, <?= $nbTvshow ?> de série, <?= $nbAnime ?> d'anime, <?= $nbSpectacle ?> de spectacle, joués par <?= $nbActor ?> actrices/acteurs/doubleurs. Créez un compte à vos jeunes enfants afin qu’ils accèdent à un contenu adapté.</p>
        <p>Enfin, et surtout, faites votre propre <a href="/src/views/watchlist.php?user=<?= $user ?>">liste de favoris</a> ❤ Vous êtes déjà <?= $nbUser ?> membres !</p>
        <br>
        <p class="italic">« J’ai pris un plaisir infini à développer ce site, maintenant j’ai hâte de vous partager mes autres passions au travers de futurs projets. »</p>
        <p class="italic sign">&mdash; Sébastien</p>
      </div>
    </div>

    <script src="/assets/js/lib/lottie-player.js"></script>
    <lottie-player src="/assets/json/cinema.json" background="transparent" speed="0.7" style="width: 600px; height: 600px; margin: 0 auto;" loop autoplay></lottie-player>

    <!-- Menu circulaire -->
    <?php include "../templates/menu.html"; ?>
  </main>

  <!-- Footer -->
  <?php include "../templates/footer.php" ?>

</body>

</html>