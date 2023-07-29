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

  <script>
    function visibilita(){
      var element1 = document.getElementById("info-profilo");
      var element2 = document.getElementById("modifiche-profilo2");
      var element3 = document.getElementById("modifiche-profilo3");
      var element4 = document.getElementById("modifiche-profilo4");
      var element5 = document.getElementById("modifiche-profilo5");
      var element6 = document.getElementById("modifiche-profilo6");
      element1.classList.toggle("mostra-modifiche-profilo");
      element2.classList.toggle("mostra-modifiche-profilo");
      element3.classList.toggle("mostra-modifiche-profilo");
      element4.classList.toggle("mostra-modifiche-profilo");
      element5.classList.toggle("mostra-modifiche-profilo");
      element6.classList.toggle("mostra-modifiche-profilo");
    }
  </script>

  <?php require(RC_ROOT . '/lib/header.php'); ?> 
  <div id="card-esterna-profilo" class="py-1em">
    <h2>Profilo</h2>
      <form id="form-profilo" action="profilo.php" method="POST">
        <table id="table-info-profilo" class="py-1em">
          <tr>
            <td id="specifica-campo"><b>Nome:</b></td>
            <td> 
              <span id="info-profilo"> <?php echo $nome; ?> </span> 
              <input type="text" class="input-profilo" id="modifiche-profilo1" name="reset_nome">
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Cognome:</b></td>
            <td> 
              <span id="info-profilo"> <?php echo $cognome; ?> </span>
              <input type="text" class="input-profilo" id="modifiche-profilo2" name="reset_cognome">
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Email:</b></td>
            <td>
              <span id="info-profilo"> <?php  ?> </span>
              <input type="text" class="input-profilo" id="modifiche-profilo3" name="reset_email">
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Telefono:</b></td>
            <td>
              <span id="info-profilo"> <?php echo $telefono; ?> </span>
              <input type="text" class="input-profilo" id="modifiche-profilo4" name="reset_telefono">
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Indirizzo:</b></td>
            <td>
              <span id="info-profilo"> <?php echo $indirizzo; ?> </span>
              <input type="text" class="input-profilo" id="modifiche-profilo5" name="reset_indirizzo">
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Codice fiscale:</b></td>
            <td>
              <span id="info-profilo"> <?php echo $codiceFiscale; ?> </span>
              <input type="text" class="input-profilo" id="modifiche-profilo6" name="reset_cf">
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Credito:</b></td>
            <td>
              <span id="info-profilo"> <?php echo $credito; ?> &euro;</span>
              <!--<input type="text" id="input-profilo" name="reset_indirizzo">-->
            </td>
          </tr>
          <tr>
            <td id="specifica-campo"><b>Reputazione:</b></td>
            <td>
              <?php echo $reputazione; ?>
            </td>
          </tr>
        </table>
        <button type="submit" class="button-conferma-profilo" id="conferma-modifiche" name="azione" value="conferma_modifiche_profilo" title="conferma modifiche">Conferma modifiche</button>
      </form>
      <button type="submit" class="button-modifica-profilo mt-8" id="modifica" name="azione" value="cambia_profilo" title="modifica profilo" onclick="visibilita()">Modifica profilo</button>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>

</body>
</html>