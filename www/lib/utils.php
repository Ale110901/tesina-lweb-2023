<?php
function redirect_login() {
  $pag_corr = RC_SUBDIR . '/index.php';
  header('Location: /login.php?redirect=' . $pag_corr);
  exit();
}
?>
