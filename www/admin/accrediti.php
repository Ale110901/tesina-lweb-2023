<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/accrediti.php');
require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'accetta') {
  accetta_accredito($_POST['id']);
} else if ($_POST['azione'] === 'rifiuta') {
  rifiuta_accredito($_POST['id']);
}

$accrediti = xpath($doc_accrediti, 'accrediti', '/ns:accrediti/ns:accredito');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Dashboard admin &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>

  <div id="contenuto">
    <h2>GESTIONE ACCREDITI</h2>
    <div class="table mt-32 mb-32 mx-auto centrato">
      <div id="intestazione" class="thg grassetto">
        <div class="td">Data</div>
        <div class="td">Utente</div>
        <div class="td">Quantita</div>
        <div class="td">Accetta</div>
        <div class="td">Rifiuta</div>
      </div>
<?php
foreach ($accrediti as $accredito) {
  $ac_id = $accredito->getAttribute('id');
  $ac_data = $accredito->getElementsByTagName('data')[0]->textContent;
  $ac_data = date_format(date_create($ac_data), 'Y-m-d H:i');
  $ac_id_utente = $accredito->getElementsByTagName('idUtente')[0]->textContent;
  $ac_quantita = $accredito->getElementsByTagName('quantita')[0]->textContent;

  $result = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $ac_id_utente . ']');
  $utente = $result[0];

  $ut_nome = $utente->getElementsByTagName('nome')[0]->textContent;
  $ut_cognome = $utente->getElementsByTagName('cognome')[0]->textContent;
?>
      <form class="tr" id="accredito-<?php echo($ac_id); ?>" method="post" action="<?php echo($rc_subdir); ?>/admin/accrediti.php">
        <input type="hidden" name="id" value="<?php echo($ac_id); ?>"></input>
        <div class="td">
          <span><?php echo($ac_data); ?></span>
        </div>
        <div class="td">
          <span><?php echo($ut_nome . ' ' . $ut_cognome); ?></span>
        </div>
        <div class="td">
          <span><?php echo(number_format($ac_quantita, 2)); ?> &euro;</span>
        </div>
        <div class="td">
          <button type="submit" name="azione" value="accetta" class="button-icona">&#x2705</button>
        </div>
        <div class="td">
          <button type="submit" name="azione" value="rifiuta" class="button-icona">&#x274C</button>
        </div>
      </form>
<?php
}
?>
    </div>

    <div class="centrato">
      <a class="button" href="<?php echo($rc_subdir); ?>/admin/index.php">Torna indietro</a>
    </div>
  </div>

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
