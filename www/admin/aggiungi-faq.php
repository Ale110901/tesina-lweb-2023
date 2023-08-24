<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/faq.php');
require_once($rc_root . '/lib/utils.php');

$faq_domanda = '';
$faq_risposta = '';
$errore = false;

$back_prod = isset($_POST['id']) && $_POST['id'] !== 0;
if ($back_prod) {
  $id_prodotto = $_POST['id'];
}

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'precompila') {
  $faq_domanda =  $_POST['domanda'];

  if (isset($_POST['risposta']) && $_POST['risposta'] !== '') {
    $faq_risposta = $_POST['risposta'];
  } else {
    $faq_risposta = '';
  }
} else if ($_POST['azione'] === 'aggiungi') {
  if ($_POST['domanda'] !== '' && $_POST['risposta'] !== '') {
    aggiungi_faq($_POST['domanda'], $_POST['risposta']);
    redirect(307, $rc_subdir . '/faq.php', false);
  } else {
    $errore = true;
    $faq_domanda = $_POST['domanda'];
    $faq_risposta = $_POST['risposta'];
  }
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Aggiungi FAQ &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>AGGIUNGI FAQ</h2>
    <form method="post" action="<?php echo($rc_subdir); ?>/admin/aggiungi-faq.php">
      <div class="my-32">
        <label for="domanda" class="grassetto">Domanda:</label><br />
        <textarea class="input-flat w-50p" name="domanda"  rows="6" placeholder="Inserisci la domanda qui"><?php echo($faq_domanda); ?></textarea>
      </div>
      <div class="my-32">
        <label for="risposta" class="grassetto">Risposta:</label><br />
        <textarea class="input-flat w-50p" name="risposta" rows="6" placeholder="Inserisci la risposta qui"><?php echo($faq_risposta); ?></textarea>
      </div>

<?php if ($back_prod) { ?>
      <input type="hidden" name="id" value="<?php echo($id_prodotto); ?>" ></input>
<?php } ?>
      <button type="submit" class="button mb-16" name="azione" value="aggiungi">Aggiungi</button><br />

<?php
if ($back_prod) {
  $a_redir = 'href="' . $rc_subdir . '/prodotto.php?id=' . $id_prodotto . '"';
} else {
  $a_redir = 'onclick="history.back();"';
}
?>
      <a class="button" <?php echo($a_redir); ?>>Torna indietro</a>
<?php if ($errore) { ?>
      <p class="mt-32 grassetto">
        &#x26a0; inserire tutti i campi!
      </p>
<?php } ?>

    </form>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
