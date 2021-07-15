<?php
// Carrousel

// Appel du script d'affichage des données
require dirname(__DIR__) . '/database/viewmanager.php';

// Appel des fonctions de sélection aléatoire de 21 médias
$randMovie = select_eighteen_random_movie();
$randTvshow = select_three_random_tvshow();

// Affichage dans le carrousel des données appelées
foreach ($randMovie as $row) {
    echo '<div class="my-card"><a class="my-btn-card" href="src/views/viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" height="288" width="192"/></a></div>';
}
foreach ($randTvshow as $row) {
    echo '<div class="my-card"><a class="my-btn-card" href="src/views/viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img src="src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" height="288" width="192"/></a></div>';
}
?>
<script src="/js/carousel.js"></script>