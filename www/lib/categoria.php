<?php
require_once(RC_ROOT . '/lib/xml.php');

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
?>
