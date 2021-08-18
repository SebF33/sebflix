<!------------------------->
<!-- Formulaire du média -->
<!------------------------->

<?php
// Initialisation de la session
session_start();

// Utilisation de l'encodage interne UTF-8
mb_internal_encoding("UTF-8");

// Vérification si l'administrateur est bien connecté
if (!isset($_SESSION["loggedadmin"]) || $_SESSION["loggedadmin"] !== TRUE) {
  // Redirection si l'administrateur n'est pas connecté
  header("location:/index.php");
  exit;
}

// Appel du script du formulaire
require dirname(__DIR__, 2) . '/database/data-form.php';
?>

<html>

<head>
  <title><?php if ($action == 'add') {
            echo 'Nouvel enregistrement';
          } elseif ($action == 'copy') {
            echo 'Copier enregistrement';
          } elseif ($action == 'edit') {
            echo 'Éditer enregistrement';
          } ?>
  </title>

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">
</head>

<body>
  <?php
  // Background selon la catégorie définie
  include "../background.php";
  ?>

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
      echo '<a href="/src/views/crud.php" class="button_link" draggable="false" ondragstart="return false"><img src="/assets/img/list.png" title="List" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Retour à la liste</a>';
      ?>
    </div>
  </header>

  <div class="frm-add">
    <h2 class="demo-form-heading"><?php if ($action == 'add') {
                                    echo 'Nouveau média';
                                  } elseif ($action == 'copy') {
                                    echo 'Copie de média';
                                  } elseif ($action == 'edit') {
                                    echo 'Édition du média';
                                  } ?>
    </h2>

    <?php if (isset($_GET['msg'])) :
      $error = $_GET['error'];
    ?>
      <!-- Message d'alerte -->
      <div class="alert alert-<?php if ($error == 'false') {
                                echo 'success';
                              } elseif ($error == 'true') {
                                echo 'danger';
                              } else {
                                echo 'secondary';
                              } ?>" role="alert">
        <?php echo $_GET['msg']; ?>
      </div>
    <?php endif; ?>

    <?php if ($action == 'edit') { ?>
      <div class="text-center">
        <a class="ajax-action-links" href='/src/views/viewpage.php?type=movie&id=<?= $result['idMovie'] ?>' target="_blank" draggable="false" ondragstart="return false"><img src="/assets/img/view.png" title="Voir" height="17" width="30" /></a>
      </div>
    <?php } ?>
    <form name="frmAdd" action="" method="POST" enctype="multipart/form-data">
      <div class="demo-form-row text-center">
        <input name="save_record" type="submit" value="<?php if ($action == 'add' or $action == 'copy') {
                                                          echo 'Ajouter';
                                                        } elseif ($action == 'edit') {
                                                          echo 'Enregistrer';
                                                        } ?>" class="btn demo-form-submit">
      </div>
      <div class="demo-form-row">
        <label>Titre * : </label><br>
        <input name="title" type="text" class="demo-form-field" value="<?php if ($action == 'copy' or $action == 'edit') {
                                                                          echo $result['title'];
                                                                        } ?>" required />
      </div>
      <div class="demo-form-row">
        <label>Synopsis * : </label>
        <br>
        <textarea name="synopsis" class="demo-form-field" rows="1" required><?php if ($action == 'copy' or $action == 'edit') {
                                                                              echo $result['synopsis'];
                                                                            } ?></textarea>
      </div>
      <div class="demo-form-row">
        <label>Phrase d'accroche : </label>
        <br>
        <input name="catch" type="text" class="demo-form-field" value="<?php if ($action == 'copy' or $action == 'edit') {
                                                                          echo $result['catch'];
                                                                        } ?>" />
      </div>
      <div class="demo-form-row">
        <label>Date de sortie * : </label>
        <br>
        <input name="premiered" type="date" class="demo-form-field" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php if ($action == 'copy' or $action == 'edit') {
                                                                                                                            echo $result['premiered'];
                                                                                                                          } ?>" required />
      </div>
      <div class="demo-form-row">
        <label for="poster">Poster : </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576"> <!-- Poids maxi : 1Mo => 1024*1024 -->
        <input id="picture-file" type="file" name="poster">
      </div>
      <?php if ($action == 'edit') {
        echo '<div class="demo-form-row text-center"><img src="/src/thumbnails/' . $result['cachedurl'] . '" title="' . $result['cachedurl'] . '" alt="' . $result['cachedurl'] . '" onclick="window.open(this.src)" draggable="false" ondragstart="return false")/></div>';
      } ?>
    </form>
  </div>

</body>

</html>