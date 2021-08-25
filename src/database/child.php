<?php
///////////////////
// Profil enfant //
///////////////////

if (isset($_SESSION["logged"]) && $_SESSION["genre"] == 2) {
  $set_child = TRUE;
  $sqlWhereChild = "WHERE profile=2";
  $sqlAndChild = "AND profile=2";
  $sqlAndMovieAliasChild = "AND m.profile=2";
  $sqlAndSetAliasChild = "AND s.profile=2";
  $sqlGenresChild = "AND name NOT LIKE '%Action%' AND name NOT LIKE '%Crime%' AND name NOT LIKE '%Drame%' AND name NOT LIKE '%Guerre%' AND name NOT LIKE '%Horreur%' AND name NOT LIKE '%Thriller%'";
} else {
  $set_child = FALSE;
  $sqlWhereChild = $sqlAndChild = $sqlAndMovieAliasChild = $sqlAndSetAliasChild = $sqlGenresChild = '';
}
