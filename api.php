<?php
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
}
?>
