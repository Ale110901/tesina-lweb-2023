<?php
require_once(RC_ROOT . '/lib/xml.php');

function aggiungi_faq($domanda, $risposta) {
  $doc_faq = load_xml('faq');

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
}

function elimina_faq($id) {
  $doc_faq = load_xml('faq');

  $result = xpath($doc_faq, 'faq', '/ns:faqs/ns:faq[@id=' . $id . ']');
  $faq = $result[0];

  $faqs = $faq->parentNode;
  $faqs->removeChild($faq);

  save_xml($doc_faq, 'faq');
}

function modifica_faq($doc_faq, $id, $domanda, $risposta) {
  $result = xpath($doc_faq, 'faq', '/ns:faqs/ns:faq[@id=' . $id . ']');
  $faq = $result[0];

  $faq->getElementsByTagName('domanda')[0]->textContent =  $domanda;
  $faq->getElementsByTagName('risposta')[0]->textContent = $risposta;

  save_xml($doc_faq, 'faq');
}
?>
