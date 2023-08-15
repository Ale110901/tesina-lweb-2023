<?php
require_once(RC_ROOT . '/lib/ordini.php');
require_once(RC_ROOT . '/lib/recensioni.php');
require_once(RC_ROOT . '/lib/utenti.php');

function calcola_rating_medio($ratings) {
  if ($ratings->length === 0) {
    return [
      'supporto' => 0.00,
      'utilita' =>  0.00
    ];
  }

  $acc_supp = 0.00;
  $acc_util = 0.00;

  for ($i = 0; $i < $ratings->length; $i++) {
    $rating = $ratings[$i];

    $rat_supp = $rating->getElementsByTagName('supporto')[0]->textContent;
    $rat_util = $rating->getElementsByTagName('utilita')[0]->textContent;

    $acc_supp += $rat_supp;
    $acc_util += $rat_util;
  }

  return [
    'supporto' => $acc_supp / $ratings->length,
    'utilita' =>  $acc_util / $ratings->length
  ];
}

function aggiorna_reputazione($id_ut_dest, $supporto, $utilita) {
  global $doc_ordini;
  global $doc_utenti;

  $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_ut_dest']/ns:reputazione");
  $rep_el = $result[0];
  $rep_num = $rep_el->textContent;

  if ($rep_num == 0) {
    return;
  }


  $k_funzione = 1;

  switch ($_SESSION['tipo_utente']) {
    case 'cliente':
      $id_ut_mitt = $_SESSION['id_utente'];

      $result = xpath($doc_ordini, 'ordini',
        "/ns:ordini/ns:ordine[@idUtente='$id_ut_mitt']/ns:prodotti/ns:prodotto[@id='$id_prod']");

      if ($result->length !== 0) {
        $k_funzione = 2;
      }

      break;
    case 'gestore':
      $k_funzione = 3;
      break;
  }


  $var_supporto = $k_funzione * (2 * $supporto - 4);  // (1..3) => k * (-2, 0, +2)
  $var_utilita =  $k_funzione * (2 * $supporto - 6);  // (1..5) => k * (-4, -2, 0, +2, +4)

  $var_tot = $var_utilita + $var_supporto;
  $rep_num += $var_tot;

  if ($rep_num < 0) {
    $rep_el->textContent = 0;
  } else {
    $rep_el->textContent = $rep_num;
  }

  save_xml($doc_utenti, 'utenti');

  return true;
}

function aggiungi_rating($doc, $ratings, $supporto, $utilita) {
  $id_utente = $_SESSION['id_utente'];

  $rating = $doc->createElement('rating');

  $rating->setAttribute('idUtente', $id_utente);

  $el_supporto = $doc->createElement('supporto', $supporto);
  $rating->appendChild($el_supporto);

  $el_utilita = $doc->createElement('utilita', $utilita);
  $rating->appendChild($el_utilita);

  $ratings->appendChild($rating);

  return true;
}

function aggiungi_rating_recensione($id_recensione, $supporto, $utilita) {
  global $doc_recensioni;

  $result = xpath($doc_recensioni, 'recensioni', "/ns:recensioni/ns:recensione[@id='$id_recensione']/ns:ratings");
  $ratings = $result[0];

  aggiungi_rating($doc_recensioni, $ratings, $supporto, $utilita);

  save_xml($doc_recensioni, 'recensioni');

  // BUG: se non ricarico il documento il resto continua ad usare il documento vecchio
  $doc_recensioni = load_xml('recensioni');

  return true;
}

/*
function ottieni_rating_personale($ratings) {
  foreach ($ratings as $rating) {
    if ($rating->getAttribute('id') == $id_utente) {

    }
  }

  return [
    'supporto' => 3,
    'utilita' => 2
  ];
}
*/
?>
