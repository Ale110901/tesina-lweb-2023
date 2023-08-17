<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Dashboard gestore &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/gestore.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2 class="mb-32">DASHBOARD GESTORE</h2>
    <ul>
      <li id="prodotti" class="mb-8"><a class="ml-8 scelta" href="<?php echo(RC_SUBDIR); ?>/gestore/prodotti.php">Gestione prodotti</a></li>
      <li id="offerte" class="mb-8"><a class="ml-8 scelta" href="<?php echo(RC_SUBDIR); ?>/gestore/offerte.php">Gestione offerte</a></li>
      <li id="categorie" class="mb-8"><a class="ml-8 scelta" href="<?php echo(RC_SUBDIR); ?>/gestore/categorie.php">Gestione categorie</a></li>
      <li id="utenti" class="mb-8"><a class="ml-8 scelta" href="<?php echo(RC_SUBDIR); ?>/gestore/utenti.php">Consulta clienti</a></li>
      <li id="faq"  class="mb-16"><a class="ml-8 scelta" href="<?php echo(RC_SUBDIR); ?>/admin/aggiungi-faq.php">Aggiungi domanda alle FAQ</a></li>
    </ul><br />
    <a href="<?php echo(RC_SUBDIR); ?>/utente/logout.php" class="button con-toggle" title="Logout">Logout</a>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
