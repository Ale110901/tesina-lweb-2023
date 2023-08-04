<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/utente.php');
require_once(RC_ROOT . '/lib/xml.php');

$id_utente = $_SESSION['id_utente'];

$doc_utenti = load_xml('utenti');
$result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id=$id_utente]");
$utente = $result[0];

if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
  $utente->getElementsByTagName('nome')[0]->textContent = $_POST['nome'];
  $utente->getElementsByTagName('cognome')[0]->textContent = $_POST['cognome'];
  $utente->getElementsByTagName('telefono')[0]->textContent = $_POST['telefono'];
  $utente->getElementsByTagName('indirizzo')[0]->textContent = $_POST['indirizzo'];
  $utente->getElementsByTagName('codiceFiscale')[0]->textContent = $_POST['codice_fiscale'];

  save_xml($doc_utenti, 'utenti');
}

$nome = $utente->getElementsByTagName('nome')[0]->textContent;
$cognome = $utente->getElementsByTagName('cognome')[0]->textContent;
$email = $utente->getAttribute('email');
$telefono = $utente->getElementsByTagName('telefono')[0]->textContent;
$indirizzo = $utente->getElementsByTagName('indirizzo')[0]->textContent;
$codice_fiscale = $utente->getElementsByTagName('codiceFiscale')[0]->textContent;
$credito = $utente->getElementsByTagName('credito')[0]->textContent;
$reputazione = $utente->getElementsByTagName('reputazione')[0]->textContent;

$data_reg = $utente->getElementsByTagName('dataRegistrazione')[0]->textContent;
$data_reg = date_format(date_create($data_reg), 'd F Y');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Prova &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/profilo.css" />
</head>

<body>

  <script>
    function visibilita() {
      var elemInfo = document.getElementsByClassName('con-toggle');
      for (var i = 0; i < elemInfo.length; i++) {
        elemInfo[i].classList.toggle("nascosto");
      }
    }
  </script>

  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>Profilo</h2>
      <form action="<?php echo(RC_SUBDIR); ?>/utente/profilo.php" method="post">
        <table id="info-profilo" class="py-1em">
          <tr>
            <td><b>Nome:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($nome); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="nome" value="<?php echo ($nome); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Cognome:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($cognome); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="cognome" value="<?php echo ($cognome); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Email:</b></td>
            <td>
              <span><?php echo ($email); ?></span>
            </td>
          </tr>
          <tr>
            <td><b>Telefono:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($telefono); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="telefono" value="<?php echo ($telefono); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Indirizzo:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($indirizzo); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="indirizzo" value="<?php echo ($indirizzo); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Codice fiscale:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($codice_fiscale); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="codice_fiscale" value="<?php echo ($codice_fiscale); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Credito:</b></td>
            <td>
              <span><?php echo ($credito); ?> &euro;</span>
              <a class="link pl-2em" href="<?php echo(RC_SUBDIR); ?>/cliente/ricarica.php"> <span>Ricarica</span> </a>
            </td>
          </tr>
          <tr>
            <td><b>Reputazione:</b></td>
            <td>
              <span><?php echo ($reputazione); ?> punti</span>
            </td>
          </tr>
          <tr>
            <td><b>Data registrazione:</b></td>
            <td>
              <span><?php echo ($data_reg); ?></span>
            </td>
          </tr>
          <tr>
            <td><b>Storico acquisti:</b></td>
            <td>
              <a class="link" href="<?php echo(RC_SUBDIR); ?>/cliente/storico.php"><span>Visualizza</span></a>
            </td>
          </tr>
        </table>
        <button type="submit" name="azione" value="modifica" class="button con-toggle nascosto" title="Conferma modifiche">Conferma modifiche</button>
      </form>
      <button class="button con-toggle mt-8 mb-16" title="Modifica profilo" onclick="visibilita()">Modifica profilo</button>
      <br />
      <a href="<?php echo(RC_SUBDIR); ?>/utente/logout.php" class="button con-toggle" title="Logout">Logout</a>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>

</body>
</html>
