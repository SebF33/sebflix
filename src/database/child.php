<?php
///////////////////
// Profil enfant //
///////////////////

if (isset($_SESSION["logged"]) && $_SESSION["genre"] == 2) {
  $set_child = TRUE;
  $sqlWhereChild = "WHERE profile=2";
  $sqlAndChild = "AND profile=2";
  $sqlAndAliasChild = "AND s.profile=2";
} else {
  $set_child = FALSE;
  $sqlWhereChild = $sqlAndChild = $sqlAndAliasChild = "";
}
