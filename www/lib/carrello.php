<?php
require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

function aggiungi_carrello($id_prodotto, $qta_diff) {
  global $doc_utenti;

  $id_utente = $_SESSION['id_utente'];

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

  // BUG: se non ricarico il documento i prossimi xpath() continuano ad usare il documento vecchio
  $doc_utenti = load_xml('utenti');

  return true;
}

function conta_carrello() {
  global $doc_utenti;

  if (!isset($_SESSION['id_utente'])) {
    return 0;
  }

  $id_utente = $_SESSION['id_utente'];

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

function modifica_carrello($id_prodotto, $quantita) {
  global $doc_utenti;

  $id_utente = $_SESSION['id_utente'];

  $result = xpath($doc_utenti, 'utenti',
    "/ns:utenti/ns:utente[@id='$id_utente']/ns:carrello/ns:prodotto[@id='$id_prodotto']"
  );

  $prodotto = $result[0];
  if ($quantita == 0) {
    $carrello = $prodotto->parentNode;
    $carrello->removeChild($prodotto);
  } else {
    $prodotto->setAttribute('quantita', $quantita);
  }

  save_xml($doc_utenti, 'utenti');

  return true;
}

function svuota_carrello() {
  global $doc_utenti;

  $id_utente = $_SESSION['id_utente'];

  $result = xpath($doc_utenti, 'utenti',
    "/ns:utenti/ns:utente[@id='$id_utente']/ns:carrello"
  );

  $carrello = $result[0];
  $utente = $carrello->parentNode;
  $utente->removeChild($carrello);

  $carrello = $doc_utenti->createElement('carrello');
  $utente->appendChild($carrello);

  save_xml($doc_utenti, 'utenti');

  // BUG: se non ricarico il documento i prossimi xpath() continuano ad usare il documento vecchio
  $doc_utenti = load_xml('utenti');

  return true;
}
?>
