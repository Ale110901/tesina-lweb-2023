<?php
require_once('xml.php');

function access_verification($email, $password){
  $doc = load_xml('utenti');

  $xpath = new DOMXPath($doc);
  $xpath->registerNameSpace('ut', 'http://www.lweb.uni/tesina-rcstore/utenti/');
  $query = "/ut:utenti/ut:utente[@email='$email']";
  $result = $xpath->evaluate($query);
  if ($result->length !== 1) {
    echo ("Errore email\n");
    return false;
  }

  $utente = $result[0];
  $tipo = $utente->getAttribute('tipo');

  if ($tipo === 'cliente') {
    $attivo = $utente->getElementsByTagName('attivo')[0]->textContent;
    if ($attivo !== 'true') {
      echo ("Errore attivo");
      return false;
    }
  }

  $password2 = $utente->getElementsByTagName('password')[0]->textContent;
  if ($password2 !== md5($password)) {
    echo ("Errore password");
    return false;
  }

  echo ("Accesso riuscito!\n");
  return $utente->getAttribute('id');
}

function estrazione_utente($doc, $id) {
  $xpath = new DOMXPath($doc);
  $xpath->registerNameSpace('ut', 'http://www.lweb.uni/tesina-rcstore/utenti/');
  $query = "/ut:utenti/ut:utente[@id = $id]";
  $result = $xpath->evaluate($query);
  if ($result->length !== 1) {
    echo ("Utente non presente\n");
    return false;
  }

  return $result[0];
}
?>
