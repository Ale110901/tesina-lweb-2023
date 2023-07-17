<?php
require_once('../lib/xml.php');

$table = 'prodotti';
$key = 'costo';

$doc = load_xml($table);
$root = $doc->documentElement;
$prodotti = $root->childNodes;

$tmp = domlist_to_array($prodotti);

foreach ($tmp as $prodotto) {
  $value = $prodotto->getElementsByTagName($key)[0]->textContent;
  echo ($value . "\n");
}

$tmp = sort_by_element_dec($tmp, $key);
echo ("########\n");

foreach ($tmp as $prodotto) {
  $value = $prodotto->getElementsByTagName($key)[0]->textContent;
  echo ($value . "\n");
}
?>
