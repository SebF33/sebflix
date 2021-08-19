<?php
////////////////////////////////////////////////
// Requêtes d'administration des utilisateurs //
////////////////////////////////////////////////

// Appel du script d'administration des données
require __DIR__ . '/datamanager.php';

// Sélection de tous les utilisateurs
function select_all_user()
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT * FROM users ORDER BY id DESC");
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Sélection d'un utilisateur par son identifiant
function select_user_by_id(int $id)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT * FROM users WHERE id=:id");

    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Sélection d'un utilisateur par son pseudo
function select_username(string $username)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT * FROM users WHERE username=:username");

    $query->bindValue(':username', $username, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Mise à jour du rôle par son identifiant
function update_role_by_id(int $role, int $id)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("UPDATE users SET role=:role WHERE id=:id");
    $req->bindValue(':role', $role, PDO::PARAM_INT);
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Sélection d'une adresse mail
function select_email(string $email)
{
  connexion($dbco);
  try {
    $query = $dbco->prepare("SELECT email FROM users WHERE email=:email");

    $query->bindValue(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    return $result;
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Création d'un nouvel utilisateur
function add_user(string $username, string $genre, string $email, string $password, string $avatar)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("INSERT INTO users(username, email, password, genre, avatar)
    VALUES (:username, :email, :password, :genre, :avatar)");

    $req->bindValue(':username', $username, PDO::PARAM_STR);
    $req->bindValue(':genre', $genre, PDO::PARAM_STR);
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->bindValue(':password', $password, PDO::PARAM_STR);
    $req->bindValue(':avatar', $avatar, PDO::PARAM_STR);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Mise à jour du mot de passe
function update_pwd(string $username, string $pwd)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("UPDATE users SET password=:password WHERE username=:username");

    $req->bindValue(':username', $username, PDO::PARAM_STR);
    $req->bindValue(':password', $pwd, PDO::PARAM_STR);
    return $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Suppression d'un utilisateur
function delete_user(int $id)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare("DELETE FROM users
    WHERE id=:id");

    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}

// Suppression d'un média type film de la watchlist
function delete_my_movie(int $user, int $media, string $type)
{
  connexion($dbco);
  try {
    $req = $dbco->prepare(
      "DELETE FROM watchlist WHERE user_id = :user AND media_id = :media AND media_type = :type"
    );
    $req->bindValue(':user', $user, PDO::PARAM_INT);
    $req->bindValue(':media', $media, PDO::PARAM_INT);
    $req->bindValue(':type', $type, PDO::PARAM_STR);
    $req->execute();
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
}
