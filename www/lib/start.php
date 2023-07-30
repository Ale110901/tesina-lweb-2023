<?php
require_once(RC_ROOT . '/lib/utils.php');

if (RC_ROOT === null) {
  header('Content-Type: text/plain');

  echo ('Il sito non e\' stato installato. Procedere con l\'installazione prima.');
  exit ();
}

session_start();

$loggato = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);

if (!$perm_visitatore) {
  if (!$loggato) {
    redirect_login();
  }

  if (
    ($_SESSION['tipo'] === 'cliente' && !$perm_cliente) ||
    ($_SESSION['tipo'] === 'gestore' && !$perm_gestore) ||
    ($_SESSION['tipo'] === 'admin' && !$perm_admin)
  ) {
    http_response_code(403);
    header('Location: /accesso_negato.php');
    exit();
  }
}
?>
