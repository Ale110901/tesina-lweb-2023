<?php
require_once($rc_root . '/lib/domande.php');
require_once($rc_root . '/lib/offerte.php');
require_once($rc_root . '/lib/recensioni.php');
require_once($rc_root . '/lib/xml.php');

$doc_prodotti = load_xml('prodotti');

function aggiungi_prodotto($nome, $marca, $descrizione, $costo, $categoria, $quantita, $immagine) {
  global $doc_prodotti;

  $root = $doc_prodotti->documentElement;
  $prodotti = $root->childNodes;

  $nuovo_prodotto = $doc_prodotti->createElement('prodotto');

  $id = get_next_id($prodotti);
  $nuovo_prodotto->setAttribute('id', $id);

  $el_marca = $doc_prodotti->createElement('marca', $marca);
  $nuovo_prodotto->appendChild($el_marca);

  $el_nome = $doc_prodotti->createElement('nome', $nome);
  $nuovo_prodotto->appendChild($el_nome);

  $el_descrizione = $doc_prodotti->createElement('descrizione', $descrizione);
  $nuovo_prodotto->appendChild($el_descrizione);

  $el_costo = $doc_prodotti->createElement('costo', $costo);
  $nuovo_prodotto->appendChild($el_costo);

  $el_categoria = $doc_prodotti->createElement('categoria', $categoria);
  $nuovo_prodotto->appendChild($el_categoria);

  $el_quantita = $doc_prodotti->createElement('quantita', $quantita);
  $nuovo_prodotto->appendChild($el_quantita);

  $root->appendChild($nuovo_prodotto);

  save_xml($doc_prodotti, 'prodotti');

  $upload_ok = isset($immagine['error']) &&
    $immagine['error'] === UPLOAD_ERR_OK;
  if ($upload_ok) {
    $nome_temp = $immagine['tmp_name'];
    $nome_finale = $rc_root . '/res/img/prodotti/' . $id . '.png';
    move_uploaded_file($nome_temp, $nome_finale);
  }

  return true;
}

function elimina_prodotto($id) {
  global $doc_prodotti;

  $result = xpath($doc_prodotti, 'prodotti', '/ns:prodotti/ns:prodotto[@id=' . $id . ']');
  $prodotto = $result[0];

  $prodotti = $prodotto->parentNode;
  $prodotti->removeChild($prodotto);

  save_xml($doc_prodotti, 'prodotti');

  elimina_recensioni($id);
  elimina_domande($id);
  elimina_offerte_prodotto($id);
}

function modifica_prodotto($id, $nome, $marca, $descrizione, $costo, $categoria, $quantita, $immagine) {
  global $doc_prodotti;

  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id=$id]");
  $prodotto = $result[0];

  $prodotto->getElementsByTagName('nome')[0]->textContent = $nome;
  $prodotto->getElementsByTagName('marca')[0]->textContent = $marca;
  $prodotto->getElementsByTagName('descrizione')[0]->textContent = $descrizione;
  $prodotto->getElementsByTagName('costo')[0]->textContent = $costo;
  $prodotto->getElementsByTagName('categoria')[0]->textContent = $categoria;
  $prodotto->getElementsByTagName('quantita')[0]->textContent = $quantita;

  save_xml($doc_prodotti, 'prodotti');

  $upload_ok = isset($immagine['error']) &&
    $immagine['error'] === UPLOAD_ERR_OK;
  if ($upload_ok) {
    $nome_temp = $immagine['tmp_name'];
    $nome_finale = $rc_root . '/res/img/prodotti/' . $p_id . '.png';
    move_uploaded_file($nome_temp, $nome_finale);
  }

  return true;
}

function ottieni_info_prodotto($id) {
  global $doc_prodotti;

  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id=$id]");
  if ($result->length !== 1) {
    return null;
  }

  $prodotto = $result[0];

  $nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
  $marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
  $descrizione = $prodotto->getElementsByTagName('descrizione')[0]->textContent;
  $costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
  $categoria = $prodotto->getElementsByTagName('categoria')[0]->textContent;
  $quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;

  return [
    'nome' => $nome,
    'marca' => $marca,
    'descrizione' => $descrizione,
    'costo' => $costo,
    'categoria' => $categoria,
    'quantita' => $quantita,
  ];
}

function scala_qta_prodotto($id, $qta_diff) {
  global $doc_prodotti;

  $quantita = xpath($doc_prodotti, 'prodotti', '/ns:prodotti/ns:prodotto[@id=' . $id . ']/ns:quantita')[0];
  $quantita->textContent -= $qta_diff;

  save_xml($doc_prodotti, 'prodotti');

  return true;
}
?>
