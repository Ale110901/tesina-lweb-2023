<?php
$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/carrello.php');
require_once($rc_root . '/lib/ordini.php');
require_once($rc_root . '/lib/prodotti.php');
require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

$id_utente = $_SESSION['id_utente'];

if (!isset($_POST['azione'])) {
  $param_validi = false;

} else if ($_POST['azione'] === 'modifica-indirizzo') {
  $param_validi = true;
  $ordine_creato = false;
  $err_ind = false;

  $indirizzo = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:indirizzo')[0]->textContent;

  $totale = $_POST['totale'];
  $bonus = $_POST['bonus'];

} else if ($_POST['azione'] === 'crea') {
  $param_validi = true;

  $bonus = floatval($_POST['bonus']);
  $totale = floatval($_POST['totale']);
  $indirizzo = $_POST['indirizzo'];

  if (!preg_match('/^([[:alnum:] ]+), ([a-zA-Z ]+), ([a-zA-Z ]+)$/', $indirizzo)) {
    $err_ind = true;
    $indirizzo = '';

    $ordine_creato = false;
  } else {
    $err_ind = false;

    $prodotti = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello/ns:prodotto');
    $ordine_creato = crea_ordine($indirizzo, $totale, $prodotti);

    if ($ordine_creato) {
      foreach ($prodotti as $prodotto) {
        $id_prod = $prodotto->getAttribute('id');
        $qta_diff = $prodotto->getAttribute('quantita');

        scala_qta_prodotto($id_prod, $qta_diff);
      }

      $diff = -$totale + $bonus;

      modifica_credito($diff);
      svuota_carrello();
    }
  }

}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Carrello &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>

  <div id="pagina-form" class="centrato">
    <h2>ORDINE</h2>
<?php if (!$param_validi) { ?>
      <p class="mt-32">Non si pu&ograve; accedere a questa pagina senza aver seguito il flusso dell'ordine...</p>
<?php } else if ($ordine_creato) { ?>
      <h4 class="mt-32">Ordine creato con successo!, verrai reindirizzato al catalogo...</h4>
      <meta http-equiv="refresh" content="3; <?php echo($rc_subdir); ?>/catalogo.php">
<?php } else { ?>
      <form action="<?php echo($rc_subdir); ?>/cliente/ordine.php" method="post">
        <label for="indirizzo">Indirizzo di spedizione:</label>
        <input type="hidden" name="totale" value="<?php echo($totale); ?>" />
        <input type="hidden" name="bonus" value="<?php echo($bonus); ?>" />
        <input type="text" class="ma-32 input-flat" name="indirizzo" value="<?php echo($indirizzo); ?>" /><br />
        <button type="submit" class="button" name="azione" value="crea">Conferma ordine</button>
      </form>
<?php   if ($err_ind) { ?>
      <p class="mt-32">L'indirizzo deve essere nel formato: VIA CIVICO, CITTA, PAESE</p>
<?php   } ?>
<?php } ?>
  </div>

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
