<?php require('../lib/start.php'); ?>
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
  <?php require('lib/header.php'); ?> 
  <div id="card-esterna-profilo" class="centrato py-1em">
    <h2 class="">Profilo</h2>
        <label for="nome"><b>Nome:</b> Alessandro</label><br>
        <label for="cognome"><b>Cognome:</b> Cecchetto</label><br>
        <label for="email"><b>Email:</b> alecechetto2001@gmail.com</label><br>
        <label for="telefono"><b>Telefono:</b> +39 3334617057</label><br>
        <label for="indirizzo"><b>Indirizzo:</b> via casamia, 15</label>
        <span><button type="submit" name="azione" value="modifica_indirizzo" class="button-modifica-indirizzo" title="modifica indirizzo">&#x01F4DD</button></span><br>
        <label for="codiceFiscale"><b>Codice fiscale:</b></label><br>
        <label for="credito"><b>Credito:</b></label><br>
  </div>

  <?php require('lib/footer.php'); ?>
</body>
</html>