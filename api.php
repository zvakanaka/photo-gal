<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

if ($action == 'download_progress') {
  $album = filter_input(INPUT_GET, 'album', FILTER_SANITIZE_STRING);
  $arr = array ('numImages'=>get_num_images($photo_dir, $album), 'album'=>$album);
  echo json_encode($arr);
  die();
} else if ($action == 'num_images_on_camera') {
  $num_images_on_camera = array ('totalNumImages'=>get_stat('images'));
  echo json_encode($num_images_on_camera);
} else if ($action == 'download_and_process') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $new_album = filter_input(INPUT_GET, 'new_album', FILTER_SANITIZE_STRING);
  if ($new_album == NULL || $new_album == FALSE) {
    $error = "No album name specified. Check all fields and try again.";
    $arr = array ('error'=>$error, 'album'=>$new_album);
    echo json_encode($arr);
    die();
  } else {
    $num_images_on_camera = filter_input(INPUT_GET, 'num_images_on_camera', FILTER_SANITIZE_STRING);
    $cmd = 'bash scripts/download_and_process.sh '.escapeshellarg($new_album).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
  }
  $success = $num_images_on_camera;
  $arr = array ('num_images_on_camera'=>$success, 'album'=>$new_album);
  echo json_encode($arr);
  die();
} else if ($action == 'upload_to_server') {
  if (!isset($_SESSION["is_admin"])) {// authenticate
    $error = "Sorry, only administrators can do these things.";
    include('views/dslr.php');
    die();
  }
  $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);
  $server_name = filter_input(INPUT_GET, 'server_name', FILTER_SANITIZE_STRING);
  $album_name = filter_input(INPUT_GET, 'album_name', FILTER_SANITIZE_STRING);
  $port = filter_input(INPUT_GET, 'port', FILTER_VALIDATE_INT);
  error_log("$username $server_name $album_name");
  if ($username == NULL || $username == FALSE ||
      $server_name == NULL || $server_name == FALSE ||
      $album_name == NULL || $album_name == FALSE) {
    $arr = array ('error'=>'Missing one or more parameters');
    echo json_encode($arr);
    die();
  } else {
    $cmd = 'bash scripts/upload_to_server.sh '.escapeshellarg($album_name).' '.escapeshellarg($server_name).' '.escapeshellarg($username).' '.escapeshellarg($port).' '.escapeshellarg($photo_dir);
    shell_async($cmd);
    $arr = array ('status'=>"Uploading $album_name to <a href='http://$server_name/photo-gal/?action=album&album=$album_name' target='blank'>$server_name</a>");
    echo json_encode($arr);
    die();
  }
} else if ($action == 'exif_read') {
  $album = filter_input(INPUT_GET, 'album', FILTER_SANITIZE_STRING);
  $file = filter_input(INPUT_GET, 'file', FILTER_SANITIZE_STRING);
  if ($file == NULL || $file == FALSE ||
      $album == NULL || $album == FALSE) {
    $arr = array ('error'=>'Missing one or more parameters');
    echo json_encode($arr);
    die();
  }
  $arr = get_exif($photo_dir, $album, $file);
  echo json_encode($arr);
  die();
}
?>
