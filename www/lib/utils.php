<?php
function redirect_login() {
  $pag_corr = $_SERVER['REQUEST_URI'];
  http_response_code(307);
  header('Location: ' . RC_SUBDIR . '/utente/login.php?redirect=' . $pag_corr);
  exit();
}
?>
