<?php
function redirect($code, $page, $use_dest) {
  http_response_code($code);

  if ($use_dest) {
    $cur_page = $_SERVER['REQUEST_URI'];
    $suffix .= '?redirect=' . $cur_page;
  } else {
    $suffix = '';
  }

  header('Location: ' . RC_SUBDIR . $page . $suffix);
  exit();
}
?>
