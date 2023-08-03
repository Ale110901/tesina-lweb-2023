<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/accredito.php');

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
  <title>Ricarica</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />

</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>

  <div id="pagina-form" class="centrato">
<?php if (!$ricarica) { ?>
    <form action="<?php echo(RC_SUBDIR); ?>/cliente/ricarica.php" method="post">
      <label for="ricarica">Importo da ricaricare:</label><br>
      <input type="number" class="input-flat" name="quantita" min="0" step="1" size="3"/>
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
<?php } ?>
    <a href="<?php echo(RC_SUBDIR); ?>/utente/profilo.php">Torna indietro</a>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>