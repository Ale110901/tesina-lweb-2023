<?php
require_once(RC_ROOT . '/lib/xml.php');

function ottieni_categoria($doc, $id_categoria) {
  $result = xpath($doc, 'categorie', "/ns:categorie/ns:categoria[@id='$id_categoria']/ns:nome");
  if ($result->length !== 1) {
    return "Categoria sconosciuta";
  } else {
    return $result[0]->textContent;
  }
}
?>
