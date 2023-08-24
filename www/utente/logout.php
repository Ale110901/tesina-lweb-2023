<?php
require_once('../lib/utils.php');

$rc_subdir = make_subdir(1);

session_start();
if (isset($_SESSION['id_utente'])) {
  unset($_SESSION['id_utente']);
  session_destroy();
}

$redir_dest = isset($_GET['redirect']) ? $_GET['redirect'] : '';
if (!isset($redir_dest) || $redir_dest === '') {
  $redir_dest = $rc_subdir . '/index.php';
}

redirect(307, $redir_dest, false);
?>
