<?php
require('lib/load_config.php');
require('model/database.php');
require('model/photo_db.php');
require('model/user_db.php');
require('model/photo_fs.php');
require('lib/exec.php');
require('lib/string_tools.php');

session_start();

$action = filter_input(INPUT_POST, 'action');
if ($action == NULL) {
  $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
  if ($action == NULL) {
    $action = 'home';
  }
}

if ($action == 'register') {
  include('views/login-register.php');
  die();
} else if ($action == 'home') {
  $album_blacklist = array();
  $hidden_albums = get_hidden_albums();
  foreach ($hidden_albums as $album) {
    $album_blacklist[] = $album['album_name'];
  }
  $show_hidden = filter_input(INPUT_GET, 'hidden', FILTER_SANITIZE_STRING);
  $qs_photo = filter_input(INPUT_GET, 'photo', FILTER_SANITIZE_STRING);
  if ($show_hidden == 'true') {
    $albums = get_albums($photo_dir, array());
    include('views/home.php');
    die();
  } else {
    $albums = get_albums($photo_dir, $album_blacklist);
    include('views/home.php');
    die();
  }
} else if ($action == 'users') {
  if (isset($_SESSION["is_admin"])) {
    $users = get_users();
    include('views/users.php');
    die();
  }
} else if ($action == 'insert_user') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
  $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
  $username = filter_input(INPUT_POST, 'new-username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'new-password', FILTER_SANITIZE_STRING);
  $confirm_password = filter_input(INPUT_POST, 'confirm-new-password', FILTER_SANITIZE_STRING);
  if ($username == NULL || $username == FALSE ||
          $firstname == NULL || $firstname == FALSE ||
          $lastname == NULL || $lastname == FALSE ||
          $password == NULL || $password == FALSE ||
          $confirm_password == NULL || $confirm_password == FALSE ||
          $email == NULL || $email == FALSE) {
      $error = "Invalid user data. Check all fields and try again.";
      include('views/login-register.php');
      die();
  } else if ($password != $confirm_password) {
    $error = "Password and confirmation does not match. Try again.";
    include('views/login-register.php');
    die();
  } else {
    if (insert_user($firstname, $lastname, $username, $password, $email) == TRUE) {
      $_SESSION["logged_in"] = $username;
      $user_id = get_user_id($username);
      $_SESSION["user_id"] = $user_id;
      if (is_admin($user_id)) {
        $_SESSION["is_admin"] = true;
      }
      header("Location: .?action=home");
    } else {
      $error = "Account already exists.";
      include('views/login-register.php');
      die();
    }
  }
} else if ($action == 'delete_user') {
  $user_id = filter_input(INPUT_POST, 'user_id',
        FILTER_VALIDATE_INT);
  if ($user_id == NULL || $user_id == FALSE) {
      $error = "Missing user id.";
  }
  delete_user($user_id);
  if (isset($_SESSION["is_admin"])) {
    $users = get_users();
    include('views/users.php');
    die();
  }
} else if ($action == 'album') {
  $album = filter_input(INPUT_GET, 'album', FILTER_SANITIZE_STRING);
  if ($album == NULL) {
    $error = "No album. Try again.";
    //TODO: redirect to home
  }
  $image_blacklist = array();
  $hidden_images = get_hidden_images();
  foreach ($hidden_images as $image) {
    $image_blacklist[] = $image['image_name'];
  }
  $hidden_albums = get_hidden_albums();
  $album_blacklist = array();
  foreach ($hidden_albums as $hidden_album) {
    $album_blacklist[] = $hidden_album['album_name'];
  }
  if (in_array($album, $album_blacklist)) {
    $message = "This album is unlisted. Anyone with the link can see it.";
  }
  $qs_photo = filter_input(INPUT_GET, 'photo', FILTER_SANITIZE_STRING);
  $show_hidden = filter_input(INPUT_GET, 'hidden', FILTER_SANITIZE_STRING);
  if ($show_hidden == 'true') {
    $images = get_images($photo_dir, $album, array());
    include('views/album.php');
    die();
  } else {
    $images = get_images($photo_dir, $album, $image_blacklist);
    include('views/album.php');
    die();
  }
} else if ($action == 'authenticate') {
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($username == NULL || $username == FALSE || $password == NULL ||
          $password == FALSE) {
      $error = "Invalid user data. Check all fields and try again.";
      include('views/login-register.php');
      die();
  }

  if (login($username, $password)) {
    $_SESSION["logged_in"] = $username;
    $user_id = get_user_id($username);
    $_SESSION["user_id"] = $user_id;
    if (is_admin($user_id)) {
      $_SESSION["is_admin"] = true;
    }
    header("Location: .?action=home");
  } else {
    $error = "Password/username mismatch. Please try again, unless you are a hacker.";
    include('views/login-register.php');
    die();
  }
} else if ($action == 'logout') {
  session_unset();
  session_destroy();
  session_write_close();
  setcookie(session_name(),'',0,'/');
  echo "Logged out, just a sec...";
	header( "Refresh:1; url=.?action=home", true, 303);
	die();
} else if ($action == 'hide_album') {
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);
  hide_album($album_name, 1);
  header("Location: .?action=album&album=$album_name");
}  else if ($action == 'unhide_album') {
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);
  hide_album($album_name, 0);
  header("Location: .?action=album&album=$album_name");
} else if ($action == 'dslr') {
  $albums = get_albums($photo_dir, array());
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  include('views/dslr.php');
} else if ($action == 'download_from_dslr') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $new_album = filter_input(INPUT_POST, 'new_album', FILTER_SANITIZE_STRING);
  if ($new_album == NULL || $new_album == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    include('views/dslr.php');
    die();
  } else {
    $cmd = 'bash scripts/download_from_dslr.sh '.escapeshellarg($new_album).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
  }
  $albums = get_albums($photo_dir, array());
  $message = "Breathe in. Breathe out. Repeat until photos are downloaded to ".$new_album.".";
  include('views/dslr.php');
  die();
} else if ($action == 'download_and_process') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $new_album = filter_input(INPUT_POST, 'new_album', FILTER_SANITIZE_STRING);
  if ($new_album == NULL || $new_album == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    include('views/dslr.php');
    die();
  } else {
    $cmd = 'bash scripts/download_and_process.sh '.escapeshellarg($new_album).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
  }
  $albums = get_albums($photo_dir, array());
  $message = "Breathe in. Breathe out. Repeat until photos are downloaded to ".$new_album.".";
  include('views/dslr.php');
  die();
} else if ($action == 'optimize') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $optimization_type = filter_input(INPUT_POST, 'optimization_type', FILTER_SANITIZE_STRING);
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);

  $albums = get_albums($photo_dir, array());

  if ($optimization_type == NULL || $optimization_type == FALSE ||
      $album_name == NULL || $album_name == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    include('views/dslr.php');
    die();
  } else {
    if ($optimization_type == 'thumbs') {
      $cmd = 'bash scripts/create_thumbs.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
      shell_async($cmd);
    } else if ($optimization_type == 'webs') {
      $cmd = 'bash scripts/create_webs.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
      shell_async($cmd);
    } else if ($optimization_type == 'delete_originals') {
      $cmd = 'bash scripts/delete_originals.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
      shell_async($cmd);
    } else {
      $error = "No optimization type specified. Check all fields and try again.";
      include('views/dslr.php');
      die();
    }
    $message = "Breathe in. Breathe out. Repeat until ".$optimization_type." for ".$album_name." are created.";
    include('views/dslr.php');
    die();
  }
} else if ($action == 'upload_to_server') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
  $server_name = filter_input(INPUT_POST, 'server_name', FILTER_SANITIZE_STRING);
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);
  $port = filter_input(INPUT_POST, 'port', FILTER_VALIDATE_INT);

  $albums = get_albums($photo_dir, array());

  if ($username == NULL || $username == FALSE ||
      $server_name == NULL || $server_name == FALSE ||
      $album_name == NULL || $album_name == FALSE) {
    $error = "Missing upload field(s). Check all fields and try again.";
    include('views/dslr.php');
    die();
  } else {
    $cmd = 'bash scripts/upload_to_server.sh '.escapeshellarg($album_name).' '.escapeshellarg($server_name).' '.escapeshellarg($username).' '.escapeshellarg($port).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
    $message = "Breathe in. Breathe out. Repeat until ".$album_name." is uploaded to ".$server_name.".";
    include('views/dslr.php');
    die();
  }
} else if ($action == 'set_album_thumb') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_GET, 'photo_name', FILTER_SANITIZE_STRING);

  $albums = get_albums($photo_dir, array());

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
    $error = "Missing album or photo name. Check all fields and try again.";
    include('views/dslr.php');
    die();
  } else {
    $cmd = 'bash scripts/create_album_thumb.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
    shell_async($cmd);
    $message = "Album thumb ".$album_name."/".$photo_name." created.";
    include('views/dslr.php');
    die();
  }
} else if ($action == 'move_to_trash') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_GET, 'photo_name', FILTER_SANITIZE_STRING);
  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
    echo "Error: Photo or Album name is missing.";
    header("Refresh:2; url=.?action=home", true, 303);
  }
  $albums = get_albums($photo_dir, array());

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
      echo "Error: Could not hide $album_name/$photo_name.";
      header("Refresh:2; url=.?action=album&album=$album_name", true, 303);
  } else {
    hide_image($album_name."/".$photo_name);
    echo $album_name."/".$photo_name." hidden.";
    header("Refresh:2; url=.?action=album&album=$album_name", true, 303);
  }
} else if ($action == 'delete_photo') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_GET, 'photo_name', FILTER_SANITIZE_STRING);
  $next_photo = filter_input(INPUT_GET, 'next_photo', FILTER_SANITIZE_STRING);

  $albums = get_albums($photo_dir, array());

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
    echo "Error: Could not delete $album_name/$photo_name, missing information.";
    header("Refresh:2; url=.?action=album&album=$album_name", true, 303);
  } else {
    $cmd = 'bash scripts/delete_photo.sh '.escapeshellarg($album_name).' '.escapeshellarg($photo_name).' '.escapeshellarg($photo_dir).' '.escapeshellarg($_SERVER['REMOTE_ADDR']);
    shell_async($cmd);
    echo $album_name."/".$photo_name." deleted.";
    if ($next_photo == NULL || $next_photo == FALSE) {
      $referer = $_SERVER['HTTP_REFERER'];
      header("Refresh:1; url=$referer", true, 303);
    } else {
      header("Refresh:1; url=.?action=album&album=$album_name&photo=$next_photo", true, 303);
    }
  }
} else if ($action == 'favorite') {
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_GET, 'photo_name', FILTER_SANITIZE_STRING);

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE) {
      echo "Error: Could not favorite $album_name/$photo_name.";
      header("Refresh:2; url=.?action=album&album=$album_name", true, 303);
  } else {
    $user_id = get_user_id($_SESSION['logged_in']);
    insert_favorite($album_name, $photo_name, $user_id);
    echo $album_name."/".$photo_name." favorited.";
    header("Refresh:1; url=.?action=album&album=$album_name&photo=$photo_name", true, 303);
  }
} else if ($action == 'delete_favorite') {
  $album_name = filter_input(INPUT_POST, 'album_name', FILTER_SANITIZE_STRING);
  $photo_name = filter_input(INPUT_POST, 'photo_name', FILTER_SANITIZE_STRING);
  $user_id = filter_input(INPUT_POST, 'user_id_to_unfavorite', FILTER_VALIDATE_INT);
  $acting_user_id = get_user_id($_SESSION['logged_in']);

  if ($album_name == NULL || $album_name == FALSE ||
      $photo_name == NULL || $photo_name == FALSE ||
      $user_id == NULL || $user_id == FALSE ||
      $acting_user_id == NULL || $acting_user_id == FALSE) {
        echo "user_id: $user_id, acting_user_id: $acting_user_id";
        echo "Error: Could not unfavorite $album_name/$photo_name.";
  } else {
    if ($user_id !== $acting_user_id || !isset($_SESSION['is_admin'])) {
      unfavorite($album_name, $photo_name, $user_id);
      echo $album_name."/".$photo_name." unfavorited.";
    } else {
      echo "Error: You do not have permission to unfavorite $album_name/$photo_name for this user.";
    }
  }
  $referer = $_SERVER['HTTP_REFERER'];
  header("Refresh:1; url=$referer", true, 303);
} else if ($action == 'review_favorites') {
  $user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

  if ($user_id == NULL || $user_id == FALSE) {
    echo "Error: No user id provided";
    header("Refresh:2; url=.?action=users", true, 303);
  } else if ($user_id != $_SESSION['user_id'] && !isset($_SESSION['is_admin'])) {
    echo "Imposter! You are not an admin and your user id is not ".$user_id.".";
    header("Refresh:2; url=.?action=home", true, 303);
  } else {
    $faves = get_favorites($user_id);
    include('views/favorites.php');
    die();
  }
} else if ($action == 'zip_favorites') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
  if ($user_id == NULL || $user_id == FALSE) {
      echo "Error: No user id supplied.";
      header("Refresh:2; url=.?action=users", true, 303);
  } else {
    $faves = get_favorites($user_id);
    // the zip will be updated
    $cmd = "zip -j ../../zips/user_$user_id.zip ";
    foreach ($faves as $fave) {
      $cmd .= $photo_dir.$fave['album_name']."/".strip_ext($fave['photo_name']).".* ";
    }
    $cmd .= "";
    shell_async($cmd);
    echo "Sit tight while user_$user_id.zip is created";
    header("Refresh:1; url=.?action=review_favorites&user_id=$user_id&zip=user_$user_id.zip", true, 303);
  }
}
?>
