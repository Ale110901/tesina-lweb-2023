<?php
session_start();

$loggato = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);
$pag_corr = RC_SUBDIR . '/index.php';

if (!$perm_visitatore) {
  if (!$loggato) {
    redirect_login();
  }

  if (
    ($_SESSION['tipo'] === 'cliente' && !$perm_cliente) ||
    ($_SESSION['tipo'] === 'gestore' && !$perm_gestore) ||
    ($_SESSION['tipo'] === 'admin' && !$perm_admin)
  ) {
    header('Location: /accesso_negato.php');
    exit();
  }
}

function redirect_login() {
  header('Location: /login.php?redirect=' . $pag_corr);
  exit();
}
?>
