<?php
function get_supported_format() {
  $is_firefox = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Firefox")) > 0 );
  $is_safari = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Safari")) > 0 );
  $is_chrome = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Chrome")) > 0 );
  $is_ie = (strlen(strstr($_SERVER['HTTP_USER_AGENT'],"Internet Explorer")) > 0 );

  if ($is_chrome) {
    return "webp";
  }
  return "jpg";
}

$supported_format = get_supported_format();
?>
