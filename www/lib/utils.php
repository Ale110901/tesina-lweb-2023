<?php
function redirect_login() {
  $pag_corr = RC_SUBDIR . '/index.php';
  http_response_code(307);
  header('Location: /login.php?redirect=' . $pag_corr);
  exit();
}
?>
