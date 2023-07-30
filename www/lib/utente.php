<?php
require_once(RC_ROOT . '/lib/xml.php');

function login($email, $password) {
  $doc = load_xml('utenti');

  $result = xpath($doc, 'utenti', "/ns:utenti/ns:utente[@email='$email']");
  if ($result->length !== 1) {
    return "Errore email";
  }

  $utente = $result[0];

  $password2 = $utente->getElementsByTagName('password')[0]->textContent;
  if ($password2 !== md5($password)) {
    return "Errore password";
  }

  $tipo = $utente->getAttribute('tipo');

  if ($tipo === 'cliente') {
    $attivo = $utente->getElementsByTagName('attivo')[0]->textContent;
    if ($attivo !== 'true') {
      return "Errore abilitazione";
    }
  }

  $_SESSION['id_utente'] = $utente->getAttribute('id');
  $_SESSION['tipo_utente'] = $tipo;

  return "Ok";
}
?>
