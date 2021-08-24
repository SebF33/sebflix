<?php
///////////////
// Carrousel //
///////////////

// Initialisation de la session
session_start();
// Profil enfant
if (isset($_SESSION["logged"]) && $_SESSION["genre"] == 2) {
  $sqlWhereChild = "WHERE profile=2";
  $sqlAndChild = "AND profile=2";
} else {
  $sqlAndChild = $sqlWhereChild = "";
}

// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';

// Appel des fonctions de sélection aléatoire de 21 médias
$randMovie = select_eighteen_random_movie($sqlAndChild);
$randTvshow = select_three_random_tvshow($sqlAndChild);
// Affichage dans le carrousel des données appelées
foreach ($randMovie as $row) {
  echo '<div class="my-card"><a class="my-btn-card" href="src/views/viewpage.php?type=movie&id=' . $row['idMovie'] . '" draggable="false" ondragstart="return false"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" height="288" width="192" draggable="false" ondragstart="return false"/></a></div>';
}
foreach ($randTvshow as $row) {
  echo '<div class="my-card"><a class="my-btn-card" href="src/views/viewpage.php?type=tvshow&id=' . $row['idShow'] . '" draggable="false" ondragstart="return false"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" height="288" width="192" draggable="false" ondragstart="return false"/></a></div>';
}
?>
<script src="/assets/js/carousel.js"></script>