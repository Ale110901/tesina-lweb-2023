<?php
require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

$doc_accrediti = load_xml('accrediti');

function crea_accredito($quantita) {
  global $doc_accrediti;

  $id_utente = $_SESSION['id_utente'];

  $root = $doc_accrediti->documentElement;
  $accrediti = $root->childNodes;

  $id_accredito = get_next_id($accrediti);

  $el_accredito = $doc_accrediti->createElement('accredito');
  $el_accredito->setAttribute('id', $id_accredito);

  $el_data = $doc_accrediti->createElement('data', date('Y-m-d\TH:i:s'));
  $el_accredito->appendChild($el_data);

  $el_id_utente = $doc_accrediti->createElement('idUtente', $id_utente);
  $el_accredito->appendChild($el_id_utente);

  $el_quantita = $doc_accrediti->createElement('quantita', $quantita);
  $el_accredito->appendChild($el_quantita);

  $root->appendChild($el_accredito);

  save_xml($doc_accrediti, 'accrediti');

  // BUG: se non ricarico il documento i prossimi xpath() continuano ad usare il documento vecchio
  $doc_accrediti = load_xml('accrediti');

  return true;
}

function accetta_accredito($id_accredito) {
  global $doc_accrediti;

  $result = xpath($doc_accrediti, 'accrediti', '/ns:accrediti/ns:accredito[@id=' . $id_accredito . ']');
  $accredito = $result[0];

  $ac_id_utente = $accredito->getElementsByTagName('idUtente')[0]->textContent;
  $ac_quantita = $accredito->getElementsByTagName('quantita')[0]->textContent;

  $accrediti = $accredito->parentNode;
  $accrediti->removeChild($accredito);

  save_xml($doc_accrediti, 'accrediti');


  global $doc_utenti;

  $result = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $ac_id_utente . ']');
  $utente = $result[0];

  $ut_credito = $utente->getElementsByTagName('credito')[0];
  $ut_credito->textContent += floatval($ac_quantita);

  save_xml($doc_utenti, 'utenti');

  return true;
}

function rifiuta_accredito($id_accredito) {
  global $doc_accrediti;

  $result = xpath($doc_accrediti, 'accrediti', '/ns:accrediti/ns:accredito[@id=' . $id_accredito . ']');
  $accredito = $result[0];

  $accrediti = $accredito->parentNode;
  $accrediti->removeChild($accredito);

  save_xml($doc_accrediti, 'accrediti');

  return true;
}
?>
