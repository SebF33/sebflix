<!------------------------->
<!-- Formulaire du média -->
<!------------------------->

<?php
// Initialisation de la session
session_start();

// Vérification si l'administrateur est bien connecté
if (!isset($_SESSION["loggedadmin"]) || $_SESSION["loggedadmin"] !== TRUE) {
  // Redirection si l'administrateur n'est pas connecté
  header("location:/index.php");
  exit;
}

// Appel du script du formulaire
require dirname(__DIR__, 2) . '/database/data-form.php';
// Appel des listes des options
require dirname(__DIR__, 2) . '/database/options.php';

// Type du formulaire
if ($action == 'add') {
  $type = 'Nouvel enregistrement';
  $h2 = 'Nouveau média';
  $save = 'Ajouter';
} elseif ($action == 'copy') {
  $type = 'Copier enregistrement';
  $h2 = 'Copie de média';
  $save = 'Ajouter';
} elseif ($action == 'edit') {
  $type = 'Éditer enregistrement';
  $h2 = 'Édition du média';
  $save = 'Enregistrer';
}
// Traitement de l'affichage des données
if ($action == 'copy' or $action == 'edit') {
  $title = $result['title'];
  $synopsis = $result['synopsis'];
  $genre = $result['genre'];
  $premiered = $result['premiered'];
  if (!isset($rating['rating'])) {
    $rating = '';
  } else {
    $rating = $rating['rating'];
  }
  $age = $result['classification'];
  $country = $result['country'];
  $embed = $result['embed'];
  $embed = substr($embed, 30);
  $catch = $result['catch'];
  $poster = $result['cachedurl'];
  if (isset($bg['cachedurl'])) {
    $background = $bg['cachedurl'];
  }
} else {
  $catch = $embed = $premiered = $synopsis = $title = '';
}
?>

<html>

<head>
  <title><?= $type ?></title>

  <link rel="stylesheet" href="/assets/css/lib/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/design.css">
  <link rel="stylesheet" href="/assets/css/results.css">
  <link rel="stylesheet" href="/assets/css/crud.css">
</head>

<body>
  <?php
  // Background selon le type du formulaire
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
    <h2 class="demo-form-heading"><?= $h2 ?></h2>

    <!-- Message d'alerte -->
    <?php if (isset($_GET['msg'])) :
      $error = $_GET['error'];
      if ($error == 'false') {
        $alert = 'success';
      } elseif ($error == 'true') {
        $alert = 'danger';
      } else {
        $alert = 'secondary';
      }
    ?>
      <div class="alert alert-<?= $alert ?>" role="alert">
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
        <input name="save_record" type="submit" value="<?= $save ?>" class="btn demo-form-submit">
      </div>

      <div class="demo-form-row">
        <label>Titre <span>*</span> : </label><br>
        <input name="title" type="text" class="demo-form-field" value="<?= $title ?>" required />
      </div>

      <div class="demo-form-row">
        <label>Synopsis <span>*</span> : </label>
        <br>
        <textarea name="synopsis" class="demo-form-field" rows="1" required><?= $synopsis ?></textarea>
      </div>

      <div class="demo-form-row">
        <label>Genre(s) <span>*</span> : </label><br>
        <select name="genre" class="genre">
          <?php
          for ($i = 0; $i < count($genres); $i++) {
            if ($genres[$i] == $genre) {
              $genreSelected = ' selected';
            } else {
              $genreSelected = '';
            }
          ?> <option value="<?php echo $genres[$i]; ?>" <?php echo $genreSelected; ?>><?php echo $genres[$i]; ?></option>
          <?php
          }
          ?>
        </select>
      </div>

      <div class="demo-form-row">
        <label>Date de sortie <span>*</span> : </label>
        <br>
        <input name="premiered" type="date" class="demo-form-field" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?= $premiered ?>" required />
      </div>

      <div class="demo-form-row">
        <label>Note sur 10 : </label>
        <input name="rating" class="number" type="number" step="0.1" min="0" max="10" class="demo-form-field" value="<?= $rating ?>" />
      </div>

      <div class="demo-form-row">
        <label>Âge : </label>
        <select name="age" class="age">
          <?php
          for ($i = 0; $i < count($ages); $i++) {
            if ($ages[$i] == $age) {
              $ageSelected = ' selected';
            } else {
              $ageSelected = '';
            }
          ?> <option value="<?php echo $ages[$i]; ?>" <?php echo $ageSelected; ?>><?php echo $ages[$i]; ?></option>
          <?php
          }
          ?>
        </select>
      </div>

      <div class="demo-form-row">
        <label>Pays : </label>
        <select name="country" class="country">
          <?php
          for ($i = 0; $i < count($countries); $i++) {
            if ($countries[$i] == $country) {
              $countrySelected = ' selected';
            } else {
              $countrySelected = '';
            }
          ?> <option value="<?php echo $countries[$i]; ?>" <?php echo $countrySelected; ?>><?php echo $countries[$i]; ?></option>
          <?php
          }
          ?>
        </select>
      </div>

      <div class="demo-form-row">
        <label>Bande-annonce :<br><span>https://www.youtube.com/embed/</span></label>
        <input name="embed" class="embed" type="text" class="demo-form-field" value="<?= $embed ?>" />
      </div>

      <div class="demo-form-row">
        <label>Phrase d'accroche : </label>
        <br>
        <input name="catch" type="text" class="demo-form-field" value="<?= $catch ?>" />
      </div>

      <div class="demo-form-row">
        <label for="poster">Poster : </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576"> <!-- Poids maxi : 1Mo => 1024*1024 -->
        <input id="picture-file" type="file" name="poster">
      </div>
      <?php if ($action == 'edit') {
        // Affiche
        echo '<div class="demo-form-row text-center"><img class="form-poster" src="/src/thumbnails/' . $poster . '" title="' . $poster . '" alt="' . $poster . '" onclick="window.open(this.src)" draggable="false" ondragstart="return false")/></div>';
      } ?>

      <div class="demo-form-row">
        <label for="background">Fond : </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576"> <!-- Poids maxi : 1Mo => 1024*1024 -->
        <input id="picture-file" type="file" name="background">
      </div>
      <?php if ($action == 'edit' && isset($background)) {
        // Fond
        echo '<div class="demo-form-row text-center"><img class="form-background" src="/src/thumbnails/' . $background . '" title="' . $background . '" alt="' . $background . '" onclick="window.open(this.src)" draggable="false" ondragstart="return false")/></div>';
      } ?>

    </form>
  </div>

</body>

</html>