<?php
$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

$id_utente = $_SESSION['id_utente'];

if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
  modifica_cliente($id_utente, true,
    $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['password'],
    $_POST['telefono'], $_POST['indirizzo'], $_POST['codice_fiscale']);
}

$info_utente = ottieni_info_utente($id_utente);
$data_reg = date_format(date_create($info_utente['data_reg']), 'd F Y');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Prova &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/profilo.css" />
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

  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
      <h2>Profilo</h2>
      <form action="<?php echo($rc_subdir); ?>/cliente/profilo.php" method="post">
        <table id="info-profilo" class="py-1em">
          <tr>
            <td><b>Nome:</b></td>
            <td>
              <span class="con-toggle"><?php echo($info_utente['nome']); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="nome" value="<?php echo($info_utente['nome']); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Cognome:</b></td>
            <td>
              <span class="con-toggle"><?php echo($info_utente['cognome']); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="cognome" value="<?php echo($info_utente['cognome']); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Email:</b></td>
            <td>
              <span class="con-toggle"><?php echo($info_utente['email']); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="email" value="<?php echo($info_utente['email']); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Password:</b></td>
            <td>
              <span class="con-toggle">&ndash;</span>
              <input type="password" class="input-flat con-toggle nascosto" name="password" />
            </td>
          </tr>
          <tr>
            <td><b>Telefono:</b></td>
            <td>
              <span class="con-toggle"><?php echo($info_utente['telefono']); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="telefono" value="<?php echo($info_utente['telefono']); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Indirizzo:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($info_utente['indirizzo']); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="indirizzo" value="<?php echo ($info_utente['indirizzo']); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Codice fiscale:</b></td>
            <td>
              <span class="con-toggle"><?php echo ($info_utente['codice_fiscale']); ?></span>
              <input type="text" class="input-flat con-toggle nascosto" name="codice_fiscale" value="<?php echo ($info_utente['codice_fiscale']); ?>" />
            </td>
          </tr>
          <tr>
            <td><b>Credito:</b></td>
            <td>
              <span><?php echo (number_format($info_utente['credito'], 2)); ?> &euro;</span>
              <a class="link ml-2em" href="<?php echo($rc_subdir); ?>/cliente/ricarica.php">Ricarica</a>
            </td>
          </tr>
          <tr>
            <td><b>Reputazione:</b></td>
            <td>
              <span><?php echo ($info_utente['reputazione']); ?> punti</span>
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
              <a class="link" href="<?php echo($rc_subdir); ?>/cliente/storico.php">Visualizza</a>
            </td>
          </tr>
        </table>
        <p class="con-toggle nascosto">
          <button type="submit" name="azione" value="modifica" class="button" title="Conferma modifiche">Conferma modifiche</button><br /><br />
          <a class="button" href="<?php echo($rc_subdir); ?>/cliente/profilo.php" title="Torna indietro">Torna indietro</a>
        </p>
      </form>
      <button class="button con-toggle mt-8 mb-16" title="Modifica profilo" onclick="visibilita()">Modifica profilo</button>
      <br />
      <a href="<?php echo($rc_subdir); ?>/utente/logout.php" class="button con-toggle" title="Logout">Logout</a>
  </div>

  <?php require($rc_root . '/lib/footer.php'); ?>

</body>
</html>
