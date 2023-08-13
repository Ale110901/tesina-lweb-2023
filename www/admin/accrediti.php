<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/accredito.php');
require_once(RC_ROOT . '/lib/xml.php');

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'accetta') {
  accetta_accredito($_POST['id']);
} else if ($_POST['azione'] === 'rifiuta') {
  rifiuta_accredito($_POST['id']);
}

$doc_accrediti = load_xml('accrediti');
$accrediti = xpath($doc_accrediti, 'accrediti', '/ns:accrediti/ns:accredito');

$doc_utenti = load_xml('utenti');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Dashboard admin &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>

  <div id="contenuto">
    <h2>GESTIONE ACCREDITI</h2>
    <div class="table mt-32">
      <div id="intestazione" class="thg">
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
  $ac_id_utente = $accredito->getElementsByTagName('idUtente')[0]->textContent;
  $ac_quantita = $accredito->getElementsByTagName('quantita')[0]->textContent;

  $result = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $ac_id_utente . ']');
  $utente = $result[0];

  $ut_nome = $utente->getElementsByTagName('nome')[0]->textContent;
  $ut_cognome = $utente->getElementsByTagName('cognome')[0]->textContent;
?>
      <form class="tr" id="accredito-<?php echo($ac_id); ?>" method="post" action="<?php echo(RC_SUBDIR); ?>/admin/accrediti.php">
        <input type="hidden" name="id" value="<?php echo($ac_id); ?>"></input>
        <div class="td">
          <span><?php echo($ac_data); ?></span>
        </div>
        <div class="td">
          <span><?php echo($ut_nome . ' ' . $ut_cognome); ?></span>
        </div>
        <div class="td">
          <span><?php echo($ac_quantita); ?></span>
        </div>
        <div class="td">
          <button type="submit" name="azione" value="accetta" class="button">V</button>
        </div>
        <div class="td">
          <button type="submit" name="azione" value="rifiuta" class="button">X</button>
        </div>
      </form>
<?php
}
?>
    </div>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
