<?php
require_once(RC_ROOT . '/lib/rating.php');
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

  $doc_recensioni = load_xml('recensioni');

  return true;
}

function aggiungi_rating_recensione($id_recensione, $supporto, $utilita) {
  global $doc_recensioni;

  $result = xpath($doc_recensioni, 'recensioni', "/ns:recensioni/ns:recensione[@id='$id_recensione']/ns:ratings");
  $ratings = $result[0];

  aggiungi_rating($doc_recensioni, $ratings, $supporto, $utilita);

  save_xml($doc_recensioni, 'recensioni');

  // BUG: se non ricarico il documento il resto continua ad usare il documento vecchio
  $doc_recensioni = load_xml('recensioni');

  return true;
}

function elimina_recensione($id) {
  global $doc_recensioni;

  $result = xpath($doc_recensioni, 'recensioni', '/ns:recensioni/ns:recensione[@id=' . $id . ']');
  $recensione = $result[0];

  $recensioni = $recensione->parentNode;
  $recensioni->removeChild($recensione);

  save_xml($doc_recensioni, 'recensioni');
}

function elimina_recensioni($id_prodotto) {
  global $doc_recensioni;

  $recensioni = xpath($doc_recensioni, 'recensioni', '/ns:recensioni/ns:recensione[@idProdotto=' . $id_prodotto . ']');
  $root = $doc_recensioni->documentElement;

  foreach ($recensioni as $recensione) {
    $root->removeChild($recensione);
  }

  save_xml($doc_recensioni, 'recensioni');
}
?>
