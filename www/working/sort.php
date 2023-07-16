<?php
require_once('../config.php');

function sort_by_element_txt($elements, $key) {
  usort($elements, function($aElement, $bElement) use ($key) {
    $aValue = $aElement->getElementsByTagName($key)[0]->textContent;
    $bValue = $bElement->getElementsByTagName($key)[0]->textContent;

    return strnatcmp($aValue, $bValue);
  });

  return $elements;
}

function sort_by_element_dec($elements, $key) {
  usort($elements, function($aElement, $bElement) use ($key) {
    $aValue = $aElement->getElementsByTagName($key)[0]->textContent;
    $bValue = $bElement->getElementsByTagName($key)[0]->textContent;

    $aValue = floatval($aValue);
    $bValue = floatval($bValue);

    return $aValue <=> $bValue;
  });

  return $elements;
}

function domlist_to_array($domlist) {
  $arr = [];
  for ($i = 0; $i < $domlist->length; $i++) {
    $arr[$i] = $domlist->item($i);
  }
  return $arr;
}

function load_xml($filename) {
  $xmlString = '';
  foreach(file($filename) as $line) {
    $xmlString .= trim($line);
  }
  $doc = new DOMDocument();
  $doc->loadXML($xmlString);

  return $doc;
}

function save_xml($doc, $filename) {
  $doc->save($filename);
}


$xmlFile = RC_ROOT . '/data/prodotti.xml';
$key = 'costo';

$doc = load_xml($xmlFile);
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
