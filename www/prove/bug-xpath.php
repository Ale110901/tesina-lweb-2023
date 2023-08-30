<?php
$rc_root = realpath(__DIR__ . '/..');

header('Content-Type: text/plain');

require_once($rc_root . '/lib/xml.php');

/*
 * Il bug si presenta quando aggiungiamo un elemento e proviamo ad usare
 * xpath() sullo stesso documento nelle chiamate successive
 *
 *   ## Carica documento
 *   1: AAAAA => BBBBB
 *   2: CCCCC => DDDDD
 *   ## Aggiungi elemento
 *   1: AAAAA => BBBBB
 *   2: CCCCC => DDDDD
 *   ## Modifica elemento
 *   1: AAAAA => BBBBB
 *   2: GGGGG => HHHHH
 *   ## Elimina elemento
 *   2: GGGGG => HHHHH
 *   ## Normalizza documento
 *   2: GGGGG => HHHHH
 *   ## Ricarica documento
 *   2: GGGGG => HHHHH
 *   3: EEEEE => FFFFF
 */

echo ("## Carica documento\n");
$doc_dizionario = load_xml('prova');
stampa_elementi($doc_dizionario);

echo ("## Aggiungi elemento\n");
aggiungi_elemento('EEEEE', 'FFFFF');
stampa_elementi($doc_dizionario);

echo ("## Modifica elemento\n");
modifica_elemento(2, 'GGGGG', 'HHHHH');
stampa_elementi($doc_dizionario);

echo ("## Elimina elemento\n");
elimina_elemento(1);
stampa_elementi($doc_dizionario);

echo ("## Normalizza documento\n");
$doc_dizionario->normalizeDocument();
stampa_elementi($doc_dizionario);

echo ("## Ricarica documento\n");
$doc_dizionario = load_xml('prova');
stampa_elementi($doc_dizionario);


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

function modifica_elemento($id, $chiave, $valore) {
  global $doc_dizionario;

  $result = xpath($doc_dizionario, 'dizionario', '/ns:dizionario/ns:elemento[@id=' . $id . ']');
  $elemento = $result[0];

  $elemento->getElementsByTagName('chiave')[0]->textContent = $chiave;
  $elemento->getElementsByTagName('valore')[0]->textContent = $valore;

  save_xml($doc_dizionario, 'prova');
}

function elimina_elemento($id) {
  global $doc_dizionario;

  $result = xpath($doc_dizionario, 'dizionario', '/ns:dizionario/ns:elemento[@id=' . $id . ']');
  $elemento = $result[0];

  $dizionario = $elemento->parentNode;
  $dizionario->removeChild($elemento);

  save_xml($doc_dizionario, 'prova');
}

function stampa_elementi($doc) {
  $elementi = xpath($doc, 'dizionario', '/ns:dizionario/ns:elemento');
  // $elementi = $doc->documentElement->childNodes;

  foreach ($elementi as $elemento) {
    $id = $elemento->getAttribute('id');
    $chiave = $elemento->getElementsByTagName('chiave')[0]->textContent;
    $valore = $elemento->getElementsByTagName('valore')[0]->textContent;

    echo ($id . ': ' . $chiave . ' => ' . $valore . "\n");
  }
}
?>
