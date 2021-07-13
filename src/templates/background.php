<style type="text/css">
  body {
    background-image: url(<?php
                          if ($type == 'collection' && !empty($fanart['cachedurl'])) {
                            echo '"/src/thumbnails/' . $fanart['cachedurl'] . '"';
                          } elseif ($type == 'movies' or $type == 'tvshows' or $type == 'sets') {
                            echo '"/img/bg_' . $type . '.png"';
                          } elseif ($category == 'movies' or $category == 'tvshows') {
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
