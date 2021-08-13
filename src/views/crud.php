<?php
/////////////////////////////////////////
// CRUD (Create, Read, Update, Delete) //
/////////////////////////////////////////

// Initialisation de la session
session_start();

// Vérification si l'administrateur est bien connecté
if (!isset($_SESSION["loggedadmin"]) || $_SESSION["loggedadmin"] !== TRUE) {
  // Redirection si l'administrateur n'est pas connecté
  header("location:/index.php");
  exit;
}

// Définition de la page en cours
if (isset($_GET['page']) && !empty($_GET['page'])) {
  $currentPage = (int) strip_tags($_GET['page']);
} else {
  $currentPage = 1;
}

// Fuseau horaire
date_default_timezone_set('EUROPE/Paris');
// Traduction de la date en français
setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';

// Appel du script du moteur de recherche
require dirname(__DIR__) . '/database/search.php';
?>

<html>

<head>
  <title>Gestionnaire de médias</title>

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

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
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
      <h1>Gestionnaire de médias</h1>
    </div>

    <!-- Boutons de gestion -->
    <div class="btn-toolbar text-center justify-content-center d-grid gap-3 d-md-flex">
      <?php
      echo '<a href="/index.php" class="button_link"><img src="/assets/img/home.png" title="Accueil" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Accueil</a>';
      echo '<a href="/src/views/profile.php" class="button_link"><img src="/assets/img/user.png" title="Utilisateur" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Profil</a>';
      echo '<a href="/src/templates/forms/media-form.php?action=add" class="button_link"><img src="/assets/img/add.png" title="Ajouter" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Créer un média</a>';
      if (isset($_GET['search']) && !empty($_GET['search'])) {
        echo '<a href="crud.php" class="button_link"><img src="/assets/img/list.png" title="Liste" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Retour à la liste</a>';
      }
      ?>
    </div>
  </header>

  <main>
    <!-- Bloc de recherche -->
    <div class="container-search">
      <form action="" method="get">
        <div class="search-box">
          <button class="btn-search"><i class="fas fa-search"></i></button>
          <input name="search" type="text" class="input-search" placeholder="Saisissez un titre...">
        </div>
      </form>
    </div>
    <?php if (isset($_GET['search']) && !empty($_GET['search'])) { ?>
      <!-- Résultats de la recherche -->
      <div class="alert">
        <h2><?php echo "\n" . $msg_result ?></h2>
      </div>
    <?php } ?>

    <?php
    if (empty($_GET['search'])) {
      // Appel de la fonction de sélection des 40 derniers médias type film ajoutés
      $result = select_last_movie();
    }
    if (!empty($result)) {
    ?>
      <table class="tbl-qa justify-content-center table-responsive" border="3">
        <thead>
          <tr>
            <th class="table-header" width="2%">#</th>
            <th class="table-header" width="20%">Titre</th>
            <th class="table-header" width="50%">Synopsis</th>
            <th class="table-header" width="10%">Date de sortie</th>
            <th class="table-header" width="8%">Affiche</th>
            <th class="table-header" width="10%">Actions</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <?php
          foreach ($result as $row) {
            // Formatage de la date en français
            $french_date = utf8_encode(strftime('%e %B %Y', strtotime($row->premiered)));
          ?>
            <tr class="table-row">
              <td id="td-id"><?= $row->idMovie ?></td>
              <td id="td-title"><?= $row->title ?></td>
              <td id="td-italic">
                <div class="max-lines"><?= $row->synopsis ?></div>
              </td>
              <td id="td-text"><?= $french_date ?></td>
              <td id="td-image">
                <img src="/src/thumbnails/<?= $row->cachedurl ?>" title="<?= $row->cachedurl ?>" alt="<?= $row->title ?>" height="216" width="144" />
              </td>
              <td id="td-actions">
                <a class="ajax-action-links" href='/src/views/viewpage.php?type=movie&id=<?= $row->idMovie ?>' target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/view.png" title="Voir" height="17" width="30" /></a>
                <a class="ajax-action-links" href='/src/templates/forms/media-form.php?id=<?= $row->idMovie ?>&action=edit' draggable="false" ondragstart="return false"><img src="/assets/img/edit.png" title="Éditer" height="25" width="24" /></a>
                <a class="ajax-action-links" href='/src/templates/forms/media-form.php?id=<?= $row->idMovie ?>&action=copy' draggable="false" ondragstart="return false"><img src="/assets/img/copy.png" title="Copier" height="25" width="22" /></a>
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
                    <p>Êtes-vous sûr de vouloir supprimer ce média ?</p>
                    <p class="modal-media-title">"<strong><?= $row->title ?></strong>"</p>
                  </div>
                  <div class="modal-footer">
                    <a href="#" data-dismiss="modal" class="btn btn-info" onclick="$('#dialog-example_<?= $row->idMovie ?>').modal('hide');">Non</a>
                    <a href='/src/database/delete.php?type=movie&id=<?= $row->idMovie ?>' class="btn btn-danger" id="<?= $row->idMovie ?>">Oui</a>
                  </div>
                </div>
              </div>
            </div>
        <?php
          }
        }
        ?>
        </tbody>
      </table>

      <?php
      if (isset($count) && $count >= 1) { ?>
        <!-- Pagination -->
        <div class="pagination-container">
          <ul class="pagination">
            <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
            <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
              <a href="./crud.php?search=<?= $query ?>&page=<?= $currentPage - 1 ?>" class="page-link">«</a>
            </li>
            <?php
            for ($page = 1; $page <= $pages; $page++) : ?>
              <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
              <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                <a href="./crud.php?search=<?= $query ?>&page=<?= $page ?>" class="page-link"><?= $page ?></a>
              </li>
            <?php endfor ?>
            <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
            <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
              <a href="./crud.php?search=<?= $query ?>&page=<?= $currentPage + 1 ?>" class="page-link">»</a>
            </li>
          </ul>
        </div>
      <?php } ?>

      <!-- Flèche retour au début -->
      <button class="scrollToTopBtn">☝️</button>
      <script src="/assets/js/to-top.js"></script>

  </main>

</body>

</html>