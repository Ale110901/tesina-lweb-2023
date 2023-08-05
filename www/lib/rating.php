<?php
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

function aggiungi_rating($doc, $ratings, $supporto, $utilita) {
  $id_utente = $_SESSION['id_utente'];
  
  $rating = $doc_utenti->createElement('rating');

  $rating->setAttribute('idUtente', $id_utente);

  $el_supporto = $doc_utenti->createElement('supporto', $supporto);
  $rating->appendChild($el_supporto);

  $el_utilita = $doc_utenti->createElement('utilita', $utilita);
  $rating->appendChild($el_utilita);

  $ratings->appendChild($rating);
}
?>
