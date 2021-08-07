<?php
/////////////////
// INSCRIPTION //
/////////////////

// Appel du script d'administration des utilisateurs
require dirname(__DIR__, 2) . '/database/usermanager.php';
// Appel du script de validation des données
require dirname(__DIR__, 2) . '/database/validation.php';

// Définition des variables et initialisation avec des valeurs vides
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Traitement du formulaire de données quand il est parvenu
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validation du pseudo
  if (empty(trim($_POST["username"]))) {
    $username_err = "Merci d'entrer un nom d'utilisateur.";
  } elseif (strlen(trim($_POST["username"])) > 11) {
    $username_err = "Le pseudo ne doit pas contenir plus de 11 caractères.";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
    $username_err = "Le pseudo peut uniquement contenir des lettres, chiffres et underscores.";
  } else {
    $username = valid_data(mb_strtolower($_POST['username']));
    // Requête de sélection
    $username_db = select_username($username);
    // Vérification si le pseudo existe déjà
    if ($username_db) {
      $username_err = "Le pseudo est déjà pris.";
    }
  }

  // Validation de l'adresse mail
  $email = valid_data($_POST['email']);
  if (empty(trim($_POST["email"]))) {
    $email_err = "Merci d'entrer une adresse mail.";
    // Vérification du format de l'adresse mail
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Requête de sélection
    $email_db = select_email($email);
    // Vérification si l'adresse mail existe déjà
    if ($email_db) {
      $email_err = "L'adresse mail est déjà prise.";
    }
  } else {
    $email_err = "L'adresse mail n'est pas au bon format.";
  }

  // Validation du password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Veuillez entrer un mot de passe.";
    // Définition du nombre minimal de caractères requis
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
  } else {
    $password = valid_data($_POST['password']);
  }

  // Confirmation du password
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Veuillez confirmer le mot de passe.";
  } else {
    $confirm_password = valid_data($_POST['confirm_password']);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Le mot de passe ne correspond pas.";
    }
  }

  // Vérification des erreurs de saisie avant insertion dans la base de données
  if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
    // Hachage du mot de passe
    $password = password_hash($password, PASSWORD_DEFAULT);
    // Requête de création de l'utilisateur
    add_user($username, $email, $password);

    // Redirection de l'utilisateur vers la page du profil
    header("location:/src/views/profile.php");
  }
}
?>

<!------------------------>
<!-- Page d'inscription -->
<!------------------------>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Inscription</title>

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
  <header>
    <div class="headerLogo">
      <a href="/index.php">
        <img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" />
      </a>
      <h1>Compte utilisateur</h1>
    </div>
    <div class="btn-toolbar mx-4">
      <?php
      echo '<a href="/src/views/profile.php" class="button_link"><img src="/assets/img/user.png" title="Utilisateur" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Profil</a>';
      ?>
    </div>
  </header>

  <div class="wrapper">
    <img src="/assets/img/ticket.png" alt="Ticket">
    <h2>S'inscrire</h2>
    <p>Veuillez remplir ce formulaire pour créer un compte.</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label>Pseudo :</label>
        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" placeholder="Saisissez 11 caractères max...">
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
      </div>
      <div class="form-group">
        <label>Adresse mail :</label>
        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
        <span class="invalid-feedback"><?php echo $email_err; ?></span>
      </div>
      <div class="form-group">
        <label>Mot de passe :</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="Saisissez 6 caractères min...">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
      </div>
      <div class="form-group">
        <label>Confirmer le mot de passe :</label>
        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Envoyer">
        <input type="reset" class="btn btn-secondary ml-2" value="Réinitialiser">
      </div>
      <p>Vous avez déjà un compte ? <a href="login-form.php">Se connecter</a></p>
    </form>
  </div>
</body>

</html>