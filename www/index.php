<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Home page &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/index.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="homepage" class="centrato">
    <h1 class="my-32">BENVENUTO IN R&amp;C STORE</h1>
    <h2 class="corsivo my-32">Il tuo negozio di fiducia per prodotti sportivi</h2>
    <a class="button my-32" href="<?php echo(RC_SUBDIR); ?>/catalogo.php">Vai al catalogo</a>
<?php if ($e_gestore) { ?>
    <p class="my-32"><a class="link my-32" href="<?php echo(RC_SUBDIR); ?>/gestore/index.php">Dashboard gestore</a></p>
<?php } else if ($e_admin) { ?>
    <p class="my-32"><a class="link my-32" href="<?php echo(RC_SUBDIR); ?>/admin/index.php">Dashboard admin</a></p>
<?php } ?>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
