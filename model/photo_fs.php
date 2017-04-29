<?php
function get_albums($photo_dir, $album_blacklist) {
  $albums = array();
  if ($handle = opendir($photo_dir)) {
      while (false !== ($album = readdir($handle))) {
          if (!in_array($album, $album_blacklist)) {
            if ($album != '.' && $album != '..' && !strpos($album, '.'))
              $albums[] = $album;
          }
      }
      closedir($handle);
  }
  return $albums;
}

function get_images($photo_dir, $album, $image_blacklist) {
  $images = array();
  if ($SUPPORTED_FORMAT === 'webp') {
    $all_images = glob($photo_dir."/$album"."/*.{webp,WEBP}", GLOB_BRACE);
  } else {
    $all_images = glob($photo_dir."/$album"."/*.{jpg,JPG}", GLOB_BRACE);
  }
  foreach($all_images as $image) {
    if (!in_array($album.'/'.basename($image), $image_blacklist)) {
        $images[] = basename($image);
    }
  }
  return $images;
}

function get_num_images($photo_dir, $album) {
  // get total number of full-size, web, and thumbnail images
  $fullsize_images = count(glob($photo_dir."/$album"."/*.{jpg,JPG}", GLOB_BRACE));
  $webp_webs = count(glob($photo_dir."/$album/.web"."/*.{webp,WEBP}", GLOB_BRACE));
  $jpg_webs = count(glob($photo_dir."/$album/.web"."/*.{jpg,JPG}", GLOB_BRACE));
  $jpg_thumbs = count(glob($photo_dir."/$album/.thumb"."/*.{jpg,JPG}", GLOB_BRACE));
  $webp_thumbs = count(glob($photo_dir."/$album/.thumb"."/*.{jpg,JPG}", GLOB_BRACE));
  return $fullsize_images + $webp_webs + $webp_thumbs + $jpg_webs + $jpg_thumbs;
}

function get_exif($photo_dir, $album, $filename) {
  $exif = exif_read_data("$photo_dir/$album/$filename", 'IFD0');
  if ($exif === false) {
    return false;
  }
  $exif = exif_read_data("$photo_dir/$album/$filename", 0, true);
  foreach ($exif as $key => $section) {
      foreach ($section as $name => $val) {
      }
  }
  return $exif;
}
?>
