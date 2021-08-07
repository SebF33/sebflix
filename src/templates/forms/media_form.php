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

// Appel du script de validation
require __DIR__ . '/validation.php';
// Appel du script des requêtes
require __DIR__ . '/datamanager.php';
// Appel du script du formulaire
require __DIR__ . '/form_data.php';
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

  <!-- Appel des polices "Truculenta" et "Roboto" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <div class="col text-center mt-4">
      <a href="/index.php">
        <img src="/img/logo_sebflix.png" alt="Sebflix" width="190.8" height="66.8" />
      </a>
      <h1>Gestionnaire d'articles</h1>
    </div>
    <div class="btn-toolbar mx-4">
      <?php
      echo '<a href="/src/views/crud.php" class="button_link"><img src="/assets/img/list.png" title="List" height="30" width="30" />' . str_repeat('&nbsp;', 2) . 'Retour à la liste</a>';
      ?>
    </div>
  </header>

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

  <div class="frm-add">
    <h2 class="demo-form-heading"><?php if ($action == 'add') {
                                    echo 'Nouvel enregistrement';
                                  } elseif ($action == 'copy') {
                                    echo 'Copier enregistrement';
                                  } elseif ($action == 'edit') {
                                    echo 'Éditer enregistrement';
                                  } ?>
    </h2>

    <form name="frmAdd" action="" method="POST" enctype="multipart/form-data">
      <div class="demo-form-row">
        <label>Titre : </label><br>
        <input name="title" type="text" class="demo-form-field" value="<?php if ($action == 'copy' or $action == 'edit') {
                                                                          echo $result['title'];
                                                                        } ?>" required />
      </div>
      <div class="demo-form-row">
        <label>Synopsis : </label>
        <br>
        <textarea name="synopsis" class="demo-form-field" rows="2" required><?php if ($action == 'copy' or $action == 'edit') {
                                                                              echo $result['synopsis'];
                                                                            } ?></textarea>
      </div>
      <div class="demo-form-row">
        <label>Phrase d'accroche : </label>
        <br>
        <input name="catch" type="text" class="demo-form-field" value="<?php if ($action == 'copy' or $action == 'edit') {
                                                                          echo $result['catch'];
                                                                        } ?>" required />
      </div>
      <div class="demo-form-row">
        <label>Date de sortie : </label>
        <br>
        <input name="premiered" type="date" class="demo-form-field" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php if ($action == 'copy' or $action == 'edit') {
                                                                                                                            echo $result['premiered'];
                                                                                                                          } ?>" required />
      </div>
      <div class="demo-form-row">
        <label for="picture">Image : </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576"> <!-- Poids maxi : 1Mo => 1024*1024 -->
        <input type="file" id="picture" name="picture">
      </div>
      <?php if ($action == 'edit') {
        echo '<div class="demo-form-row"><img src="/src/thumbnails/' . $result['picture'] . '" title="' . $result['picture'] . '" alt="' . $result['picture'] . '" /></div>';
      } ?>
      <div class="demo-form-row">
        <input name="save_record" type="submit" value="<?php if ($action == 'add' or $action == 'copy') {
                                                          echo 'Ajouter';
                                                        } elseif ($action == 'edit') {
                                                          echo 'Enregistrer';
                                                        } ?>" class="demo-form-submit">
      </div>
    </form>
  </div>

</body>

</html>