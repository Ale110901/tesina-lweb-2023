<?php
require_once(RC_ROOT . '/lib/xml.php');

function login_utente($email, $password) {
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

function registra_utente($nome, $cognome, $email, $password, $telefono, $indirizzo, $codice_fiscale) {
  $doc_utenti = load_xml('utenti');

  $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@email='$email']");
  if ($result->length !== 0) {
    return false;
  }

  $root = $doc_utenti->documentElement;
  $utenti = $root->childNodes;

  $nuovo_utente = $doc_utenti->createElement('utente');

  $id_utente = get_next_id($utenti);
  $nuovo_utente->setAttribute('id', $id_utente);
  $nuovo_utente->setAttribute('tipo', 'cliente');
  $nuovo_utente->setAttribute('email', $email);

  $el_attivo = $doc_utenti->createElement('attivo', 'true');
  $nuovo_utente->appendChild($el_attivo);

  $el_nome = $doc_utenti->createElement('nome', $nome);
  $nuovo_utente->appendChild($el_nome);

  $el_cognome = $doc_utenti->createElement('cognome', $cognome);
  $nuovo_utente->appendChild($el_cognome);

  $el_telefono = $doc_utenti->createElement('telefono', $telefono);
  $nuovo_utente->appendChild($el_telefono);

  $el_indirizzo = $doc_utenti->createElement('indirizzo', $indirizzo);
  $nuovo_utente->appendChild($el_indirizzo);

  $el_codice_fiscale = $doc_utenti->createElement('codiceFiscale', $codice_fiscale);
  $nuovo_utente->appendChild($el_codice_fiscale);

  $el_password = $doc_utenti->createElement('password', md5($password));
  $nuovo_utente->appendChild($el_password);

  $el_reputazione = $doc_utenti->createElement('reputazione', '30');
  $nuovo_utente->appendChild($el_reputazione);

  $el_credito = $doc_utenti->createElement('credito', '0.00');
  $nuovo_utente->appendChild($el_credito);

  $el_data_reg = $doc_utenti->createElement('dataRegistrazione', date('Y-m-d'));
  $nuovo_utente->appendChild($el_data_reg);

  $el_carrello = $doc_utenti->createElement('carrello');
  $nuovo_utente->appendChild($el_carrello);

  $root->appendChild($nuovo_utente);

  save_xml($doc_utenti, 'utenti');

  return true;
}
?>
