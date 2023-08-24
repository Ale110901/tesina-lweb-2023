<?php
$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/accrediti.php');

$ricarica = isset($_POST['azione']) && $_POST['azione'] === 'ricarica';
$qta_valida = isset($_POST['quantita']) && !is_nan($_POST['quantita']);

if ($ricarica && $qta_valida) {
  $creato = crea_accredito($_POST['quantita']);
}
?>

<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Ricarica &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />

</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>

  <div id="pagina-form" class="centrato">
<?php if (!$ricarica) { ?>
    <form class="pb-16" action="<?php echo($rc_subdir); ?>/cliente/ricarica.php" method="post">
      <label for="ricarica">Importo da ricaricare:</label><br>
      <input type="number" class="input-flat" name="quantita" min="1" step="1" size="5"/>
      <button type="submit" name="azione" value="ricarica" class="button ml-32 mt-8">Invia richiesta</button>
    </form>
<?php } else if ($creato) { ?>
    <div class="pt-16 mb-8">
      <p>La richiesta e' stata inviata. In caso di approvazione il credito verr&agrave; incrementato.</p>
    </div>
<?php } else { ?>
    <div class="pt-16 mb-8">
      <p>Errore nella creazione della richiesta...</p>
    </div>
<?php }
?>
    <a class="button" onclick="history.back();">Torna indietro</a>
  </div>

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
