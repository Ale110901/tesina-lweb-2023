<?php
require_once(RC_ROOT . '/lib/ordini.php');
require_once(RC_ROOT . '/lib/utenti.php');

const K_CLIENTE = 1;
const K_ACQUIRENTE = 2;
const K_GESTORE = 3;

const SCALA_EXP = 1 / 1390;

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

function aggiorna_reputazione($id_ut_dest, $id_prod, $supporto, $utilita) {
  global $doc_ordini;
  global $doc_utenti;

  $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_ut_dest']/ns:reputazione");
  $rep_dest = $result[0]->textContent;

  if ($rep_dest == 0) {
    return;
  }


  $k_funzione = K_CLIENTE;

  switch ($_SESSION['tipo_utente']) {
    case 'cliente':
      $id_ut_mitt = $_SESSION['id_utente'];


      $result = xpath($doc_ordini, 'ordini',
        "/ns:ordini/ns:ordine[@idUtente='$id_ut_mitt']/ns:prodotti/ns:prodotto[@id='$id_prod']");

      if ($result->length !== 0) {
        $k_funzione = K_ACQUIRENTE;
      }


      $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_ut_mitt']/ns:reputazione");
      $rep_mitt = $result[0]->textContent;

      $k_funzione = $k_funzione + (K_GESTORE - $k_funzione) * (1 - exp(-$rep_mitt / SCALA_EXP));

      break;
    case 'gestore':
      $k_funzione = K_GESTORE;
      break;
  }


  $var_supporto = $k_funzione * (2 * $supporto - 4);  // (1..3) => k * (-2, 0, +2)
  $var_utilita =  $k_funzione * (2 * $utilita - 6);   // (1..5) => k * (-4, -2, 0, +2, +4)

  $var_tot = $var_utilita + $var_supporto;
  $rep_dest += $var_tot;

  if ($rep_dest < 0) {
    $rep_el->textContent = 0;
  } else {
    $rep_el->textContent = $rep_dest;
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
