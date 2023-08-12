<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/utente.php');
require_once(RC_ROOT . '/lib/xml.php');

$doc_utenti = load_xml('utenti');
$utenti = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@tipo="cliente"]');

$modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

if ($modifica) {
  $id_utente = $_POST['id'];

  $result = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']');
  $utente = $result[0];

  $utente->setAttribute('email', $_POST['email']);

  $attivo = isset($_POST['attivo']) && $_POST['attivo'] === 'on';
  $utente->getElementsByTagName('attivo')[0]->textContent = $attivo ? 'true' : 'false';

  $utente->getElementsByTagName('nome')[0]->textContent = $_POST['nome'];
  $utente->getElementsByTagName('cognome')[0]->textContent = $_POST['cognome'];

  $telefono = $_POST['telefono'];
  if (preg_match('/^\+[0-9]{12,13}$/', $telefono)) {
    $utente->getElementsByTagName('telefono')[0]->textContent = $telefono;
  }

  $indirizzo = $_POST['indirizzo'];
  if (preg_match('/^([[:alnum:] ]+), ([a-zA-Z ]+), ([a-zA-Z ]+)$/', $indirizzo)) {
    $utente->getElementsByTagName('indirizzo')[0]->textContent = $indirizzo;
  }

  $codice_fiscale = $_POST['codice-fiscale'];
  if (preg_match('/^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$/', $codice_fiscale)) {
    $utente->getElementsByTagName('codiceFiscale')[0]->textContent = $codice_fiscale;
  }

  $password = $_POST['password'];
  if ($password !== '') {
    $utente->getElementsByTagName('password')[0]->textContent = md5($password);
  }

  save_xml($doc_utenti, 'utenti');
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
    <h2>GESTIONE UTENTI</h2>
    <div class="table">
        <div class="tr">
          <div class="td">Email</div>
          <div class="td">Nome</div>
          <div class="td">Cognome</div>
          <div class="td">Telefono</div>
          <div class="td">Indirizzo</div>
          <div class="td">Codice fiscale</div>
          <div class="td">Password</div>
          <div class="td">Attivo</div>
          <div class="td">Modifica</div>
        </div>
<?php
foreach ($utenti as $utente) {
  $ut_id = $utente->getAttribute('id');
  $ut_nome = $utente->getElementsByTagName('nome')[0]->textContent;
  $ut_cognome = $utente->getElementsByTagName('cognome')[0]->textContent;
  $ut_email = $utente->getAttribute('email');
  $ut_telefono = $utente->getElementsByTagName('telefono')[0]->textContent;
  $ut_indirizzo = $utente->getElementsByTagName('indirizzo')[0]->textContent;
  $ut_codice_fiscale = $utente->getElementsByTagName('codiceFiscale')[0]->textContent;
  $ut_attivo = $utente->getElementsByTagName('attivo')[0]->textContent;
?>
        <form class="tr" id="utente-<?php echo($ut_id); ?>" method="post" action="<?php echo(RC_SUBDIR); ?>/admin/utenti.php">
          <input type="hidden" name="id" value="<?php echo($ut_id); ?>"></input>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_email); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="email" value="<?php echo($ut_email); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_nome); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="nome" value="<?php echo($ut_nome); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_cognome); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="cognome" value="<?php echo($ut_cognome); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_telefono); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="telefono" value="<?php echo($ut_telefono); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_indirizzo); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="indirizzo" value="<?php echo($ut_indirizzo); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_codice_fiscale); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="codice-fiscale" value="<?php echo($ut_codice_fiscale); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo('&ndash;'); ?></span>
            <input type="password" class="input-flat con-toggle nascosto" name="password" />
          </div>
          <div class="td">
            <input type="checkbox" class="con-toggle" <?php if ($ut_attivo === 'true') echo("checked"); ?> disabled></input>
            <input type="checkbox" class="con-toggle nascosto" name="attivo" <?php if ($ut_attivo === 'true') echo("checked"); ?>></input>
          </div>
          <div class="td">
            <a href="#" class="link con-toggle" onclick="visibilita(<?php echo($ut_id); ?>);">Modifica</a>
            <button type="submit" name="azione" value="modifica" class="link con-toggle nascosto">Conferma</button>
          </div>
        </form>
<?php
}
?>
    </div>
  </div>

  <script type="text/javascript">
    function visibilita(id) {
      var elemInfo = document.querySelectorAll('#utente-' + id + ' > div > .con-toggle');
      for (var i = 0; i < elemInfo.length; i++) {
        elemInfo[i].classList.toggle("nascosto");
      }
    }
  </script>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
