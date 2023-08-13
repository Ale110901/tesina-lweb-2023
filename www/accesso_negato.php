<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');

$doc_utenti = load_xml('utenti');

if(isset($_SESSION['id_utente'])){
  $id_utente = $_SESSION['id_utente'];

  $result = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']');
  $utente = $result[0];

  $tipo_u = $utente->getAttribute('tipo');
} else {
  $tipo_u = 'utente';
}

if($tipo_u === 'cliente' || $tipo_u === 'utente') {
  $home = '/index.php';
} else if($tipo_u === 'admin') { 
  $home = '/admin/index.php';
} else if($tipo_u === 'gestore') {
  $home = '/gestore/index.php';
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
      Per accedere a questa pagina bisogna essere autenticati e disporre delle autorizzazioni necessarie.<br /><br />
      <img src="<?php echo(RC_SUBDIR); ?>/res/img/divieto.png" alt="Icona divieto" /><br /><br />
      <a href="<?php echo(RC_SUBDIR . $home); ?>">Torna alla home</a>>
    </p>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
