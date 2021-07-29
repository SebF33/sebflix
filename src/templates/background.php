<style type="text/css">
  body {
    background-image: url(<?php if (($type == 'collection' or $type == 'moviecast' or $type == 'tvshowcast') && !empty($fanart['cachedurl'])) {
                            echo '"/src/thumbnails/' . $fanart['cachedurl'] . '"';
                          } elseif ($type == 'filmography' and $id == '26276') {
                            echo '"/img/bg_chaplin.png"';
                          } elseif ($type == 'achievement' or $type == 'actors' or $type == 'beststudios' or $type == 'direction' or $type == 'genres' or $type == 'movies' or $type == 'sets' or $type == 'studios' or $type == 'tvshows') {
                            echo '"/img/bg_' . $type . '.png"';
                          } elseif ($category == 'animation' or $category == 'movies' or $category == 'spectacles' or $category == 'tvshows') {
                            echo '"/img/bg_' . $category . '.png"';
                          } else {
                            echo '"/img/bg.png"';
                          }

                          echo ');
background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }
</style>';
