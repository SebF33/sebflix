<!-------------------->
<!-- Page du profil -->
<!-------------------->

<?php
// Initialisation de la session
session_start();

// Vérification si l'utilisateur est bien connecté
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== TRUE) {
  // Redirection si l'utilisateur n'est pas connecté
  header("location:/src/templates/forms/login-form.php");
  exit;
}

// Appel du script d'administration des utilisateurs
require dirname(__DIR__) . '/database/usermanager.php';
// Appel du script de l'upload d'image
require dirname(__DIR__) . '/database/upload.php';

// Appel de la fonction de sélection d'un avatar par l'identifiant d'un utilisateur
$avatar = select_avatar_by_id($_SESSION['id']);
$_SESSION['avatar'] = $avatar['avatar'];
// Définition des variables et initialisation avec des valeurs vides
$avatar_err = $default_picture_name = "";
// Traitement du formulaire de données quand il est parvenu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Définition du dossier qui contiendra l'avatar
  $img_folder = dirname(__DIR__, 2) . '/src/thumbnails/users/';
  // Traitement de l'avatar
  $upload_img = upload_img($_FILES['picture'], $default_picture_name, $img_folder);
  $set_request = $upload_img[0]; // Autorisation de création de l'utilisateur
  $avatar_name = 'users/' . $upload_img[1]; // Nom du chemin de l'image pour la base de données
  $avatar_err = $upload_img[2]; // Message d'erreur de l'upload
  // Vérification des erreurs avant insertion dans la base de données
  if ($set_request) {
    // Appel de la fonction de mise à jour de l'avatar
    update_avatar($_SESSION['id'], $avatar_name);
    // Redirection de l'utilisateur vers la page du profil
    header("location:/src/views/profile.php");
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Profil utilisateur</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="Description" content="Page du profil">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">

  <!-- Appel de jQuery -->
  <script src="/assets/js/lib/jquery-3.6.0.min.js"></script>
  <!-- Appel de Bootstrap -->
  <script src="/assets/js/lib/bootstrap.min.js"></script>
</head>

<body>
  <style type="text/css">
    body {
      background-image: url(<?php if (isset($_SESSION["logged"]) && $_SESSION["genre"] == 2) {
                              // Profil enfant
                              echo '"/assets/img/bg_children.png"';
                            } else {
                              echo '"/assets/img/bg_welcome.png"';
                            } ?>);
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <header>
    <div class="headerLogo">
      <a href="/index.php">
        <img src="/assets/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" />
      </a>

      <img id="avatar-img" class="img-fluid m-5 rounded-circle img-thumbnail z-depth-2" alt="<?= $_SESSION['avatar'] ?>" src="/src/thumbnails/<?= $_SESSION['avatar'] ?>" data-holder-rendered="true" draggable="false" ondragstart="return false">

      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576"> <!-- Poids maxi : 1Mo => 1024*1024 -->
        <input type="file" id="picture" name="picture" hidden="true">
      </form>

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
    <a href="/src/templates/forms/reset-password-form.php" class="btn btn-warning" draggable="false" ondragstart="return false">Changer le mot de passe</a>
    <a href="/src/templates/forms/logout.php" class="btn btn-danger ml-3" draggable="false" ondragstart="return false">Se déconnecter</a>
  </div>
  <div class="btn-toolbar text-center justify-content-center mt-4 d-grid gap-2 d-md-flex">
    <?php
    echo '<a href="/index.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/home.png" alt="Accueil" title="Accueil" height="30" width="32" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
    echo '<a href="/src/views/watchlist.php?user=' . $_SESSION["id"] . '" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/fav.png" title="Watchlist" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Watchlist</a>';
    // Vérification si l'admin est loggé pour l'affichage des boutons d'administration
    if ($_SESSION["loggedadmin"] == TRUE) {
      echo '<a href="crud.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/crud.png" alt="Médias" title="Médias" height="30" width="26" />' . str_repeat('&nbsp;', 2) . 'Médias</a>';
      echo '<a href="users-list.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/users.png" alt="Utilisateurs" title="Utilisateurs" height="30" width="33" />' . str_repeat('&nbsp;', 2) . 'Utilisateurs</a>';
    }
    ?>
  </div>

  <?php if (isset($avatar_err) && !empty($avatar_err)) :
    // Message d'alerte
  ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $avatar_err; ?>
    </div>
  <?php endif; ?>

  <script>
    document.querySelector('#avatar-img').addEventListener('click', function() {
      document.querySelector('#picture').click();
    });
    document.querySelector('#picture').addEventListener('change', function() {
      this.form.submit();
    });
  </script>

</body>

</html>