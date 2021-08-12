<?php

// Appel du script de connexion à la base de données
require_once __DIR__ . "/../../database/connect.php";

if (!empty($_POST["id"]) && !empty($_POST["type"]) && !empty($_POST["user"])) {
  $id = (int)$_POST["id"];
  $type = $_POST["type"];
  $user = (int)$_POST["user"];
  connexion($dbco);
  try {
    $req = $dbco->prepare(
      "DELETE FROM watchlist WHERE user_id = :user AND media_id = :id AND media_type = :type"
    );
    $req->bindValue(':user', $user, PDO::PARAM_INT);
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':type', $type, PDO::PARAM_STR);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
  exit();
}
