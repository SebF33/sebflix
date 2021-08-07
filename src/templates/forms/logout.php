<?php
/////////////////
// DÉCONNEXION //
/////////////////

// Initialisation de la session
session_start();

// Annulation de toutes les variables de session
unset($_SESSION);

// Destruction de la session
session_destroy();

// Redirection vers la page de connexion
header("location:/index.php");
exit;
