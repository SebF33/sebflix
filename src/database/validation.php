<?php
////////////////////////////
// VALIDATION DES DONNÉES //
////////////////////////////

// Nettoyage v.0 (à virer)
function valid_get($data)
{
  $data = trim($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Nettoyage
function valid_data($data)
{
  return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
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
