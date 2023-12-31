<?php
$rc_root = realpath(__DIR__ . '/..');

require_once($rc_root . '/lib/utils.php');

$rc_subdir = make_subdir($rc_level);

session_start();

// Caso particolare
/*
 * La pagina del carrello riceve un post quando si aggiunge un elemento.
 * Serve memorizzare i dati del post anche quando l'utente non e' loggato per
 * permettere l'aggiunta dell'elemento dopo il login.
 * Quindi questa operazione va in questo file, perche' va eseguita dopo
 * l'avvio della sessione ma prima del controllo dei permessi.
 */
if ($_SERVER['PHP_SELF'] === $rc_subdir . '/cliente/carrello.php') {
  if (isset($_POST['azione']) && $_POST['azione'] === 'aggiungi') {
    $_SESSION['agg_carr_id_prod'] = $_POST['id_prodotto'];
    $_SESSION['agg_carr_qta'] = $_POST['quantita'];
  }
}

$loggato = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);

$e_cliente = isset($_SESSION['tipo_utente']) && $_SESSION['tipo_utente'] === 'cliente';
$e_gestore = isset($_SESSION['tipo_utente']) && $_SESSION['tipo_utente'] === 'gestore';
$e_admin =   isset($_SESSION['tipo_utente']) && $_SESSION['tipo_utente'] === 'admin';
$e_visitatore = !$e_cliente && !$e_gestore && !$e_admin;

if ($e_visitatore && !$perm_visitatore) {
  redirect(401, $rc_subdir . '/utente/login.php', true);
}

if (
  ($e_cliente && !$perm_cliente) ||
  ($e_gestore && !$perm_gestore) ||
  ($e_admin && !$perm_admin)
) {
  redirect(403, $rc_subdir . '/accesso_negato.php', false);
}
?>
