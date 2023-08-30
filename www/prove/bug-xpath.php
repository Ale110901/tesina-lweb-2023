<?php
$rc_root = realpath(__DIR__ . '/..');

header('Content-Type: text/plain');

require_once($rc_root . '/lib/xml.php');


$doc_dizionario = load_xml('prova');

$elementi = $doc_dizionario->documentElement->childNodes;
// $elementi = xpath($doc_dizionario, 'dizionario', '/ns:dizionario/ns:elemento');
stampa_elementi($elementi);

aggiungi_elemento('EEEEE', 'FFFFF');
echo ("## Aggiunto elemento\n");

$elementi = $doc_dizionario->documentElement->childNodes;
// $elementi = xpath($doc_dizionario, 'dizionario', '/ns:dizionario/ns:elemento');
stampa_elementi($elementi);


function aggiungi_elemento($chiave, $valore) {
  global $doc_dizionario;

  $root = $doc_dizionario->documentElement;
  $elementi = $root->childNodes;

  $id = get_next_id($elementi);

  $elemento = $doc_dizionario->createElement('elemento');
  $elemento->setAttribute('id', $id);

  $chiave = $doc_dizionario->createElement('chiave', $chiave);
  $elemento->appendChild($chiave);

  $valore = $doc_dizionario->createElement('valore', $valore);
  $elemento->appendChild($valore);

  $root->appendChild($elemento);

  save_xml($doc_dizionario, 'prova');
}

function stampa_elementi($elementi) {
  foreach ($elementi as $elemento) {
    $chiave = $elemento->getElementsByTagName('chiave')[0]->textContent;
    $valore = $elemento->getElementsByTagName('valore')[0]->textContent;

    echo ($chiave . ' => ' . $valore . "\n");
  }
}
?>
