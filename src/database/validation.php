<?php
////////////////////////////
// VALIDATION DES DONNÉES //
////////////////////////////

// Nettoyage v.1
function valid_get($data)
{
  $data = trim($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Nettoyage v.2
// Convertit tous les caractères éligibles en entités HTML
// Convertit les guillemets doubles et les guillemets simples
function valid_data($data)
{
  return htmlentities(trim($data), ENT_QUOTES);
}

// Décoder et ré-encoder pour éviter le surencodage
function valid_data_to_display($data)
{
  $data = html_entity_decode($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Mettre le premier caractère d'une chaîne en majuscule-multioctet
function mb_ucfirst($string)
{
  return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
}
