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

function aggiungi_risposta($id_prodotto, $id_domanda, $contenuto_r) {
  global $doc_domande;
  $id_ut = $_SESSION['id_utente'];

  $arr_risposte = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@idProdotto=' . $id_prodotto . ' and @id='. $id_domanda .']/ns:risposte/ns:risposta');
  $risposte = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@idProdotto=' . $id_prodotto . ' and @id='. $id_domanda .']/ns:risposte')[0];
  
  $nuova_risposta = $doc_domande->createElement('risposta');

  $id = get_next_id($arr_risposte);
  $nuova_risposta->setAttribute('id', $id);

  $el_id_ut = $doc_domande->createElement('idUtente', $id_ut);
  $nuova_risposta->appendChild($el_id_ut);

  $el_contenuto = $doc_domande->createElement('contenuto', $contenuto_r);
  $nuova_risposta->appendChild($el_contenuto);

  $risposte->appendChild($nuova_risposta);

  save_xml($doc_domande, 'domande');

  return true;
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
