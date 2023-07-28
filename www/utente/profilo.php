<?php 
  require('../config.php');
  require(RC_ROOT . '/lib/start.php');
  require(RC_ROOT . '/lib/auth.php');

  $id_utente = access_verification('mario.rossi@gmail.com', 'password');
  if($id_utente !== false){
    $nodo_utente = estrazione_utente($id_utente);
    $nome = $nodo_utente->getElementsByTagName('nome')[0]->textContent;
    $cognome = $nodo_utente->getElementsByTagName('cognome')[0]->textContent;
    $telefono = $nodo_utente->getElementsByTagName('telefono')[0]->textContent;
    $indirizzo = $nodo_utente->getElementsByTagName('indirizzo')[0]->textContent;
    $codiceFiscale = $nodo_utente->getElementsByTagName('codiceFiscale')[0]->textContent;
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

  <link rel="stylesheet" type="text/css" href="../res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="../res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="../res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="../res/css/profilo.css" />
</head>

<body>
  <?php require('../lib/header.php'); ?> 
  <div id="card-esterna-profilo" class="py-1em">
    <h2>Profilo</h2>
  <!--  <label for="nome"><b>Nome:</b> <span>Alessandro</span></label><br>
        <label for="cognome"><b>Cognome:</b> <span>Cecchetto</span> </label><br>
        <label for="email"><b>Email:</b> <span>alecechetto2001@gmail.com</span> </label><br>
        <label for="telefono"><b>Telefono:</b> <span>+39 3334617057</span> </label><br>
        <label for="indirizzo"><b>Indirizzo:</b> <span>via casamia, 15</span> </label>
        <span> <button type="submit" name="azione" value="modifica_indirizzo" class="button-modifica-indirizzo" title="modifica indirizzo">&#x01F4DD</button> </span><br>
        <label for="codiceFiscale"><b>Codice fiscale:</b><span> </span></label><br>
        <label for="credito"><b>Credito:</b></label><br> -->
      <table id="info-profilo" class="py-1em">
        <tr>
          <td id="specifica-campo"><b>Nome:</b></td>
          <td> <?php echo $nome; ?> </td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Cognome:</b></td>
          <td><?php echo $cognome; ?></td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Email:</b></td>
          <td><?php  ?></td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Telefono:</b></td>
          <td><?php echo $telefono; ?></td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Indirizzo:</b></td>
          <td><?php echo $indirizzo; ?><span><button type="submit" name="azione" value="modifica_indirizzo" class="button-modifica-indirizzo" title="modifica indirizzo">&#x01F4DD</button></span></td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Codice fiscale:</b></td>
          <td><?php echo $codiceFiscale; ?></td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Credito:</b></td>
          <td><?php echo $credito; ?></td>
        </tr>
        <tr>
          <td id="specifica-campo"><b>Reputazione:</b></td>
          <td><?php echo $reputazione; ?></td>
        </tr>

      </tbody>
    </table>
  </div>

  <?php require('../lib/footer.php'); ?>
</body>
</html>