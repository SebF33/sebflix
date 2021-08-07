<?php
////////////////////////
// LISTE UTILISATEURS //
////////////////////////

// Initialisation de la session
session_start();

// Appel du script d'administration des utilisateurs
require dirname(__DIR__) . '/database/usermanager.php';

// Mise à jour du rôle de l'utilisateur
if (isset($_POST['role']) and isset($_POST['id'])) {
  // Appel de la fonction de mise à jour du rôle
  update_role_by_id((int)$_POST['role'], (int)$_POST['id']);
  // Appel de la fonction de sélection d'un utilisateur par son identifiant
  $checkRole = select_user_by_id((int)$_SESSION["id"]);
  // Déconnexion si les droits d'admin sont supprimés
  if ($checkRole['role'] == 0) {
    // Annulation de toutes les variables de session
    unset($_SESSION);
    // Destruction de la session
    session_destroy();
  }
}

// Vérification si l'administrateur est bien connecté
if (!isset($_SESSION["loggedadmin"]) || $_SESSION["loggedadmin"] !== TRUE) {
  // Redirection si l'administrateur n'est pas connecté
  header("location:/index.php");
  exit;
}

// Fuseau horaire
date_default_timezone_set('EUROPE/Paris');
// Traduction de la date en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
?>

<html>

<head>
  <title>Liste des utilisateurs</title>

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
      <h1>Liste des utilisateurs</h1>
    </div>
    <div class="btn-toolbar text-center justify-content-center mt-4 d-grid gap-2 d-md-flex">
      <?php
      echo '<a href="/index.php" class="button_link"><img src="/assets/img/home.png" title="Accueil" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
      echo '<a href="/src/views/profile.php" class="button_link"><img src="/assets/img/user.png" title="Utilisateur" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Profil</a>';
      ?>
    </div>
  </header>

  <?php
  // Appel de la fonction de sélection de tous les utilisateurs
  $result = select_all_user();
  ?>

  <table class="tbl-qa tbl-user" border="3">
    <thead>
      <tr>
        <th class="table-header" width="10%">#</th>
        <th class="table-header" width="35%">Pseudo</th>
        <th class="table-header" width="25%">Date d'enregistrement</th>
        <th class="table-header" width="20%">Rôle</th>
        <th class="table-header" width="10%">Droits</th>
      </tr>
    </thead>
    <tbody id="table-body">
      <?php
      if (!empty($result)) {
        foreach ($result as $row) {
          // Formatage de la date en français
          $french_date = utf8_encode(strftime('%e %B %Y &agrave; %Hh%M', strtotime($row["created_at"])));
      ?>
          <tr class="table-row">
            <td id="id"><?php echo $row["id"]; ?></td>
            <td id="title"><?php echo $row["username"]; ?></td>
            <td id="title"><?php echo $french_date; ?></td>
            <td id="title"><?php if ($row["role"] == 1) {
                              echo 'Administrateur';
                            } elseif ($row["role"] == 0) {
                              echo 'Utilisateur';
                            } elseif ($row["role"] == 2) {
                              echo 'Super administrateur';
                            } ?></td>
            <td id="title">
              <?php if ($row["role"] != 2) { ?>
                <form action="" method="post">
                  <div class="form-check">
                    <input id="flexRadioDefault1" class="form-check-input" type="radio" name="role" <?php if ($row["role"] == 1) echo "checked"; ?> value="1" onchange="this.form.submit();" /> Avec
                  </div>
                  <div class="form-check">
                    <input id="flexRadioDefault1" class="form-check-input" type="radio" name="role" <?php if ($row["role"] != 1) echo "checked"; ?> value="0" onchange="this.form.submit();" /> Sans
                  </div>
                  <input type="hidden" name="id" value="<?php echo $row["id"]; ?>" />
                </form>
              <?php } ?>
            </td>
          </tr>
      <?php
        }
      }
      ?>
    </tbody>
  </table>
</body>

</html>