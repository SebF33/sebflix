<?php
///////////////////////////////////////////
// Requêtes d'administration des données //
///////////////////////////////////////////

// Appel du script de connexion à la base de données
require __DIR__ . '/connect.php';

// Ajout d'un média type film
function add_movie(array $datas)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("INSERT INTO movie(title, synopsis, catch, premiered)
		VALUES(:title, :synopsis, :catch, :premiered);
		INSERT INTO pictures(id_product, picture)
		VALUES(LAST_INSERT_ID(), :picture)");

    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':synopsis', $datas['synopsis'], PDO::PARAM_STR);
    $req->bindValue(':catch', $datas['catch'], PDO::PARAM_STR);
    $req->bindValue(':premiered', $datas['premiered'], PDO::PARAM_STR);
    $req->bindValue(':picture', $datas['picture'], PDO::PARAM_STR);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Fonction de sélection de tous les articles
function select_all_bio()
{
  connexion($dbco);
  try {
    $query = $dbco->query("SELECT *
    FROM products prod
    INNER JOIN pictures pic 
		ON prod.id = pic.id_product
    ORDER BY prod.year DESC");

    return $query->fetchAll(PDO::FETCH_OBJ);
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Fonction de sélection et de tri de tous les articles
function select_all_bio_sort(string $sort)
{
  connexion($dbco);
  try {
    if ($sort == 'ASC') {
      $query = $dbco->prepare("SELECT * FROM products ORDER BY price ASC");
    } else {
      $query = $dbco->prepare("SELECT * FROM products ORDER BY price DESC");
    }

    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// [A REVOIR] Fonction de mise à jour d'un article par son identifiant
function update_bio_by_id(array $datas, int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT picture FROM pictures WHERE id_product=:id");
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    if ($result != "generic.png" or !empty($result)) {
      $file_exists = TRUE; // Le fichier existe
    } else {
      $file_exists = FALSE; // Le fichier n'existe pas
    }

    $sql_file = $file_exists ? "; UPDATE pictures SET picture=:picture WHERE id_product=:id" : "";

    $req = $dbco->prepare("UPDATE products
    SET title=:title, description=:description, autor=:autor, price=:price
    WHERE id=:id;
    $sql_file");
    $req->bindValue(':title', $datas['title'], PDO::PARAM_STR);
    $req->bindValue(':description', $datas['description'], PDO::PARAM_STR);
    $req->bindValue(':autor', $datas['autor'], PDO::PARAM_STR);
    $req->bindValue(':price', $datas['price'], PDO::PARAM_STR);
    if ($file_exists) {
      $req->bindValue(':picture', $datas['picture'], PDO::PARAM_STR);
    }
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Fonction de suppression d'un article par son identifiant
function delete_bio_by_id(int $id)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("DELETE FROM products
    WHERE id=:id;
    DELETE FROM pictures
    WHERE id_product=:id");

    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
