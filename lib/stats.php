<?php
function get_stat($arg) {
  $cmd = 'bash scripts/stats.sh '.escapeshellarg($arg);
  $output = shell_exec($cmd);
  return rtrim($output);
}
?>
