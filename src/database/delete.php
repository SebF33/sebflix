<?php
/////////////////////////////
// SUPPRESSION DES DONNÉES //
/////////////////////////////

// Appel du script d'administration des données
require __DIR__ . '/datamanager.php';

// Déclaration et nettoyage des variables d'entrée
$id = htmlspecialchars($_GET['id']);

// Appel de la fonction de suppression des données
delete_bio_by_id($id);

// Redirection
header('location:/src/views/crud.php');
