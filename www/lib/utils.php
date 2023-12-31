<?php
function redirect($code, $page, $use_dest) {
  if ($use_dest) {
    $cur_page = $_SERVER['REQUEST_URI'];
    $suffix = '?redirect=' . $cur_page;
  } else {
    $suffix = '';
  }

  http_response_code($code);
  header('Location: ' . $page . $suffix);
  exit();
}

function make_subdir($level) {
  $path = $_SERVER['PHP_SELF'];

  for ($i = 0; $i < ($level + 1); $i++) {
    $pos = strrpos($path, '/');
    $path = substr($path, 0, $pos);
  }

  return $path;
}
?>
