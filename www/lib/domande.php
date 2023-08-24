<?php
require_once($rc_root . '/lib/rating.php');
require_once($rc_root . '/lib/xml.php');

$doc_domande = load_xml('domande');

function elimina_domanda($id) {
  global $doc_domande;

  $result = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@id=' . $id . ']');
  $domanda = $result[0];

  $domande = $domanda->parentNode;
  $domande->removeChild($domanda);

  save_xml($doc_domande, 'domande');

  return true;
}

function elimina_domande($id_prodotto) {
  global $doc_domande;

  $domande = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@idProdotto=' . $id_prodotto . ']');
  $root = $doc_domande->documentElement;

  foreach ($domande as $domanda) {
    $root->removeChild($domanda);
  }

  save_xml($doc_domande, 'domande');

  return true;
}

function aggiungi_domanda($id_prodotto, $contenuto_dom) {
  global $doc_domande;
  $id_ut = $_SESSION['id_utente'];

  $root = $doc_domande->documentElement;
  $domande = $root->childNodes;

  $nuova_domanda = $doc_domande->createElement('domanda');

  $id = get_next_id($domande);
  $nuova_domanda->setAttribute('id', $id);
  $nuova_domanda->setAttribute('idProdotto', $id_prodotto);

  $el_u = $doc_domande->createElement('idUtente', $id_ut);
  $nuova_domanda->appendChild($el_u);

  $contenuto = $doc_domande->createElement('contenuto', $contenuto_dom);
  $nuova_domanda->appendChild($contenuto);

  $data = $doc_domande->createElement('data', date('Y-m-d'));
  $nuova_domanda->appendChild($data);

  $el_ratings = $doc_domande->createElement('ratings');
  $nuova_domanda->appendChild($el_ratings);

  $el_risposte = $doc_domande->createElement('risposte');
  $nuova_domanda->appendChild($el_risposte);

  $root->appendChild($nuova_domanda);

  save_xml($doc_domande, 'domande');

  // BUG: se non ricarico il documento il resto continua ad usare il documento vecchio
  load_xml('domande');

  return true;
}

function aggiungi_rating_domanda($id_domanda, $supporto, $utilita) {
  global $doc_domande;

  $result = xpath($doc_domande, 'domande', "/ns:domande/ns:domanda[@id='$id_domanda']/ns:ratings");
  $ratings = $result[0];

  aggiungi_rating($doc_domande, $ratings, $supporto, $utilita);

  save_xml($doc_domande, 'domande');

  // BUG: se non ricarico il documento il resto continua ad usare il documento vecchio
  $doc_domande = load_xml('domande');

  return true;
}


function elimina_risposta($id_domanda, $id_risposta) {
  global $doc_domande;

  $result = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@id=' . $id_domanda . ']/ns:risposte/ns:risposta[@id=' . $id_risposta . ']');
  $risposta = $result[0];

  $risposte = $risposta->parentNode;
  $risposte->removeChild($risposta);

  save_xml($doc_domande, 'domande');

  return true;
}

function aggiungi_risposta($id_domanda, $contenuto_r) {
  global $doc_domande;

  $risposte = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@id=' . $id_domanda . ']/ns:risposte')[0];

  $nuova_risposta = $doc_domande->createElement('risposta');

  $arr_risposte = $risposte->childNodes;
  $id = get_next_id($arr_risposte);
  $nuova_risposta->setAttribute('id', $id);

  $id_ut = $_SESSION['id_utente'];
  $el_id_ut = $doc_domande->createElement('idUtente', $id_ut);
  $nuova_risposta->appendChild($el_id_ut);

  $el_contenuto = $doc_domande->createElement('contenuto', $contenuto_r);
  $nuova_risposta->appendChild($el_contenuto);

  $el_data = $doc_domande->createElement('data', date('Y-m-d'));
  $nuova_risposta->appendChild($el_data);

  $el_ratings = $doc_domande->createElement('ratings');
  $nuova_risposta->appendChild($el_ratings);

  $risposte->appendChild($nuova_risposta);

  save_xml($doc_domande, 'domande');

  load_xml('domande');

  return true;
}

function aggiungi_rating_risposta($id_domanda, $id_risposta, $supporto, $utilita) {
  global $doc_domande;

  $result = xpath($doc_domande, 'domande', "/ns:domande/ns:domanda[@id='$id_domanda']/ns:risposte/ns:risposta[@id='$id_risposta']/ns:ratings");
  $ratings = $result[0];

  aggiungi_rating($doc_domande, $ratings, $supporto, $utilita);

  save_xml($doc_domande, 'domande');

  // BUG: se non ricarico il documento il resto continua ad usare il documento vecchio
  $doc_domande = load_xml('domande');

  return true;
}


function presenza_gestore_risposta($id_domanda) {
  global $doc_domande;
  $trovato = false;

  $domande = xpath($doc_domande, 'domande', '/ns:domande/ns:domanda[@id=' . $id_domanda . ']');

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
