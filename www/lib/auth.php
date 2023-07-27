<?php
require_once('xml.php');

/* eventualmente da fare con xpath... */
function access_verification($email, $password){
  $doc = load_xml('utenti');

  /*$root = $doc->documentElement;
  $utenti = $root->childNodes;
  $trovato = false;
  $i = 0;
  do{
    $utente = $utenti->item($i);
    $password_utente = $utente->getElementsByTagName('password')[0]->textContent;
    $attivo_utente = $utente->getElementsByTagName('attivo')[0]->textContent;
    $email_utente = $utente->getAttribute('email');
    if($email_utente == $email && $password_utente == $password && $attivo_utente == true){
      $trovato = true;
    }
    $i++;
  }while($i < $utenti->length && !$trovato);*/
  /* DA SISTEMARE */

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
  return true;
}
?>
