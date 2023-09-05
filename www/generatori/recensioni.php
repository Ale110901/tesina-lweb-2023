<?php
$rc_root = realpath(__DIR__ . '/..');
require_once($rc_root . '/lib/utils.php');
require_once($rc_root . '/lib/xml.php');

$categorie = [
  'proteine' => [ 1, 2, 3, 4, 6, 8, 9, 10, 15, 19, 20, 21, 22 ],
  'barrette' => [ 5, 7, 11, 12, 13, 14, 16, 17, 18 ],
  'vitamine' => [ 23, 24, 25 ],
  'abbigliamento' => [ 26, 27, 28, 29, 30 ],
  'accessori' => [ 31, 32, 33, 34 ],
];

$utenti = [
  1,
  4,
  5,
  6,
  7,
  8,
  9,
  10,
  11,
  12,
];
$rec_per_prod = 4;
$rat_per_rec = 7;

$doc_recensioni = load_xml('recensioni');

foreach ($categorie as $nome => $prodotti) {
  $testi = file($rc_root . '/generatori/rec_' . $nome . '.txt');

  for ($i = 0; $i < count($prodotti); $i++) {
    for ($j = 0; $j < $rec_per_prod; $j++) {
      $id_prodotto = $prodotti[$i];

      $testo = $testi[array_rand($testi)];
      $testo = substr($testo, 0, strlen($testo) - 1);
      $id_ut_rec = $utenti[array_rand($utenti)];
      $data = rand_date();

      $ratings = [];
      $ut_util = [ $id_ut_rec ];

      for ($k = 0; $k < $rat_per_rec; $k++) {
        $diff = array_diff($utenti, $ut_util);
        if (count($diff) === 0) {
          break;
        }

        $id_ut_rat = $diff[array_rand($diff)];
        $supporto = rand(1, 3);
        $utilita = rand(1, 5);

        $ratings[$k] = gen_rating($id_ut_rat, $supporto, $utilita);

        array_push($ut_util, $id_ut_rat);
      }

      aggiungi_recensione($id_prodotto, $testo, $data, $id_ut_rec, $ratings);
    }
  }
}

save_xml($doc_recensioni, 'recensioni');


function aggiungi_recensione($id_prodotto, $contenuto, $data, $id_utente, $ratings) {
  global $doc_recensioni;

  $root = $doc_recensioni->documentElement;
  $recensioni = $root->childNodes;

  $nuova_recensione = $doc_recensioni->createElement('recensione');

  $id_recensione = get_next_id($recensioni);

  $nuova_recensione->setAttribute('id', $id_recensione);
  $nuova_recensione->setAttribute('idProdotto', $id_prodotto);

  $el_idUt = $doc_recensioni->createElement('idUtente', $id_utente);
  $nuova_recensione->appendChild($el_idUt);

  $el_contenuto = $doc_recensioni->createElement('contenuto', $contenuto);
  $nuova_recensione->appendChild($el_contenuto);

  $el_data = $doc_recensioni->createElement('data', $data);
  $nuova_recensione->appendChild($el_data);

  $el_ratings = $doc_recensioni->createElement('ratings');
  foreach ($ratings as $rating) {
    $el_ratings->appendChild($rating);
  }
  $nuova_recensione->appendChild($el_ratings);

  $root->appendChild($nuova_recensione);

  return true;
}

function gen_rating($id_utente, $supporto, $utilita) {
  global $doc_recensioni;

  $rating = $doc_recensioni->createElement('rating');

  $rating->setAttribute('idUtente', $id_utente);

  $el_supporto = $doc_recensioni->createElement('supporto', $supporto);
  $rating->appendChild($el_supporto);

  $el_utilita = $doc_recensioni->createElement('utilita', $utilita);
  $rating->appendChild($el_utilita);

  return $rating;
}
?>
