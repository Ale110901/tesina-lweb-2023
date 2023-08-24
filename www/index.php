<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once($rc_root . '/lib/start.php');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Home page &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/index.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="homepage" class="centrato">
    <h1 class="my-32">BENVENUTO IN R&amp;C STORE</h1>
    <h2 class="corsivo my-32">Il tuo negozio di fiducia per prodotti sportivi</h2>
    <a class="button my-32" href="<?php echo($rc_subdir); ?>/catalogo.php">Vai al catalogo</a>
<?php if ($e_gestore) { ?>
    <p class="my-32"><a class="link" href="<?php echo($rc_subdir); ?>/gestore/index.php">Vai alla dashboard</a></p>
<?php } else if ($e_admin) { ?>
    <p class="my-32"><a class="link" href="<?php echo($rc_subdir); ?>/admin/index.php">Vai alla dashboard</a></p>
<?php } ?>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
