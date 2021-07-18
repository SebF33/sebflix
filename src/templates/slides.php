<ul class="slides">
    <?php
    // Slides

    // Appel du script d'affichage des données
    require dirname(__DIR__) . '/database/viewmanager.php';

    // Appel des fonctions de sélection aléatoire de 12 médias
    $randMovie = select_ten_random_movie();
    $randTvshow = select_two_random_tvshow();
    foreach ($randMovie as $row) {
        echo '<li><a href="viewpage.php?type=movie&id=' . $row['idMovie'] . '"><img src="/src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="1920" height="1080"/></a></li>';
    }
    foreach ($randTvshow as $row) {
        echo '<li><a href="viewpage.php?type=tvshow&id=' . $row['idShow'] . '"><img src="/src/thumbnails/' . $row['cachedurl'] . '" title="' . $row['title'] . '" alt="' . $row['title'] . '" width="1920" height="1080"/></a></li>';
    }
    ?>
</ul>
<script src="/js/flexslider.js"></script>