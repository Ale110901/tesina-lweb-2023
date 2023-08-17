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

$back_prod = isset($_POST['id']) && $_POST['id'] !== 0;

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'precompila') {
  if ($back_prod) {
    $id_prodotto = $_POST['id'];
  }
  $faq_domanda =  $_POST['domanda'];
  if(isset($_POST['risposta']) && isset($_POST['risposta']) !== '') {
    $faq_risposta = $_POST['risposta'];
  } else {
    $faq_risposta = '';
  }

} else if ($_POST['azione'] === 'aggiungi' && $_POST['domanda'] !== '' && $_POST['risposta'] !== '') {
  if ($back_prod) {
    $id_prodotto = $_POST['id'];
  }

  aggiungi_faq($_POST['domanda'], $_POST['risposta']);
  redirect(307, RC_SUBDIR . '/faq.php', false);
} else {
  if ($back_prod) {
    $id_prodotto = $_POST['id'];
  }

  $faq_domanda = $_POST['domanda'];
  $faq_risposta = $_POST['risposta'];
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

<?php if ($back_prod) { ?>
      <input type="hidden" name="id" value="<?php echo($id_prodotto); ?>" ></input>
<?php } ?>
      <input type="hidden" name="domanda" value="<?php echo($faq_domanda); ?>" ></input>
      <input type="hidden" name="risposta" value="<?php echo($faq_risposta); ?>" ></input>
      <button type="submit" class="button mb-16" name="azione" value="aggiungi">Aggiungi</button><br />

<?php 
$redirect = $back_prod ? 'href="' . RC_SUBDIR. '/prodotto.php?id=' . $id_prodotto . '"' : 'onclick="history.back();"';
?>

      <a class="button" <?php echo($redirect); ?>>Torna indietro</a>
    </form>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
