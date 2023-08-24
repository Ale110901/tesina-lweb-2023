<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once($rc_root . '/lib/start.php');
require_once($rc_root . '/lib/categorie.php');
require_once($rc_root . '/lib/utils.php');

$errore = false;

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'aggiungi') {
  if ($_POST['nome'] !==  '') {
    aggiungi_categoria($_POST['nome']);
    redirect(307, $rc_subdir . '/gestore/categorie.php', false);
  } else {
    $errore = true;
  }
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Aggiungi categoria &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>AGGIUNGI CATEGORIA</h2>
    <form method="post" action="<?php echo($rc_subdir); ?>/gestore/aggiungi-categoria.php">
      <div class="my-32">
        <label for="nome" class="mr-8 grassetto">Nome:</label>
        <input type="text" class="input-flat" name="nome" />
      </div>
      <button type="submit" class="button mb-16" name="azione" value="aggiungi">Aggiungi</button><br />
    </form>
<?php if ($errore) { ?>
    <p class="mb-32 grassetto">
      &#x26a0; inserire tutti i campi!
    </p>
<?php } ?>
    <a class="button" href="<?php echo($rc_subdir);?>/gestore/categorie.php">Torna indietro</a>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
