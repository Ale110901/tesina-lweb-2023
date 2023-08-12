<?php
require_once(RC_ROOT . '/lib/xml.php');

function scala_qta_prodotto($id_prodotto, $qta_diff) {
  $doc_prodotti = load_xml('prodotti');

  $quantita = xpath($doc_prodotti, 'prodotti', '/ns:prodotti/ns:prodotto[@id=' . $id_prodotto . ']/ns:quantita')[0];
  $quantita->textContent -= $qta_diff;

  save_xml($doc_prodotti, 'prodotti');
}
?>
