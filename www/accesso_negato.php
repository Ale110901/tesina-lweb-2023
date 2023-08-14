<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');

if ($e_visitatore || $e_cliente) {
  $homepage = '/index.php';
} else if ($e_admin) {
  $homepage = '/admin/index.php';
} else if ($e_gestore) {
  $homepage = '/gestore/index.php';
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Accesso negato &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto">
    <h2>Accesso negato</h2>
    <p class="centrato py-1em">
      Non si dispone delle autorizzazioni necessarie per accedere a questa pagina.<br /><br />
      <img src="<?php echo(RC_SUBDIR); ?>/res/img/divieto.png" alt="Icona divieto" /><br /><br />
      <a href="<?php echo(RC_SUBDIR . $homepage); ?>">Torna alla home</a>
    </p>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
