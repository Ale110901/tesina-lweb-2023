<?php
require_once(RC_ROOT . '/lib/xml.php');

function aggiungi_carrello($id_prodotto, $qta_diff) {
  $id_utente = $_SESSION['id_utente'];

  $doc_utenti = load_xml('utenti');
  $result = xpath($doc_utenti, 'utenti',
    '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello/ns:prodotto[@id= ' . $id_prodotto . ']'
  );

  if ($result->length === 0) {
    $result = xpath($doc_utenti, 'utenti',
      '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello'
    );
    $carrello = $result[0];

    $prodotto = $doc_utenti->createElement('prodotto');
    $prodotto->setAttribute('id', $id_prodotto);
    $prodotto->setAttribute('quantita', $qta_diff);
    $carrello->appendChild($prodotto);
  } else {
    $prodotto = $result[0];
    $qta_carrello = $prodotto->getAttribute('quantita');
    $qta_carrello += $qta_diff;
    $prodotto->setAttribute('quantita', $qta_carrello);
  }

  save_xml($doc_utenti, 'utenti');
}

function conta_carrello() {
  if (!isset($_SESSION['id_utente'])) {
    return 0;
  }

  $id_utente = $_SESSION['id_utente'];

  $doc_utenti = load_xml('utenti');
  $result = xpath($doc_utenti, 'utenti',
    '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello/ns:prodotto'
  );

  if ($result->length === 0) {
    return 0;
  }

  $somma = 0;
  foreach ($result as $item) {
    $somma += $item->getAttribute('quantita');
  }

  return $somma;
}
?>
