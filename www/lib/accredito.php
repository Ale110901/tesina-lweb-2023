<?php
require_once(RC_ROOT . '/lib/xml.php');

function crea_accredito($quantita) {
  $id_utente = $_SESSION['id_utente'];

  $doc_accrediti = load_xml('accrediti');
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

  return true;
}
?>
