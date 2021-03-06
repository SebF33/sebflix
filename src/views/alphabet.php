<!------------------------>
<!-- Page de l'alphabet -->
<!------------------------>

<?php
// Initialisation de la session
session_start();
// Profil enfant
require dirname(__DIR__) . '/database/child.php';
// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';
// Appel du script de validation des données
require dirname(__DIR__) . '/database/validation.php';

// Définition des valeurs autorisées dans le GET
$categories = array('movies', 'tvshows', 'animes', 'animation', 'spectacles');

// Vérification du GET ('category' obligatoire)
if (isset($_GET['category']) && !empty($_GET['category']) && in_array($_GET['category'], $categories)) {
  // Traitement du GET 'category'
  $category = valid_get($_GET['category']);
  // Titre, type et requête de sélection selon la catégorie définie
  if ($category == 'movies') {
    $title = "Films";
    $type = "movie";
    $result = select_seven_recent_movie($sqlAndChild);
  } elseif ($category == 'tvshows') {
    $title = "Séries";
    $type = "tvshow";
    $result = select_seven_recent_tvshow($sqlAndChild);
  } elseif ($category == 'animes') {
    $title = "Animes";
    if ($set_child) {
      $type = "movie";
      $result = select_seven_recent_anime_movie($sqlAndChild);
    } else {
      $type = "tvshow";
      $result = select_seven_recent_anime_tvshow();
    }
  } elseif ($category == 'animation') {
    $title = "Dessins animés";
    $type = "movie";
    $result = select_seven_recent_animation($sqlAndChild);
  } elseif ($category == 'spectacles') {
    $title = "Spectacles";
    $type = "movie";
    $result = select_seven_recent_spectacle($sqlAndChild);
  }
  if ($set_child) {
    if ($category == 'tvshows') {
      $last = 'Dernières ';
    } else {
      $last = 'Derniers ';
    }
  } else {
    $last = '';
  }
  $h1 = $last . $title;
} else {
  // Redirection si un GET n'est pas vérifié
  header("location:/index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title><?= $title ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Alphabet">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="/assets/css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/style.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/footer.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/alphabet.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/menu.css" type="text/css" media="screen">
  <link rel="stylesheet" href="/assets/css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="/assets/img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="/assets/img/favicon-192.png">

  <!-- Appel de jQuery -->
  <script src="/assets/js/lib/jquery-3.6.0.min.js"></script>

  <!-- Appel des icônes sur Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
</head>

<!-- Corps de page -->

<body>
  <?php
  // Background selon la catégorie définie
  include "../templates/background.php";
  ?>

  <!-- Header -->
  <?php
  // Construction de la barre de navigation
  if (!$set_child) {
    if ($category == 'movies') {
      $navLeft = [
        'Comédie' => '/src/views/table.php?category=' . $category . '&genre=comedy',
        'Thriller' => '/src/views/table.php?category=' . $category . '&genre=thriller',
        'Science-Fiction' => '/src/views/table.php?category=' . $category . '&genre=sf',
        'Horreur' => '/src/views/table.php?category=' . $category . '&genre=horror'
      ];
      $navRight = [
        'Guerre' => '/src/views/table.php?category=' . $category . '&genre=war',
        'Péplum' => '/src/views/table.php?category=' . $category . '&genre=peplum',
        'Western' => '/src/views/table.php?category=' . $category . '&genre=western',
        'Arts Martiaux' => '/src/views/table.php?category=' . $category . '&genre=martial'
      ];
      $btnNavClass = " btn-type-movie";
    } elseif ($category == 'tvshows') {
      $navLeft = [
        'Science-Fiction' => '/src/views/table.php?category=' . $category . '&genre=sf',
        'Thriller' => '/src/views/table.php?category=' . $category . '&genre=thriller'
      ];
      $navRight = [
        'Horreur' => '/src/views/table.php?category=' . $category . '&genre=horror',
        'Animation' => '/src/views/table.php?category=' . $category . '&genre=animation'
      ];
      $btnNavClass = "";
    } elseif ($category == 'animes') {
      $navLeft = [
        'Shōnen' => '/src/views/table.php?category=' . $category . '&genre=shonen'
      ];
      $navRight = [
        'Seinen' => '/src/views/table.php?category=' . $category . '&genre=seinen'
      ];
      $btnNavClass = "";
    } elseif ($category == 'animation') {
      $navLeft = [
        'Familial' => '/src/views/table.php?category=' . $category . '&genre=familial'
      ];
      $navRight = [
        'Courts-métrages' => '/src/views/table.php?category=' . $category . '&genre=short'
      ];
      $btnNavClass = "";
    } elseif ($category == 'spectacles') {
      $navLeft = [
        'One-man-show' => '/src/views/table.php?category=' . $category . '&genre=man'
      ];
      $navRight = [
        'One-woman-show' => '/src/views/table.php?category=' . $category . '&genre=woman'
      ];
      $btnNavClass = "";
    }
  } else {
    $navLeft = $navRight = "";
  }
  include "../templates/navigation.php"
  ?>

  <!-- Main content -->
  <main class="mainAlphabet">

    <div class="mainAlphabetTop">
      <h1 class="alphabet-title"><?= $h1 ?></h1>
      <?php if (!$set_child) { ?>
        <ul class="alphabet">
          <?php
          echo '<li><a href="table.php?category=' . $category . '&letter=numeric" class="letters" draggable="false" ondragstart="return false"><span class="&">&</span></a></li>';
          foreach (range('A', 'N') as $i) {
            echo '<li><a href="table.php?category=' . $category . '&letter=' . strtolower($i) . '" class="letters" draggable="false" ondragstart="return false"><span class="' . $i . '">' . $i . '</span></a></li>';
          }
          echo "<br></br>";
          foreach (range('O', 'Z') as $i) {
            echo '<li><a href="table.php?category=' . $category . '&letter=' . strtolower($i) . '" class="letters" draggable="false" ondragstart="return false"><span class="' . $i . '">' . $i . '</span></a></li>';
          }
          ?>
        </ul>
        <div class="select-box">
          <select onchange="location = this.value">
            <optgroup>
              <?php
              echo '<option style="font-family: truculenta" value="table.php?category=' . $category . '&letter=numeric">&</option>';
              foreach (range('A', 'Z') as $i) {
                echo '<option style="font-family: truculenta" value="table.php?category=' . $category . '&letter=' . strtolower($i) . '">' . $i . '</option>';
              }
              ?>
            </optgroup>
          </select>
        </div>
      <?php } ?>
    </div>

    <div class="mainAlphabetBottom">
      <?php
      // Créer un tableau à 1 colonne
      echo
      "<table class='tType' border='1'>
          <tr>
          </tr>";
      // Afficher dans le tableau les données appelées
      if ($type == 'movie') {
        foreach ($result as $row) {
          echo '<td><a href="viewpage.php?type=' . $type . '&id=' . $row['idMovie'] . '" draggable="false" ondragstart="return false"><img src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="240" width="160" draggable="false" ondragstart="return false"/></a></td>';
        }
      } elseif ($type == 'tvshow') {
        foreach ($result as $row) {
          echo '<td><a href="viewpage.php?type=' . $type . '&id=' . $row['idShow'] . '" draggable="false" ondragstart="return false"><img src="../thumbnails/' . $row['cachedurl'] . '" alt="' . $row['title'] . '" height="240" width="160" draggable="false" ondragstart="return false"/></a></td>';
        }
      }
      echo "</tr>";
      echo "</table>";
      ?>
    </div>
    <!-- Menu circulaire -->
    <?php include "../templates/menu.html"; ?>
  </main>

  <!-- Footer -->
  <?php include "../templates/footer.php" ?>

</body>

</html>