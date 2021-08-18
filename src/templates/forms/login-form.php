<?php
///////////////
// CONNEXION //
///////////////

// Appel du script d'administration des utilisateurs
require dirname(__DIR__, 2) . '/database/usermanager.php';

// Définition des variables et initialisation avec des valeurs vides
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Traitement du formulaire de données quand il est parvenu
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Vérifier si le nom d'utilisateur est vide
  if (empty(trim($_POST["username"]))) {
    $username_err = "Entrez un pseudo svp.";
  } else {
    $username = htmlspecialchars(trim(mb_strtolower($_POST["username"])), ENT_QUOTES);
  }

  // Vérifier si le mot de passe est vide
  if (empty(trim($_POST["password"]))) {
    $password_err = "Entrez un mot de passe svp.";
  } else {
    $password = htmlspecialchars(trim($_POST["password"]), ENT_QUOTES);
  }

  // Validation des identifiants
  if (empty($username_err) && empty($password_err)) {
    // Requête de sélection
    $userdb = select_username($username);

    // Vérification si le pseudo existe
    if ($userdb) {
      // Vérification si le mot de passe correspond
      if (password_verify($password, $userdb['password'])) {
        // Mot de passe correct alors démarrage d'une nouvelle session
        session_start();

        // Stockage des données dans des variables de session
        $_SESSION['logged'] = TRUE;
        $_SESSION['id'] = (int)$userdb['id'];
        $_SESSION['username'] = $userdb['username'];
        $_SESSION['role'] = (int)$userdb['role'];
        $_SESSION['genre'] = (int)$userdb['genre'];
        $_SESSION['avatar'] = $userdb['avatar'];
        if ($_SESSION['role'] === 1 or $_SESSION['role'] === 2) {
          $_SESSION['loggedadmin'] = TRUE;
        } else {
          $_SESSION['loggedadmin'] = FALSE;
        }

        // Redirection de l'utilisateur vers la page du profil
        header("location:/src/views/profile.php");
      } else {
        $login_err = "Le mot de passe est invalide.";
      }
    } else {
      $login_err = "Le pseudo n'existe pas.";
    }
  }
}
?>

<!----------------------->
<!-- Page de connexion -->
<!----------------------->

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Connexion</title>

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&display=swap" rel="stylesheet">
</head>

<body>
  <style type="text/css">
    body {
      background-image: url("/assets/img/bg_user.png");
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
      <h1>Compte utilisateur</h1>
    </div>
    <div class="btn-toolbar text-center justify-content-center mt-4 d-grid gap-2 d-md-flex">
      <?php
      echo '<a href="/index.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/home.png" title="Accueil" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
      ?>
    </div>
  </header>

  <div class="wrapper">
    <img src="/assets/img/ticket.png" alt="Ticket" draggable="false" ondragstart="return false">
    <h2>Connexion</h2>
    <p>Veuillez remplir vos identifiants pour vous connecter.</p>

    <?php
    if (!empty($login_err)) {
      echo '<div class="alert alert-danger">' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label>Pseudo :</label>
        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
      </div>
      <div class="form-group">
        <label>Mot de passe :</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Se connecter">
      </div>
      <p>Vous n'avez pas de compte ? <a href="register-form.php">S'inscrire</a></p>
    </form>
  </div>
</body>

</html>