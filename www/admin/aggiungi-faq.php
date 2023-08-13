<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/faq.php');
require_once(RC_ROOT . '/lib/utils.php');

$aggiungi = isset($_POST['azione']) && $_POST['azione'] === 'aggiungi';

if ($aggiungi) {
  aggiungi_faq($_POST['domanda'], $_POST['risposta']);
  redirect(307, '/faq.php', false);
}
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
    <h2>AGGIUNGI FAQ</h2>
    <form method="post" action="<?php echo(RC_SUBDIR); ?>/admin/aggiungi-faq.php">
      <textarea class="input-flat" name="domanda"  rows="6" placeholder="Inserisci la domanda qui"></textarea>
      <textarea class="input-flat" name="risposta" rows="6" placeholder="Inserisci la risposta qui"></textarea>
      <button type="submit" name="azione" value="aggiungi">Aggiungi</button>
    </form>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
