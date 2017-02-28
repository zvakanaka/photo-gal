<?php
//for older php servers:
require('lib/password.php');

function login($username, $password) {
  global $db;
  $statement = $db->prepare('SELECT * FROM users
                             WHERE username=:username');
  $statement->bindValue(':username', $username);
  $statement->execute();
  while ($row = $statement->fetch()) {
    if (password_verify($password, $row['password'])) {
        return true;
    }
  }
  return false;
}

function is_admin($user_id) {
    global $db;
    $query = 'SELECT * FROM users
              WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(":user_id", $user_id);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();
    if ($user['is_admin']) {
      return true;
    }
    return false;
}

function get_users() {
  global $db;
  $query = 'SELECT * FROM users';
  $statement = $db->prepare($query);
  $statement->execute();
  return $statement;
}

function get_user_id($username) {
  global $db;
  $query = 'SELECT user_id FROM users
            WHERE username = :username';
  $statement = $db->prepare($query);
  $statement->bindValue(":username", $username);
  $statement->execute();
  $user_id = $statement->fetch();
  return $user_id['user_id'];
}

function get_user_name($user_id) {
  global $db;
  $query = 'SELECT username FROM users
            WHERE user_id = :user_id';
  $statement = $db->prepare($query);
  $statement->bindValue(":user_id", $user_id);
  $statement->execute();
  $user_id = $statement->fetch();
  return $user_id['username'];
}

function get_user_email($user_id) {
  global $db;
  $query = 'SELECT email FROM users
            WHERE user_id = :user_id';
  $statement = $db->prepare($query);
  $statement->bindValue(":user_id", $user_id);
  $statement->execute();
  $user_id = $statement->fetch();
  return $user_id['email'];
}

function insert_user($firstname, $lastname, $username, $password, $email) {
  global $db;
	$user_exists = false;
	$exists_stmt = $db->prepare('SELECT username FROM users
                               WHERE username=:username');
	$exists_stmt->bindValue(":username", $username);
	$exists_stmt->execute();
  $user_exists = false;
	while ($row = $exists_stmt->fetch()) {
		$user_exists = true;
	}
	if ($user_exists == true) {
		return false;
	} else {
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $query = 'INSERT INTO users VALUES
              (NULL, :firstname, :lastname, :username, :password, :email, 0)';
		$statement = $db->prepare($query);
    $statement->bindValue(":firstname", $firstname);
    $statement->bindValue(":lastname", $lastname);
		$statement->bindValue(":username", $username);
		$statement->bindValue(":password", $password_hash);
    $statement->bindValue(':email', $email);
		$statement->execute();
    $statement->closeCursor();
    return true;
	}
}

function delete_user($user_id) {
    global $db;
    $query = 'DELETE FROM users
              WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $statement->closeCursor();
}

function set_admin($user_id) {
  global $db;
  $query = 'UPDATE users SET
            is_hidden = TRUE
            WHERE user_id = :user_id';
  $statement = $db->prepare($query);
  $statement->bindValue(":user_id", $user_id);
  $statement->execute();
  $statement->closeCursor();
}

function unset_admin($user_id) {
  global $db;
  $query = 'UPDATE users SET
            is_hidden = FALSE
            WHERE user_id = :user_id';
  $statement = $db->prepare($query);
  $statement->bindValue(":user_id", $user_id);
  $statement->execute();
  $statement->closeCursor();
}

function get_favorites($user_id) {
 global $db;
 $query = 'SELECT * FROM faves
           WHERE user_id = :user_id';
 $stmnt = $db->prepare($query);
 $stmnt->bindValue(':user_id', $user_id);
 $stmnt->execute();
 $faves = $stmnt->fetchAll();
 $stmnt->closeCursor();
 return $faves;
}

function get_album_favorites($user_id, $album_name) {
 global $db;
 $query = 'SELECT * FROM faves
           WHERE album_name = :album_name
           AND user_id = :user_id';
 $stmnt = $db->prepare($query);
 $stmnt->bindValue(':user_id', $user_id);
 $stmnt->bindValue(':album_name', $album_name);
 $stmnt->execute();
 $faves = $stmnt->fetchAll();
 $stmnt->closeCursor();
 return $faves;
}

function insert_favorite($album_name, $photo_name, $user_id)  {
 global $db;
 $query = 'INSERT INTO faves VALUES (
          NULL, :user_id, :photo_name, :album_name, 0, 0
          )';
 $stmnt = $db->prepare($query);
 $stmnt->bindValue(':user_id', $user_id);
 $stmnt->bindValue(':album_name', $album_name);
 $stmnt->bindValue(':photo_name', $photo_name);
 $stmnt->execute();
 $stmnt->closeCursor();
}

function unfavorite($album_name, $photo_name, $user_id) {
 global $db;
 $query = 'DELETE FROM faves
           WHERE user_id = :user_id
           AND album_name = :album_name
           AND photo_name = :photo_name';
 $stmnt = $db->prepare($query);
 $stmnt->bindValue(':user_id', $user_id);
 $stmnt->bindValue(':album_name', $album_name);
 $stmnt->bindValue(':photo_name', $photo_name);
 $stmnt->execute();
 $stmnt->closeCursor();
}

function set_reviewed($user_id) {
 global $db;
 $query = 'UPDATE faves SET
           reviewed = 1
           WHERE user_id = :user_id';
 $stmnt = $db->prepare($query);
 $stmnt->bindValue(':user_id', $user_id);
 $stmnt->execute();
 $stmnt->closeCursor();
}
?>
