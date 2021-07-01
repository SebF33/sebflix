<!---------------------->
<!-- Rubrique "Films" -->
<!---------------------->

<?php
// Appeler le script de connexion
require 'connect.php';
?>

<?php
// Requête de sélection MySQL
$query = $db->prepare(
  "SELECT *
    FROM movie
    INNER JOIN art ON movie.idMovie = art.media_id
    WHERE media_type = 'movie'
    AND type = 'poster'
    AND c14 NOT LIKE '%Animation%'
    AND c14 NOT LIKE '%Anime%'
    AND c14 NOT LIKE '%Spectacle%'
    ORDER BY premiered DESC
    LIMIT 7"
);
$query->execute();
$result = $query->fetchall();
?>

<!DOCTYPE html>
<html lang="fr">

<!-- En-tête -->

<head>
  <title>Films</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="Description" content="Films">

  <!-- Appel des feuilles de styles -->
  <link rel="stylesheet" href="../css/design.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/style.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/alphabet.css" type="text/css" media="screen">
  <link rel="stylesheet" href="../css/responsive.css" type="text/css" media="screen">

  <!-- Appel de l'icône -->
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="48x48" href="../img/favicon-48.png">
  <link data-vue-tag="ssr" rel="icon" type="image/png" sizes="192x192" href="../img/favicon-192.png">

  <!-- Appel des scripts JavaScript -->
  <script src="../js/jquery-3.6.0.min.js"></script>

  <!-- Appel de la police "Truculenta" sur Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Truculenta:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<!-- Corps de page -->

<body>

  <!-- Header -->
  <header>
    <nav role='navigation'>
      <ul>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=28">Comédie</a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=14">Thriller</a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=6">Science-Fiction</a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=26">Horreur</a></li>
        <li id="icon-trigger" class="icon icon-trigger"><a class="btn-icon" href="../index.php">
            <svg version="1.1" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 166.4 166" width="40" height="40" style="enable-background:new 0 0 166.4 166;" xml:space="preserve">
              <style type="text/css">
                .st0 {
                  fill: #CE3E3E;
                }

                .st1 {
                  fill: #FBF5D9;
                }

                .st2 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #EBE4DA;
                }

                .st3 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #FFEDD1;
                }

                .st4 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #EFD6AB;
                }

                .st5 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #DEBF85;
                }

                .st6 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #FFF5E6;
                }

                .st7 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #C7A35F;
                }

                .st8 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #FFFCF7;
                }

                .st9 {
                  fill-rule: evenodd;
                  clip-rule: evenodd;
                  fill: #F4DEB8;
                }
              </style>
              <g>
                <path class="st0" d="M31.2,166c-5.3,0-9.9-1-13.8-3c-3.9-2-7.1-4.8-9.6-8.4c-2.5-3.6-4.4-7.8-5.6-12.7c-1.2-4.9-1.8-10.3-1.8-16.1
                c0-2.3,0.1-4.7,0.2-7.1c0.2-2.4,0.5-4.8,0.9-7.3h17.3c-0.4,2.5-0.7,4.8-1,7.1c-0.2,2.2-0.3,4.4-0.3,6.4c0,4.2,0.5,7.8,1.4,10.9
                c0.9,3.1,2.3,5.5,4.2,7.3c1.9,1.8,4.4,2.7,7.5,2.7c3.6,0,6.5-0.8,8.6-2.4c2.1-1.6,3.6-3.8,4.5-6.4c0.9-2.6,1.3-5.6,1.3-8.8
                c0-3.3-0.5-6-1.4-8.4c-1-2.3-2.3-4.4-4-6.4c-1.7-1.9-3.7-3.8-6.1-5.6c-2.3-1.8-4.8-3.8-7.5-5.9c-3.3-2.5-6.5-5.3-9.5-8.4
                c-3-3.1-5.8-6.8-8.2-11c-2.5-4.3-4.4-9.2-5.9-15C0.7,61.8,0,55.1,0,47.4c0-9,1.2-17,3.5-24.1c2.3-7.1,5.9-12.8,10.7-16.9
                S25.1,0,32.4,0c7.8,0,13.9,2.1,18.5,6.3c4.5,4.2,7.8,9.8,9.7,16.9c2,7.1,3,15.2,3,24.4c0,2.6-0.1,5.3-0.2,8
                c-0.2,2.7-0.3,5.4-0.6,8.1c-0.2,2.7-0.5,5.5-0.8,8.2H43.6c0.4-4.2,0.7-8.3,1-12.3c0.2-4,0.3-7.8,0.3-11.4c0-5.4-0.4-10.2-1.1-14.3
                c-0.7-4.1-2.1-7.3-3.9-9.6c-1.9-2.3-4.6-3.5-8.1-3.5c-2.5,0-4.5,0.7-6.2,2c-1.7,1.3-3.1,3.1-4.2,5.5c-1.1,2.3-1.9,5-2.4,8.1
                c-0.5,3.1-0.7,6.4-0.7,10c0,5.9,0.7,10.8,2.1,14.7c1.4,3.9,3.4,7.4,6,10.3c2.6,2.9,5.5,5.7,8.7,8.1c3.5,2.6,6.9,5.3,10.2,8.1
                c3.3,2.8,6.3,5.9,9.1,9.4c2.8,3.5,5,7.6,6.6,12.3c1.6,4.7,2.4,10.5,2.4,17.3c0,7.1-1.1,13.7-3.4,19.6c-2.3,6-5.8,10.7-10.5,14.3
                C44.7,164.2,38.6,166,31.2,166z" />
                <path class="st1" d="M106.2,164.8c0.1-9.1,0.3-17.5,0.4-25.1c0.1-7.6,0.2-16.3,0.3-25.8c0.1-9.6,0.1-19.2,0.1-29
                c0-9.9-0.1-19.8-0.2-29.7c-0.1-9.9-0.3-18.9-0.5-27.1c-0.2-8.2-0.4-17.2-0.5-27.1l24.8,3c-0.1,7.6-0.2,14.4-0.2,20.6
                c0,6.2,0,12.9-0.1,20.3c-0.1,7.3-0.1,14.5-0.2,21.5c-0.1,7-0.1,13.2-0.1,18.7c0,5.6,0,11.9,0.1,18.9c0.1,7,0.1,14.1,0.1,21.2
                c0,7.1,0,13.6,0.1,19.6c0.1,5.9,0.2,12.6,0.3,19.8L106.2,164.8z M115.8,23.7L105.8,1L166,2.9l-0.6,20.6L115.8,23.7z M117,108V85.1
                l49,0.7l0.4,21.1L117,108z" />
              </g>
              <g>
                <path class="st2" d="M72.7,105c-0.5,0-0.9-0.1-1.4-0.2c-4-0.9-6.9-4.2-7.5-8.5c-0.5-3.3,0.4-6.4,2.3-8.6c-2.7-3.5-3.1-8.7-1.7-12.5
                c1.4-3.8,4.4-5.9,8-5.6c1.2,0.1,2.4,0.5,3.7,1.1c2.1-3.6,6.6-9.9,12.8-9.4c3.5,0.3,6.8,2.9,9.6,7.7c4.4,7.4,5.5,12.7,3.4,16.2
                c-0.7,1.2-1.7,2-2.7,2.5c2.4,3.2,5.5,8.5,4.2,12.8c-0.6,1.9-2.2,4.4-6.7,5.5c-2.2,0.5-4.2,0.7-6.1,0.5c-4.6-0.4-7.5-2.9-9.3-5.5
                C79.5,103.2,76.7,105.3,72.7,105z" />
                <path class="st3" d="M74.8,71c0,0,9.4-20.6,19.5-3.8c10.1,16.8-1.5,16.2-1.5,16.2s13.6,14.3,1.2,17.4c-12.4,3-14.7-7.9-14.7-7.9
                s-2.6,8.2-9.5,6.6C63,97.9,62.1,88,67.5,84.8C60.5,79.9,63.9,63,74.8,71z" />
                <path class="st4" d="M85.4,76.5c0.4,0.9,0.3,1.9,0.5,2.9c0.5,2.6,3.5,4.5,3.1,7.1c-0.2,1.9-2.2,3.2-4.1,3.7c-3,0.8-6.2,0.3-8.8-1.2
                c-2.2-4.4-0.6-10.2,3.5-12.9c0.9-0.6,2-1.1,3.1-1.1C83.8,74.9,84.9,75.5,85.4,76.5z" />
                <path class="st5" d="M78,77.9l3.3-0.8c0.1,0,0.2,0,0.2-0.1c0.7-0.2,5.1-1.3,4.5,1.9c-0.6,3.5-2,4.8-0.7,7.3c1.3,2.6-7.7,0-9.2,2
                c-1,1.3-1.6-2.4-1.9-5.1c-0.2-1.7,0.6-3.4,2-4.4l0,0C76.9,78.3,77.4,78.1,78,77.9z" />
                <path class="st6" d="M98,78.3c0,0.6-0.1,1.3-0.5,1.8c-0.7,1-2.3,1.1-3.3,0.3c-1-0.7-1.5-1.9-1.8-3.1c-0.2-1.2-0.3-2.5-0.6-3.6
                c-1-3.5-4.8-6.1-8.4-5.6c1.6-2.1,4.5-3,7.1-2.4c2.6,0.6,4.7,2.6,5.9,4.9C97.8,72.9,98.1,75.6,98,78.3z" />
                <path class="st6" d="M83.7,96.1c2.7,0.5,5.5,1,8.2,1.5c1-1.9,1.7-3.9,1.9-6.1c0.8,0.1,1.5,0.3,2.3,0.4c1.6,1.8,1.5,4.9-0.1,6.8
                c-1.6,1.9-4.3,2.6-6.7,1.9C86.8,100.1,84.9,98.3,83.7,96.1z" />
                <path class="st6" d="M68.4,89.3c-1,2-1.4,4.3-0.8,6.4c0.6,2.1,2.4,3.9,4.6,4.2c2.2,0.3,4.6-1.1,5.1-3.3
                C73.7,95.2,70.5,92.6,68.4,89.3z" />
                <path class="st7" d="M83.4,81.4c-0.6-0.6-1.5-0.3-2.3,0c-0.7,0.3-1.4,0.6-1.9,1.2c-0.5,0.6-0.6,1.5-0.1,2.1c0.7,0.8,2,0.2,2.9-0.3
                c0.5-0.3,1.1-0.7,1.4-1.2C83.8,82.7,83.9,81.9,83.4,81.4z" />
                <path class="st8" d="M92.7,98.4c1.3-2.4,3.6-4.1,4.8-6.4c1,1.4,1.4,3.3,0.9,4.9c-0.5,1.7-1.8,3-3.5,3.6c-0.9,0.3-2.2,0.2-2.5-0.7
                C92.3,99.3,92.5,98.8,92.7,98.4z" />
                <path class="st9" d="M89.7,61.8C81.5,56.3,74.8,71,74.8,71c-10.9-8-14.4,8.9-7.4,13.8c-4.3,2.6-4.6,9.3-1.1,12.8
                c-0.2-2.5-0.1-5,0.5-7.4c0.5-2.1,1.3-4.2,1.1-6.3c-0.4-2.8-2.5-6-0.5-8c1.6-1.6,4.3-0.7,6.5-1.3c3-0.8,4.2-4.3,5.9-6.8
                C82,64.4,85.8,62.3,89.7,61.8z" />
              </g>
            </svg>
          </a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=24">Guerre</a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=44">Péplum</a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=7">Western</a></li>
        <li><a class="btn-type btn-type-movie" href="movies/display-results-genre.php?id=43">Arts Martiaux</a></li>
      </ul>
    </nav>
    <script src="../js/icon.js"></script>
  </header>

  <!-- Main content -->
  <main class="mainAlphabet">

    <!-- Bouton retour en arrière -->
    <?php include "../src/exit.html"; ?>

    <div class="mainAlphabetTop">
      <h1 class="hType">Films</h1>
      <ul class="alphabet">
        <li><a href="movies/display-results-numbers.php" class="letters">&</span></a></li>
        <li><a href="movies/display-results.php?id=a" class="letters"><span class="A">A</span></a></li>
        <li><a href="movies/display-results.php?id=b" class="letters"><span class="B">B</span></a></li>
        <li><a href="movies/display-results.php?id=c" class="letters"><span class="C">C</span></a></li>
        <li><a href="movies/display-results.php?id=d" class="letters"><span class="D">D</span></a></li>
        <li><a href="movies/display-results.php?id=e" class="letters"><span class="E">E</span></a></li>
        <li><a href="movies/display-results.php?id=f" class="letters"><span class="F">F</span></a></li>
        <li><a href="movies/display-results.php?id=g" class="letters"><span class="G">G</span></a></li>
        <li><a href="movies/display-results.php?id=h" class="letters"><span class="H">H</span></a></li>
        <li><a href="movies/display-results.php?id=i" class="letters"><span class="I">I</span></a></li>
        <li><a href="movies/display-results.php?id=j" class="letters"><span class="J">J</span></a></li>
        <li><a href="movies/display-results.php?id=k" class="letters"><span class="K">K</span></a></li>
        <li><a href="movies/display-results.php?id=l" class="letters"><span class="L">L</span></a></li>
        <li><a href="movies/display-results.php?id=m" class="letters"><span class="M">M</span></a></li>
        <li><a href="movies/display-results.php?id=n" class="letters"><span class="N">N</span></a></li>
        <br></br>
        <li><a href="movies/display-results.php?id=o" class="letters"><span class="O">O</span></a></li>
        <li><a href="movies/display-results.php?id=p" class="letters"><span class="P">P</span></a></li>
        <li><a href="movies/display-results.php?id=q" class="letters"><span class="Q">Q</span></a></li>
        <li><a href="movies/display-results.php?id=r" class="letters"><span class="R">R</span></a></li>
        <li><a href="movies/display-results.php?id=s" class="letters"><span class="S">S</span></a></li>
        <li><a href="movies/display-results.php?id=t" class="letters"><span class="T">T</span></a></li>
        <li><a href="movies/display-results.php?id=u" class="letters"><span class="U">U</span></a></li>
        <li><a href="movies/display-results.php?id=v" class="letters"><span class="V">V</span></a></li>
        <li><a href="movies/display-results.php?id=w" class="letters"><span class="W">W</span></a></li>
        <li><a href="movies/display-results.php?id=x" class="letters"><span class="X">X</span></a></li>
        <li><a href="movies/display-results.php?id=y" class="letters"><span class="Y">Y</span></a></li>
        <li><a href="movies/display-results.php?id=z" class="letters"><span class="Z">Z</span></a></li>
      </ul>
    </div>

    <div class="mainAlphabetBottom">
      <?php
      // Créer un tableau à 1 colonne
      echo
      "<table class='tType' border='1'>
          <tr>
          </tr>";

      // Afficher dans le tableau les données appelées
      foreach ($result as $row) {
        echo '<td><a href="viewpage.php?id=' . $row['idMovie'] . '"><img src="../src/thumbnails/' . $row['cachedurl'] . '" alt="' . $row['c00'] . '" height="240" width="160"/></a></td>';
      }
      echo "</tr>";
      echo "</table>";
      ?>
    </div>
  </main>

  <!-- Footer -->
  <?php include "footer.php" ?>

</body>

</html>