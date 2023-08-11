<?php
require_once(RC_ROOT . '/lib/xml.php');

function crea_ordine($indirizzo, $prezzo, $prodotti) {
  $id_utente = $_SESSION['id_utente'];

  $doc_ordini = load_xml('ordini');

  $root = $doc_ordini->documentElement;
  $ordini = $root->childNodes;

  $id_ordine = get_next_id($ordini);

  $nuovo_ordine = $doc_ordini->createElement('ordine');

  $nuovo_ordine->setAttribute('id', $id_ordine);
  $nuovo_ordine->setAttribute('idUtente', $id_utente);

  $el_data = $doc_ordini->createElement('data', date('Y-m-d H:i:s'));
  $nuovo_ordine->appendChild($el_data);

  $el_indirizzo = $doc_ordini->createElement('indirizzo', $indirizzo);
  $nuovo_ordine->appendChild($el_indirizzo);

  $el_prezzo = $doc_ordini->createElement('prezzoFinale', $prezzo);
  $nuovo_ordine->appendChild($el_prezzo);

  $el_prodotti = $doc_ordini->createElement('prodotti');

  foreach ($prodotti as $prodotto) {
    $nuovo_prodotto = $doc_ordini->createElement('prodotto');

    $id_prod = $prodotto->getAttribute('id');
    $qta_prod = $prodotto->getAttribute('quantita');

    $nuovo_prodotto->setAttribute('id', $id_prod);
    $nuovo_prodotto->setAttribute('quantita', $qta_prod);

    $el_prodotti->appendChild($nuovo_prodotto);
  }

  $nuovo_ordine->appendChild($el_prodotti);

  $root->appendChild($nuovo_ordine);

  save_xml($doc_ordini, 'ordini');

  return true;
}
?>
