<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once($rc_root . '/lib/start.php');
require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

$clienti = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@tipo="cliente"]');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Consultazione clienti &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/utenti-gestore.css" />

</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="contenuto">
    <h2 class="mb-32">CONSULTAZIONE CLIENTI</h2>
    <div class="table mt-32 mx-auto mb-32">
      <div class="thg grassetto">
        <div class="td">Nome</div>
        <div class="td">Cognome</div>
        <div class="td">Reputazione</div>
        <div class="td">Storico</div>
      </div>
<?php
foreach ($clienti as $cliente) {
  $c_id = $cliente->getAttribute('id');
  $c_nome = $cliente->getElementsByTagName('nome')[0]->textContent;
  $c_cognome = $cliente->getElementsByTagName('cognome')[0]->textContent;
  $c_reputazione = $cliente->getElementsByTagName('reputazione')[0]->textContent;
?>
      <div class="tr">
        <div class="td"><?php echo($c_nome); ?></div>
        <div class="td"><?php echo($c_cognome); ?></div>
        <div class="td centrato"><?php echo($c_reputazione); ?></div>
        <div class="td centrato"><a id="icona" href="<?php echo($rc_subdir); ?>/cliente/storico.php?id=<?php echo($c_id); ?>">&#x1F441</a></div>
      </div>
<?php
}
?>
    </div>
    <a class="button" href="<?php echo($rc_subdir);?>/gestore/index.php">Torna indietro</a>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
