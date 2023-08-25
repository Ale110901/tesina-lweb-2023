<?php
require_once($rc_root . '/lib/constants.php');
require_once($rc_root . '/lib/xml.php');

$doc_utenti = load_xml('utenti');

function login_utente($email, $password) {
  global $doc_utenti;

  $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@email='$email']");
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
  global $doc_utenti;

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

function modifica_utente($id_utente, $attivo, $nome, $cognome, $email, $password, $telefono, $indirizzo, $codice_fiscale) {
  global $doc_utenti;

  $successo = true;

  $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id=$id_utente]");
  $utente = $result[0];

  $utente->getElementsByTagName('attivo')[0]->textContent = $attivo ? 'true' : 'false';
  $utente->getElementsByTagName('nome')[0]->textContent = $nome;
  $utente->getElementsByTagName('cognome')[0]->textContent = $cognome;

  if (preg_match(REGEX_EMAIL, $email)) {
    $utente->setAttribute('email', $email);
    $successo = false;
  }

  if ($password !== '') {
    $utente->getElementsByTagName('password')[0]->textContent = md5($password);
    $successo = false;
  }

  if (preg_match(REGEX_TELEFONO, $telefono)) {
    $utente->getElementsByTagName('telefono')[0]->textContent = $telefono;
    $successo = false;
  }

  if (preg_match(REGEX_INDIRIZZO, $indirizzo)) {
    $utente->getElementsByTagName('indirizzo')[0]->textContent = $indirizzo;
    $successo = false;
  }

  if (preg_match(REGEX_CF, $codice_fiscale)) {
    $utente->getElementsByTagName('codiceFiscale')[0]->textContent = $codice_fiscale;
    $successo = false;
  }

  save_xml($doc_utenti, 'utenti');

  return $successo;
}

function ottieni_info_utente($id_utente) {
  global $doc_utenti;

  $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_utente']");
  $utente = $result[0];

  $tipo = $utente->getAttribute('tipo');

  $nome = $utente->getElementsByTagName('nome')[0]->textContent;
  $cognome = $utente->getElementsByTagName('cognome')[0]->textContent;
  $email = $utente->getAttribute('email');

  if ($tipo === 'gestore' || $tipo === 'admin') {
    return [
      'nome' => $nome,
      'cognome' => $cognome,
      'email' => $email
    ];
  }

  $telefono = $utente->getElementsByTagName('telefono')[0]->textContent;
  $indirizzo = $utente->getElementsByTagName('indirizzo')[0]->textContent;
  $codice_fiscale = $utente->getElementsByTagName('codiceFiscale')[0]->textContent;
  $credito = $utente->getElementsByTagName('credito')[0]->textContent;
  $reputazione = $utente->getElementsByTagName('reputazione')[0]->textContent;
  $data_reg = $utente->getElementsByTagName('dataRegistrazione')[0]->textContent;

  return [
    'nome' => $nome,
    'cognome' => $cognome,
    'email' => $email,
    'telefono' => $telefono,
    'indirizzo' => $indirizzo,
    'codice_fiscale' => $codice_fiscale,
    'credito' => $credito,
    'reputazione' => $reputazione,
    'data_reg' => $data_reg
  ];
}

function modifica_credito($diff) {
  global $doc_utenti;

  $id_utente = $_SESSION['id_utente'];

  $credito = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_utente']/ns:credito")[0];
  $credito->textContent += $diff;

  save_xml($doc_utenti, 'utenti');

  return true;
}

function trova_gestore($id) {
  global $doc_utenti;

  $result = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id . ' and @tipo="gestore"]');

  return $result->length > 0;
}
?>
