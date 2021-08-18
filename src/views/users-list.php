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
      background-image: url("/assets/img/bg_crud.png");
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-size: cover;
    }
  </style>

  <script src="/assets/js/dialog.js"></script>

  <header>
    <div class="headerLogo">
      <a href="/index.php">
        <img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" />
      </a>
      <h1>Liste des utilisateurs</h1>
    </div>
    <div class="btn-toolbar text-center justify-content-center mt-4 d-grid gap-2 d-md-flex">
      <?php
      echo '<a href="/index.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/home.png" alt="Accueil" title="Accueil" height="30" width="32" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
      echo '<a href="/src/views/profile.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/user.png" title="Utilisateur" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Profil</a>';
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
        <th class="table-header" width="5%">#</th>
        <th class="table-header" width="10%">Pseudo</th>
        <th class="table-header" width="10%">Avatar</th>
        <th class="table-header" width="5%">Genre</th>
        <th class="table-header" width="20%">Adresse mail</th>
        <th class="table-header" width="15%">Date d'enregistrement</th>
        <th class="table-header" width="15%">Rôle</th>
        <th class="table-header" width="10%">Droits</th>
        <th class="table-header" width="10%">Actions</th>
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
            <td id="td-id"><?php echo $row["id"]; ?></td>
            <td id="td-title"><?php echo $row["username"]; ?></td>
            <td id="td-avatar"><img class="img-fluid rounded-circle img-thumbnail z-depth-2" alt="<?php echo $row["avatar"]; ?>" src="/src/thumbnails/<?php echo $row["avatar"]; ?>" onclick="window.open(this.src)" data-holder-rendered="true" draggable="false" ondragstart="return false"></td>
            <td id="td-genre"><?php if ($row["genre"] == 1) {
                                echo '<img src="/assets/img/male.png" alt="Homme" title="Homme" draggable="false" ondragstart="return false">';
                              } elseif ($row["genre"] == 0) {
                                echo '<img src="/assets/img/female.png" alt="Femme" title="Femme" draggable="false" ondragstart="return false">';
                              } elseif ($row["genre"] == 2) {
                                echo '<img src="/assets/img/child.png" alt="Enfant" title="Enfant" draggable="false" ondragstart="return false">';
                              } ?></td>
            <td id="td-italic"><?php echo $row["email"]; ?></td>
            <td id="td-italic"><?php echo $french_date; ?></td>
            <td id="td-title"><?php if ($row["role"] == 1) {
                                echo 'Administrateur';
                              } elseif ($row["role"] == 0) {
                                echo 'Utilisateur';
                              } elseif ($row["role"] == 2) {
                                echo 'Super administrateur';
                              } ?></td>
            <td id="td-title">
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
            </td>
            <td id="td-actions">
              <a class="ajax-action-links" href='/src/views/watchlist.php?user=<?php echo $row['id']; ?>' target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/fav.png" alt="Watchlist" title="Watchlist" height="25" width="25" /></a>
              <a onclick="$('#dialog-example_<?php echo $row['id']; ?>').modal('show');" class="ajax-action-links" class="btn-show-modal" href="#" data-toggle="modal" draggable="false" ondragstart="return false"><img src="/assets/img/delete.png" alt="Supprimer" title="Supprimer" height="25" width="18" /></a>
            </td>
          </tr>
          <!-- Boîte de dialogue de suppression -->
          <div id="dialog-example_<?php echo $row['id']; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content" id="dialog-example_<?php echo $row['id']; ?>">
                <div class="modal-header">
                  <h3 class="modal-title">Confirmation de suppression</h3>
                </div>
                <div class="modal-body">
                  <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
                  <p class="modal-media-title">"<strong><?php echo $row['username']; ?></strong>" enregistré le <?php echo $french_date; ?></p>
                </div>
                <div class="modal-footer">
                  <a href="#" data-dismiss="modal" class="btn btn-info" onclick="$('#dialog-example_<?php echo $row['id']; ?>').modal('hide');">Non</a>
                  <a href='/src/database/delete.php?type=user&id=<?php echo $row['id']; ?>' class="btn btn-danger" id="<?php echo $row['id']; ?>">Oui</a>
                </div>
              </div>
            </div>
          </div>
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