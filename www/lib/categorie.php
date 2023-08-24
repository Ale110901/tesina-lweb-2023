<?php
require_once($rc_root . '/lib/xml.php');

$doc_categorie = load_xml('categorie');

function ottieni_categoria($id_categoria) {
  global $doc_categorie;

  $result = xpath($doc_categorie, 'categorie', "/ns:categorie/ns:categoria[@id='$id_categoria']/ns:nome");
  if ($result->length !== 1) {
    return "Categoria sconosciuta";
  } else {
    return $result[0]->textContent;
  }
}

function elimina_categoria($id) {
  global $doc_categorie;

  $result = xpath($doc_categorie, 'categorie', '/ns:categorie/ns:categoria[@id=' . $id . ']');
  $categoria = $result[0];

  $categorie = $categoria->parentNode;
  $categorie->removeChild($categoria);

  save_xml($doc_categorie, 'categorie');

  return true;
}

function aggiungi_categoria($nome) {
  global $doc_categorie;

  $root = $doc_categorie->documentElement;
  $categorie = $root->childNodes;

  $id = get_next_id($categorie);

  $categoria = $doc_categorie->createElement('categoria');
  $categoria->setAttribute('id', $id);

  $nome =  $doc_categorie->createElement('nome', $nome);
  $categoria->appendChild($nome);

  $root->appendChild($categoria);

  save_xml($doc_categorie, 'categorie');

  return true;
}
?>
