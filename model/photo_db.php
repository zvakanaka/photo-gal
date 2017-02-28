<?php
function get_hidden_albums() {
    global $db;
    $query = 'SELECT * FROM hidden_albums
              WHERE is_hidden = 1
              ORDER BY hidden_album_id';
    $statement = $db->prepare($query);
    $statement->execute();
    return $statement;
}

function hide_album($album_name, $hidden) {
  global $db;

  if (album_exists($album_name)) {
    $query = 'UPDATE hidden_albums SET
              is_hidden = :hidden
              WHERE album_name = :album_name';
    $statement = $db->prepare($query);
    $statement->bindValue(":album_name", $album_name);
    $statement->bindValue(":hidden", $hidden);
    $statement->execute();
    $statement->closeCursor();
  } else {
    $query = 'INSERT INTO hidden_albums VALUES
              ( NULL
              , :album_name
              , :hidden)';
    $statement = $db->prepare($query);
    $statement->bindValue(":album_name", $album_name);
    $statement->bindValue(":hidden", $hidden);
    $statement->execute();
    $statement->closeCursor();
  }
}

function album_exists($album_name) {
  global $db;
  $query = 'SELECT * FROM hidden_albums
            WHERE album_name = :album_name';
  $statement = $db->prepare($query);
  $statement->bindValue(":album_name", $album_name);
  $statement->execute();
  while ($row = $statement->fetch()) {
    error_log("HEYEHYEHEYEYEYEY!");
    $statement->closeCursor();
    return true;
  }
  $statement->closeCursor();
  
  error_log("This is false HEYEHYEHEYEYEYEY! for ".$album_name);
  return false;
}

function get_hidden_images() {
  global $db;
  $query = 'SELECT * FROM hidden_images
            WHERE is_hidden = 1
            ORDER BY hidden_image_id';
  $statement = $db->prepare($query);
  $statement->execute();
  return $statement;
}

function hide_image($image_name) {
  global $db;
  if (image_exists($image_name)) {
    $query = 'UPDATE hidden_images SET
              is_hidden = 1
              WHERE image_name = :image_name';
    $statement = $db->prepare($query);
    $statement->bindValue(":image_name", $image_name);
    $statement->execute();
    $statement->closeCursor();
  } else {
    $query = 'INSERT INTO hidden_images VALUES
              ( NULL
              , :image_name
              , 1)';
    $statement = $db->prepare($query);
    $statement->bindValue(":image_name", $image_name);
    $statement->execute();
    $statement->closeCursor();
  }
}

function image_exists($image_name) {
  global $db;
  $query = 'SELECT * FROM hidden_images
            WHERE image_name = :image_name';
  $statement = $db->prepare($query);
  $statement->bindValue(":image_name", $image_name);
  $statement->execute();
  $hidden_image = $statement->fetch();
  $statement->closeCursor();
  if (sizeof($hidden_image) !== 0) {
    return false;
  }
  return true;
}
?>
