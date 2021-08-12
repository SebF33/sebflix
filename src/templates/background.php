<style type="text/css">
  body {
    background-image: url(<?php if (isset($type) && isset($fanart['cachedurl']) && ($type == 'collection' or $type == 'moviecast' or $type == 'tvshowcast') && !empty($fanart['cachedurl'])) {
                            echo '"/src/thumbnails/' . $fanart['cachedurl'] . '"';
                          } elseif (isset($type) && isset($id) && ($type == 'filmography' and $id == '26276')) {
                            // Charlie Chaplin
                            echo '"/img/bg_chaplin.png"';
                          } elseif (isset($type) && ($type == 'achievement' or $type == 'actors' or $type == 'beststudios' or $type == 'direction' or $type == 'genres' or $type == 'movies' or $type == 'sets' or $type == 'studios' or $type == 'tvshows')) {
                            echo '"/img/bg_' . $type . '.png"';
                          } elseif (isset($category) && ($category == 'animation' or $category == 'movies' or $category == 'spectacles' or $category == 'tvshows')) {
                            echo '"/img/bg_' . $category . '.png"';
                          } elseif (isset($action) && ($action == 'add' or $action == 'copy' or $action == 'edit')) {
                            echo '"/assets/img/bg_' . $action . '.png"';
                          } else {
                            // Background par défaut
                            echo '"/img/bg.png"';
                          }

                          echo ');
background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
  }
</style>';
