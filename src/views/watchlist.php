<?php
///////////////
// Watchlist //
///////////////

// Initialisation de la session
session_start();

// Vérification si l'utilisateur est bien connecté
if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== TRUE) {
  // Redirection si l'utilisateur n'est pas connecté
  header("location:/index.php");
  exit;
}

// Fuseau horaire
date_default_timezone_set('EUROPE/Paris');
// Traduction de la date en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
// Appel du script de validation des données
require dirname(__DIR__) . '/database/validation.php';

// Vérification du GET
if (isset($_GET['user']) && !empty($_GET['user'])) {
  // Récupération et validation de l'ID de l'utilisateur
  $user = (int)valid_get($_GET['user']);

  // Vérification et autorisation de l'ID de l'utilisateur
  $checkid = check_id_user($user);
  if ($checkid === 0 || ($_SESSION["id"] !== $user && $_SESSION["loggedadmin"] == FALSE)) {
    // Redirection
    header("location:/src/views/profile.php");
    exit;
  } else {
    $username = select_username_by_id($user);
  }
} else {
  // Redirection
  header("location:/index.php");
  exit;
}
?>

<html>

<head>
  <title>Watchlist</title>

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">

  <!-- Appel de jQuery -->
  <script src="/assets/js/lib/jquery-3.6.0.min.js"></script>
  <!-- Appel de Bootstrap -->
  <script src="/assets/js/lib/bootstrap.min.js"></script>

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>

<body>
  <style type="text/css">
    body {
      background-image: url("/assets/img/bg_fav.png");
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
      <h1><?php if ($_SESSION["id"] == $user) {
            echo "Ma ";
          } ?>Watchlist<?php if ($_SESSION["id"] !== $user) {
                          echo ' de "<strong>' . $username['username'] . '</strong>"';
                        } ?></h1>
    </div>

    <!-- Boutons de gestion -->
    <div class="btn-toolbar text-center justify-content-center d-grid gap-3 d-md-flex">
      <?php
      echo '<a href="/index.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/home.png" alt="Accueil" title="Accueil" height="30" width="32" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
      echo '<a href="/src/views/profile.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/user.png" title="Utilisateur" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Profil</a>';
      ?>
    </div>
  </header>

  <main>
    <?php
    // Appel de la fonction de comptage des médias de la watchlist
    $total_rows = count_my_watchlist($user);
    // Affichage du résultat non nul
    if ($total_rows >= 1) {
      $msg_result = "$total_rows favori(s)";
    }
    // Affichage du résultat nul
    else {
      $msg_result = "\n <hr/> Aucun favori";
    }
    ?>
    <div class="alert">
      <h2><?php echo "\n" . $msg_result ?></h2>
    </div>

    <?php
    // Appel de la fonction de sélection des médias type film de la watchlist
    $movies = select_my_movie($user);
    if (!empty($movies)) {
    ?>
      <table class="tbl-qa tbl-user justify-content-center table-responsive" border="3">
        <thead>
          <tr>
            <th class="table-header title" colspan="5">Film(s)</th>
          </tr>
          <tr>
            <th class="table-header" width="20%">Affiche</th>
            <th class="table-header" width="25%">Titre</th>
            <th class="table-header" width="20%">Date de sortie</th>
            <th class="table-header" width="20%">Date d'ajout</th>
            <th class="table-header" width="15%">Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <?php
          foreach ($movies as $row) {
            // Formatage des dates en français
            $french_release_date = utf8_encode(strftime('%e %B %Y', strtotime($row->premiered)));
            $french_save_date = utf8_encode(strftime('%e %B %Y &agrave; %Hh%M', strtotime($row->created_at)));
          ?>
            <tr class="table-row">
              <td id="td-image">
                <img src="/src/thumbnails/<?= $row->cachedurl ?>" title="<?= $row->cachedurl ?>" alt="<?= $row->title ?>" height="216" width="144" />
              </td>
              <td id="td-title"><?= $row->title ?></td>
              <td id="td-text"><?= $french_release_date ?></td>
              <td id="td-italic"><?= $french_save_date ?></td>
              <td id="td-actions">
                <a class="ajax-action-links" href='/src/views/viewpage.php?type=movie&id=<?= $row->idMovie ?>' target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/view.png" title="Voir" height="17" width="30" /></a>
                <a onclick="$('#dialog-example_<?= $row->idMovie ?>').modal('show');" class="ajax-action-links" class="btn-show-modal" href="#" data-toggle="modal" draggable="false" ondragstart="return false"><img src="/assets/img/delete.png" title="Supprimer" height="25" width="18" /></a>
              </td>
            </tr>
            <!-- Boîte de dialogue de suppression -->
            <div id="dialog-example_<?= $row->idMovie ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content" id="dialog-example_<?= $row->idMovie ?>">
                  <div class="modal-header">
                    <h3 class="modal-title">Confirmation de suppression</h3>
                  </div>
                  <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir le retirer de votre watchlist ?</p>
                    <p class="modal-media-title">"<strong><?= $row->title ?></strong>"</p>
                  </div>
                  <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn btn-info" onclick="$('#dialog-example_<?= $row->idMovie ?>').modal('hide');">Non</a>
                    <a href='/src/database/delete.php?user=<?= $user ?>&type=movie&id=<?= $row->idMovie ?>' class="btn btn-danger" id="<?= $row->idMovie ?>">Oui</a>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </tbody>
      </table>
    <?php
    }
    ?>

    <?php
    // Appel de la fonction de sélection des médias type série de la watchlist
    $tvshows = select_my_tvshow($user);
    if (!empty($tvshows)) {
    ?>
      <table class="tbl-qa tbl-user justify-content-center table-responsive" border="3">
        <thead>
          <tr>
            <th class="table-header title" colspan="5">Série(s)</th>
          </tr>
          <tr>
            <th class="table-header" width="20%">Affiche</th>
            <th class="table-header" width="25%">Titre</th>
            <th class="table-header" width="20%">Date de sortie</th>
            <th class="table-header" width="20%">Date d'ajout</th>
            <th class="table-header" width="15%">Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <?php
          foreach ($tvshows as $row) {
            // Formatage des dates en français
            $french_release_date = utf8_encode(strftime('%e %B %Y', strtotime($row->premiered)));
            $french_save_date = utf8_encode(strftime('%e %B %Y &agrave; %Hh%M', strtotime($row->created_at)));
          ?>
            <tr class="table-row">
              <td id="td-image">
                <img src="/src/thumbnails/<?= $row->cachedurl ?>" title="<?= $row->cachedurl ?>" alt="<?= $row->title ?>" height="216" width="144" />
              </td>
              <td id="td-title"><?= $row->title ?></td>
              <td id="td-text"><?= $french_release_date ?></td>
              <td id="td-italic"><?= $french_save_date ?></td>
              <td id="td-actions">
                <a class="ajax-action-links" href='/src/views/viewpage.php?type=tvshow&id=<?= $row->idShow ?>' target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/view.png" title="Voir" height="17" width="30" /></a>
                <a onclick="$('#dialog-example_<?= $row->idShow ?>').modal('show');" class="ajax-action-links" class="btn-show-modal" href="#" data-toggle="modal" draggable="false" ondragstart="return false"><img src="/assets/img/delete.png" title="Supprimer" height="25" width="18" /></a>
              </td>
            </tr>
            <!-- Boîte de dialogue de suppression -->
            <div id="dialog-example_<?= $row->idShow ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content" id="dialog-example_<?= $row->idShow ?>">
                  <div class="modal-header">
                    <h3 class="modal-title">Confirmation de suppression</h3>
                  </div>
                  <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir le retirer de votre watchlist ?</p>
                    <p class="modal-media-title">"<strong><?= $row->title ?></strong>"</p>
                  </div>
                  <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn btn-info" onclick="$('#dialog-example_<?= $row->idShow ?>').modal('hide');">Non</a>
                    <a href='/src/database/delete.php?user=<?= $user ?>&type=tvshow&id=<?= $row->idShow ?>' class="btn btn-danger" id="<?= $row->idShow ?>">Oui</a>
                  </div>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </tbody>
      </table>
    <?php
    }
    ?>

    <!-- Flèche retour au début -->
    <button class="scrollToTopBtn">☝️</button>
    <script src="/assets/js/to-top.js"></script>

  </main>

</body>

</html>