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
  <link rel="stylesheet" href="/css/footer.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/about.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de jQuery -->
  <script src="/js/jquery-3.6.0.min.js"></script>

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
  <header>
    <h1>À propos de <strong>Sebflix</strong></h1>
  </header>

  <main>
    <div class="wrapper-about">
      <p>Le site propose actuellement :</p>
      <p>X fiches de films ;</p>
      <p>X fiches de séries ;</p>
      <p class="italic">« J’ai pris un plaisir infini à développer ce site en partant de rien, j’ai hâte de vous partager mes autres passions à travers de futurs projets. »</p>
      <p class="italic">&mdash; Sébastien</p>
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