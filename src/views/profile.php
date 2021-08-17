<!-------------------->
<!-- Page du profil -->
<!-------------------->

<?php
// Initialisation de la session
session_start();

// Vérification si l'utilisateur est bien connecté
if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== TRUE) {
  // Redirection si l'utilisateur n'est pas connecté
  header("location:/src/templates/forms/login-form.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Profil utilisateur</title>

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">

  <!-- Appel de jQuery -->
  <script src="/assets/js/lib/jquery-3.6.0.min.js"></script>
  <!-- Appel de Bootstrap -->
  <script src="/assets/js/lib/bootstrap.min.js"></script>

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&display=swap" rel="stylesheet">
</head>

<body>
  <style type="text/css">
    body {
      background-image: url("/assets/img/bg_welcome.png");
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <header>
    <div class="headerLogo">
      <a href="/index.php">
        <img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" />
      </a>
      <img id="avatar-img" class="img-fluid m-5 rounded-circle img-thumbnail z-depth-2" alt="<?= $_SESSION["avatar"] ?>" src="/src/thumbnails/<?= $_SESSION["avatar"] ?>" data-holder-rendered="true" draggable="false" ondragstart="return false">
      <h1 class="my-5">Bonjour, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.<br>Bienvenue<?php if ($_SESSION["loggedadmin"] == TRUE and $_SESSION["role"] === 1) {
                                                                                                              echo ' cher administrateur';
                                                                                                            } elseif ($_SESSION["loggedadmin"] == TRUE and $_SESSION["role"] === 2) {
                                                                                                              echo ' super administrateur';
                                                                                                            } elseif ($_SESSION["loggedadmin"] == FALSE and $_SESSION["genre"] === 2) {
                                                                                                              echo ' petit(e)';
                                                                                                            } ?>.</h1>
    </div>
  </header>

  <!-- Boutons de gestion -->
  <div class="text-center">
    <a href="/src/templates/forms/reset-password-form.php" class="btn btn-warning">Changer le mot de passe</a>
    <a href="/src/templates/forms/logout.php" class="btn btn-danger ml-3">Se déconnecter</a>
  </div>
  <div class="btn-toolbar text-center justify-content-center mt-4 d-grid gap-2 d-md-flex">
    <?php
    echo '<a href="/index.php" class="button_link"><img src="/assets/img/home.png" alt="Accueil" title="Accueil" height="30" width="32" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
    echo '<a href="/src/views/watchlist.php?user=' . $_SESSION["id"] . '" class="button_link"><img src="/assets/img/fav.png" title="Watchlist" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Watchlist</a>';
    // Vérification si l'admin est loggé pour l'affichage des boutons d'administration
    if ($_SESSION["loggedadmin"] == TRUE) {
      echo '<a href="crud.php" class="button_link"><img src="/assets/img/crud.png" alt="Médias" title="Médias" height="30" width="26" />' . str_repeat('&nbsp;', 2) . 'Médias</a>';
      echo '<a href="users-list.php" class="button_link"><img src="/assets/img/users.png" alt="Utilisateurs" title="Utilisateurs" height="30" width="33" />' . str_repeat('&nbsp;', 2) . 'Utilisateurs</a>';
    }
    ?>
  </div>
</body>

</html>