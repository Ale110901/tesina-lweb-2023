<?php
require_once(RC_ROOT . '/lib/xml.php');

$doc_domande = load_xml('domande');

function elimina_domanda($id) {
  global $doc_domande;

  $result = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@id=' . $id . ']');
  $domanda = $result[0];

  $domande = $domanda->parentNode;
  $domande->removeChild($domanda);

  save_xml($doc_domande, 'domande');
}

function elimina_domande($id_prodotto) {
  global $doc_domande;

  $domande = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@idProdotto=' . $id_prodotto . ']');
  $root = $doc_domande->documentElement;

  foreach ($domande as $domanda) {
    $root->removeChild($domanda);
  }

  save_xml($doc_domande, 'domande');
}

function presenza_gestore_risposta($id_domanda, $id_prodotto) {
  global $doc_domande;
  $trovato = false;

  $domande = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@id='. $id_domanda .' and @idProdotto='. $id_prodotto .']');

  foreach ($domande as $domanda) {
    $risposte = $domanda->getElementsByTagName('risposte')[0]->childNodes;
    foreach ($risposte as $risposta) {
      $id_ut_r = $risposta->getElementsByTagName('idUtente')[0]->textContent;
      $trovato = trova_gestore($id_ut_r);
      if ($trovato) {
        return true;
      }
    }
  }

  return false;
}
?>
