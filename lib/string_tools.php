<?php
// filename.ext returns filename
function strip_ext($filename) {
  return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
}

//http://yo.com?action=dslr&lol=haha returns http://yo.com?action=dslr
function strip_extra_params($url) {
  if (strrpos($url,'&') != 0) {
    return substr($url,0,strrpos($url,'&'));
  }
  return $url;
}
?>
