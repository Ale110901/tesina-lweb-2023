<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Dashboard admin &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/admin.css" />
</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2 class="mb-32">DASHBOARD ADMIN</h2>
    <ul class="mb-8">
      <li id="utenti" class="mb-8"><a class="ml-8 scelta" href="<?php echo($rc_subdir); ?>/admin/utenti.php">Gestione utenti</a></li>
      <li id="accrediti" class="mb-8"><a class="ml-8 scelta" href="<?php echo($rc_subdir); ?>/admin/accrediti.php">Gestione accrediti</a></li>
      <li id="faq"  class="mb-8"><a class="ml-8 scelta" href="<?php echo($rc_subdir); ?>/faq.php">Gestisci FAQ</a></li>
      <li id="faq"  class="mb-8"><a class="ml-8 scelta" href="<?php echo($rc_subdir); ?>/admin/aggiungi-faq.php">Aggiungi domanda alle FAQ</a></li>
    </ul><br />
    <a href="<?php echo($rc_subdir); ?>/utente/logout.php" class="button con-toggle" title="Logout">Logout</a>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
