<?php require('lib/start.php'); ?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Prova &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="res/css/profilo.css" />
</head>

<body>
  <?php require('lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <?php ?>
    <h2 class="">Profilo </h2>
    <label for="nome">Nome utente:</label><br>
    <label for="cognome">Cognome:</label><br>
    <label for="telefono">Telefono:</label><br>
    <label for="indirizzo">Indirizzo:</label>
    <span><button type="submit" name="azione" value="modifica_indirizzo" class="button-modifica-indirizzo" title="modifica indirizzo">&#x01F4DD</button></span>
    <br>
    <label for="codiceFiscale">Codice fiscale:</label><br>
    <label for="credito">Credito:</label><br>
    <button type="submit" name="azione" value="accedi" class="button">Accedi</button>

  </div>

  <?php require('lib/footer.php'); ?>
</body>
</html>
