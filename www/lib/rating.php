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

function aggiorna_reputazione($doc, $id_utente, $supporto, $utilita){
  $reputazione = xpath($doc, 'utenti', "/ns:utenti/ns:utente[@id='$id_utente']/ns:reputazione")[0]->textContent;

  $result_u = xpath($doc, 'utenti', "/ns:utenti/ns:utente[@id='$id_utente']");
  $utente = $result_u[0];
  
  if($reputazione == 0){
    return;
  }

  if($supporto == 1){
    $aggiorna_supporto = -2;
  }
  if($supporto == 2){
    $aggiorna_supporto = 0;
  }
  if($supporto == 3){
    $aggiorna_supporto = 2;
  }
  if($utilita == 1){
    $aggiorna_utilita = -4;
  }
  if($utilita == 2){
    $aggiorna_utilita = -2;
  }
  if($utilita == 3){
    $aggiorna_utilita = 0;
  }
  if($utilita == 4){
    $aggiorna_utilita = 2;
  }
  if($utilita == 5){
    $aggiorna_utilita = 4;
  }

  $variazione_reputazione = $aggiorna_utilita + $aggiorna_supporto;

  if($reputazione <= abs($variazione_reputazione)) {
    $utente->getElementsByTagName('reputazione')[0]->textContent = 0;
  } else {
    $reputazione  += $variazione_reputazione;
    $utente->getElementsByTagName('reputazione')[0]->textContent = $reputazione;
  }

  save_xml($doc, 'utenti');
  
  return;
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
}

function aggiungi_rating_recensione($id_recensione, $supporto, $utilita) {
  $doc_recensioni = load_xml('recensioni');

  $result = xpath($doc_recensioni, 'recensioni', "/ns:recensioni/ns:recensione[@id='$id_recensione']/ns:ratings");
  $ratings = $result[0];

  aggiungi_rating($doc_recensioni, $ratings, $supporto, $utilita);

  save_xml($doc_recensioni, 'recensioni');

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
