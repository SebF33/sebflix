<!-------------->
<!-- Page 403 -->
<!-------------->

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title>Accès interdit</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Page 403">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/assets/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/results.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/menu.css" type="text/css" media="screen">

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
</head>

<!-- Corps de page -->

<body>
  <style>
    body {
      background-image: url('/assets/img/bg_403.png');
    }
  </style>

  <!-- Header -->
  <header role="banner">
    <div class="headerLogo">
      <!-- Logo du site -->
      <a href="/index.php"><img src="/assets/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" /></a>
      <h1 class="errorTitle">Accès interdit</h1>
      <p class="results">Oups, cet endroit du site n'est pas autorisé... vous pouvez retourner à <a href="/index.php"> l'entrée</a>.</p>
    </div>
  </header>

  <!-- Main content -->
  <main>
    <!-- Menu circulaire -->
    <?php include "../templates/menu.html"; ?>
  </main>

</body>

</html>