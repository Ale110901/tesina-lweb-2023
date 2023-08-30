<?php
require_once($rc_root . '/lib/xml.php');

$doc_faq = load_xml('faq');

function aggiungi_faq($domanda, $risposta) {
  global $doc_faq;

  $root = $doc_faq->documentElement;
  $faqs = $root->childNodes;

  $id = get_next_id($faqs);

  $faq = $doc_faq->createElement('faq');
  $faq->setAttribute('id', $id);

  $domanda =  $doc_faq->createElement('domanda', $domanda);
  $faq->appendChild($domanda);

  $risposta = $doc_faq->createElement('risposta', $risposta);
  $faq->appendChild($risposta);

  $root->appendChild($faq);

  save_xml($doc_faq, 'faq');

  // BUG: se non ricarico il documento i prossimi xpath() continuano ad usare il documento vecchio
  $doc_faq = load_xml('faq');

  return true;
}

function elimina_faq($id) {
  global $doc_faq;

  $result = xpath($doc_faq, 'faq', '/ns:faqs/ns:faq[@id=' . $id . ']');
  $faq = $result[0];

  $faqs = $faq->parentNode;
  $faqs->removeChild($faq);

  save_xml($doc_faq, 'faq');

  return true;
}

function modifica_faq($id, $domanda, $risposta) {
  global $doc_faq;

  $result = xpath($doc_faq, 'faq', '/ns:faqs/ns:faq[@id=' . $id . ']');
  $faq = $result[0];

  $faq->getElementsByTagName('domanda')[0]->textContent =  $domanda;
  $faq->getElementsByTagName('risposta')[0]->textContent = $risposta;

  save_xml($doc_faq, 'faq');

  return true;
}
?>
