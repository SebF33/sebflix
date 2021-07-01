<!------------------------------------>
<!-- Connexion à la base de données -->
<!------------------------------------>

<?php
// Appel du script de config
include("config.php");
    // Connexion à la base de données
    $col = "mysql:host=$host;dbname=$db_name";
        // Tentative de connexion
        try {
            $db = new PDO($col , "$db_user", "$db_psw");
            // Charset=UTF-8
            $db->exec('SET NAMES utf8');
            }
            // Gestion des erreurs
            catch(PDOException $e) {
            echo 'Attention erreur: '.$e->getMessage();
            }      
?>