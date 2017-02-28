<?php
function shell_async($cmd) {
  $output_file = 'scripts/log.txt';
  $pid_file = 'scripts/pid.txt';
  exec(sprintf("%s >> %s 2>&1 & echo $! >> %s", $cmd, $output_file, $pid_file));
}
?>
