<?php
require_once(RC_ROOT . '/lib/xml.php');

$doc_recensioni = load_xml('recensioni');

function aggiungi_recensione($id_prodotto, $contenuto) {
  global $doc_recensioni;
  
  $id_utente = $_SESSION['id_utente'];

  $root = $doc_recensioni->documentElement;
  $recensioni = $root->childNodes;

  $nuova_recensione = $doc_recensioni->createElement('recensione');

  $id_recensione = get_next_id($recensioni);

  $nuova_recensione->setAttribute('id', $id_recensione);
  $nuova_recensione->setAttribute('idProdotto', $id_prodotto);

  $el_idUt = $doc_recensioni->createElement('idUtente', $id_utente);
  $nuova_recensione->appendChild($el_idUt);

  $el_contenuto = $doc_recensioni->createElement('contenuto', $contenuto);
  $nuova_recensione->appendChild($el_contenuto);

  $el_ratings = $doc_recensioni->createElement('ratings');
  $nuova_recensione->appendChild($el_ratings);

  $root->appendChild($nuova_recensione);

  save_xml($doc_recensioni, 'recensioni');

  return true;
}
?>
