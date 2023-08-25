<?php
$rc_root = realpath(__DIR__ . '/..');
require_once($rc_root . '/lib/utils.php');
require_once($rc_root . '/lib/xml.php');

$utenti = [
  1,
  4,
];
$num_ordini = 30;
$max_prod_per_ord = 5;
$max_qta = 10;
$max_prodotti = 34;


$doc_ordini = load_xml('ordini');

for ($i = 0; $i < $num_ordini; $i++) {
  $id_utente = $utenti[array_rand($utenti)];
  $dataora = rand_datetime();
  $indirizzo = 'Via Doria, Latina, Italy';
  $prezzo = rand(50, 200);

  $prodotti = [];
  $prod_per_ord = rand(1, $max_prod_per_ord);

  for ($j = 0; $j < $prod_per_ord; $j++) {
    $id_prod = rand(1, $max_prodotti);
    $quantita = rand(1, $max_qta);

    array_push($prodotti, [ $id_prod, $quantita ]);
  }
  crea_ordine($id_utente, $dataora, $indirizzo, $prezzo, $prodotti);
}

save_xml($doc_ordini, 'ordini');


function crea_ordine($id_utente, $dataora, $indirizzo, $prezzo, $prodotti) {
  global $doc_ordini;

  $root = $doc_ordini->documentElement;
  $ordini = $root->childNodes;

  $id_ordine = get_next_id($ordini);

  $nuovo_ordine = $doc_ordini->createElement('ordine');

  $nuovo_ordine->setAttribute('id', $id_ordine);
  $nuovo_ordine->setAttribute('idUtente', $id_utente);

  $el_data = $doc_ordini->createElement('data', $dataora);
  $nuovo_ordine->appendChild($el_data);

  $el_indirizzo = $doc_ordini->createElement('indirizzo', $indirizzo);
  $nuovo_ordine->appendChild($el_indirizzo);

  $el_prezzo = $doc_ordini->createElement('prezzoFinale', $prezzo);
  $nuovo_ordine->appendChild($el_prezzo);

  $el_prodotti = $doc_ordini->createElement('prodotti');

  foreach ($prodotti as $prodotto) {
    $nuovo_prodotto = $doc_ordini->createElement('prodotto');

    $id_prod = $prodotto[0];
    $qta_prod = $prodotto[1];

    $nuovo_prodotto->setAttribute('id', $id_prod);
    $nuovo_prodotto->setAttribute('quantita', $qta_prod);

    $el_prodotti->appendChild($nuovo_prodotto);
  }

  $nuovo_ordine->appendChild($el_prodotti);

  $root->appendChild($nuovo_ordine);

  return true;
}
?>
