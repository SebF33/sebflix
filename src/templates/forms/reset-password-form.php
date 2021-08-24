<?php
//////////////////////////////////////
// RÉINITIALISATION DU MOT DE PASSE //
//////////////////////////////////////

// Initialisation de la session
session_start();

// Vérification si l'utilisateur est bien connecté
if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== TRUE) {
  // Redirection vers la page de connexion
  header("location:/src/templates/forms/login-form.php");
  exit;
}

// Appel du script d'administration des utilisateurs
require dirname(__DIR__, 2) . '/database/usermanager.php';
// Appel du script de validation des données
require dirname(__DIR__, 2) . '/database/validation.php';

// Définition des variables et initialisation avec des valeurs vides
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Traitement du formulaire de données quand il est parvenu
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validation du nouveau mot de passe
  if (empty(trim($_POST["new_password"]))) {
    $new_password_err = "Veuillez saisir le nouveau mot de passe.";
  } elseif (strlen(trim($_POST["new_password"])) < 6) {
    $new_password_err = "Le mot de passe doit contenir au moins 6 caractères.";
  } else {
    $new_password = valid_data($_POST["new_password"]);
  }

  // Validation de la confirmation du nouveau mot de passe
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Veuillez confirmer le mot de passe.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($new_password_err) && ($new_password != $confirm_password)) {
      $confirm_password_err = "Le mot de passe ne correspond pas.";
    }
  }

  // Vérification des erreurs d'entrée avant de mettre à jour la base de données
  if (empty($new_password_err) && empty($confirm_password_err)) {

    // Hachage du mot de passe
    $new_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Mise à jour du mot de passe
    $updated = update_pwd($_SESSION["username"], $new_password);

    if ($updated) {
      // Mot de passe mis à jour avec succès alors destruction de la session
      session_destroy();
      // Redirection vers la page de connexion
      header("location:/src/templates/forms/login-form.php");
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Réinitialisation du mot de passe</title>

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">
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
        <img src="/assets/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" />
      </a>
      <h1>Compte utilisateur</h1>
    </div>
    <div class="btn-toolbar text-center justify-content-center mt-4 d-grid gap-2 d-md-flex">
      <?php
      echo '<a href="/index.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/home.png" alt="Accueil" title="Accueil" height="30" width="32" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
      echo '<a href="/src/views/profile.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/user.png" title="Utilisateur" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Profil</a>';
      ?>
    </div>
  </header>

  <div class="wrapper">
    <img src="/assets/img/ticket.png" alt="Ticket" draggable="false" ondragstart="return false">
    <h2>Réinitialiser le mot de passe</h2>
    <p>Veuillez remplir ce formulaire pour changer votre mot de passe.</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label>Nouveau mot de passe <span>*</span> :</label>
        <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
        <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
      </div>
      <div class="form-group">
        <label>Confirmer le mot de passe <span>*</span> :</label>
        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Envoyer">
        <a class="btn btn-secondary ml-2" href="/src/views/profile.php" draggable="false" ondragstart="return false">Annuler</a>
      </div>
    </form>
  </div>
</body>

</html>