<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/faq.php');
require_once(RC_ROOT . '/lib/utils.php');

$faq_domanda = '';
$faq_risposta = '';

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'precompila') {
  $faq_domanda =  $_POST['domanda'];
  $faq_risposta = $_POST['risposta'];
} else if ($_POST['azione'] === 'aggiungi' && $_POST['domanda'] !== '' && $_POST['risposta'] !== '') {
  aggiungi_faq($_POST['domanda'], $_POST['risposta']);
  redirect(307, RC_SUBDIR . '/faq.php', false);
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Aggiungi FAQ &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>AGGIUNGI FAQ</h2>
    <form method="post" action="<?php echo(RC_SUBDIR); ?>/admin/aggiungi-faq.php">
      <div class="my-32">
        <label for="domanda">Domanda:</label><br />
        <textarea class="input-flat w-50p" name="domanda"  rows="6" placeholder="Inserisci la domanda qui"><?php echo($faq_domanda); ?></textarea>
      </div>
      <div class="my-32">
        <label for="risposta">Risposta:</label><br />
        <textarea class="input-flat w-50p" name="risposta" rows="6" placeholder="Inserisci la risposta qui"><?php echo($faq_risposta); ?></textarea>
      </div>
      <button type="submit" class="button mb-16" name="azione" value="aggiungi">Aggiungi</button><br />
      <a class="button" href="<?php echo(RC_SUBDIR);?>/faq.php" >Torna indietro</a>
    </form>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
