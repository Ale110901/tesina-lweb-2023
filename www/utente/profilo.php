<?php
  require_once('../config.php');
  require_once(RC_ROOT . '/lib/start.php');
  require_once(RC_ROOT . '/lib/auth.php');
  require_once(RC_ROOT . '/lib/xml.php');

  $id_utente = access_verification('mario.rossi@gmail.com', 'password');

  if ($id_utente !== false) {
    $doc_utenti = load_xml('utenti');
    $nodo_utente = estrazione_utente($doc_utenti, $id_utente);

    if (isset($_POST['azione']) && $_POST['azione'] === 'modifica') {
      $nodo_utente->getElementsByTagName('nome')[0]->textContent = $_POST['nome'];
      $nodo_utente->getElementsByTagName('cognome')[0]->textContent = $_POST['cognome'];
      $nodo_utente->getElementsByTagName('telefono')[0]->textContent = $_POST['telefono'];
      $nodo_utente->getElementsByTagName('indirizzo')[0]->textContent = $_POST['indirizzo'];
      $nodo_utente->getElementsByTagName('codiceFiscale')[0]->textContent = $_POST['codice_fiscale'];

      save_xml($doc_utenti, 'utenti');
    }

    $nome = $nodo_utente->getElementsByTagName('nome')[0]->textContent;
    $cognome = $nodo_utente->getElementsByTagName('cognome')[0]->textContent;
    $email = $nodo_utente->getAttribute('email');
    $telefono = $nodo_utente->getElementsByTagName('telefono')[0]->textContent;
    $indirizzo = $nodo_utente->getElementsByTagName('indirizzo')[0]->textContent;
    $codice_fiscale = $nodo_utente->getElementsByTagName('codiceFiscale')[0]->textContent;
    $credito = $nodo_utente->getElementsByTagName('credito')[0]->textContent;
    $reputazione = $nodo_utente->getElementsByTagName('reputazione')[0]->textContent;
  }
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
      var elemInfo = document.getElementsByClassName('info-mod');
      for (var i = 0; i < elemInfo.length; i++) {
        elemInfo[i].classList.toggle("nascosto");
      }

      var elemInfo = document.getElementsByClassName('input-profilo');
      for (var i = 0; i < elemInfo.length; i++) {
        elemInfo[i].classList.toggle("nascosto");
      }

      var elemInfo = document.getElementsByClassName('button-modifica-profilo');
      for (var i = 0; i < elemInfo.length; i++) {
        elemInfo[i].classList.toggle("nascosto");
      }
    }
  </script>

  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="card-esterna-profilo" class="py-1em">
    <h2>Profilo</h2>
      <form id="form-profilo" action="<?php echo(RC_SUBDIR); ?>/utente/profilo.php" method="POST">
        <table id="table-info-profilo" class="py-1em">
          <tr>
            <td class="specifica-campo"><b>Nome:</b></td>
            <td>
              <span class="info-mod"><?php echo ($nome); ?></span>
              <input type="text" class="input-profilo nascosto" name="nome" value="<?php echo ($nome); ?>" />
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Cognome:</b></td>
            <td>
              <span class="info-mod"><?php echo ($cognome); ?></span>
              <input type="text" class="input-profilo nascosto" name="cognome" value="<?php echo ($cognome); ?>" />
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Email:</b></td>
            <td>
              <span class="info-profilo"><?php echo ($email); ?></span>
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Telefono:</b></td>
            <td>
              <span class="info-mod"><?php echo ($telefono); ?></span>
              <input type="text" class="input-profilo nascosto" name="telefono" value="<?php echo ($telefono); ?>" />
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Indirizzo:</b></td>
            <td>
              <span class="info-mod"><?php echo ($indirizzo); ?></span>
              <input type="text" class="input-profilo nascosto" name="indirizzo" value="<?php echo ($indirizzo); ?>" />
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Codice fiscale:</b></td>
            <td>
              <span class="info-mod"><?php echo ($codice_fiscale); ?></span>
              <input type="text" class="input-profilo nascosto" name="codice_fiscale" value="<?php echo ($codice_fiscale); ?>" />
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Credito:</b></td>
            <td>
              <span class="info-profilo"><?php echo ($credito); ?> &euro;</span>
            </td>
          </tr>
          <tr>
            <td class="specifica-campo"><b>Reputazione:</b></td>
            <td>
              <span class="info-profilo"><?php echo ($reputazione); ?> punti</span>
            </td>
          </tr>
        </table>
        <button type="submit" class="button-modifica-profilo nascosto" name="azione" value="modifica" title="conferma modifiche">Conferma modifiche</button>
      </form>
      <button class="button-modifica-profilo mt-8" title="modifica profilo" onclick="visibilita()">Modifica profilo</button>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>

</body>
</html>
