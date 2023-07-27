<?php
require_once('../config.php');

function load_xml($table) {
  $xmlFile = RC_ROOT . '/data/' . $table . '.xml';

  $xmlString = '';
  foreach(file($xmlFile) as $line) {
    $xmlString .= trim($line);
  }
  $doc = new DOMDocument();
  $doc->loadXML($xmlString);

  return $doc;
}

function save_xml($doc, $table) {
  $xmlFile = RC_ROOT . '/data/' . $table . '.xml';

  $doc->save($xmlFile);
}

/* eventualmente da fare con xpath... */
function access_verification($doc, $email, $password){
  $root = $doc->documentElement;
  $utenti = $root->childNodes;
  $trovato = false;
  $i = 0;
  do{
    $utente = $utenti->item($i);
    $password_utente = $utente->getElementsByTagName('password')[0]->textContent;
    $attivo_utente = $utente->getElementsByTagName('attivo')[0]->textContent;
    $email_utente = $utente->getAttribute('email');
    if($email_utente == $email && $password_utente == $password && $attivo_utente == true){
      $trovato = true;
    }
    $i++;
  }while($i < $utenti->length && !$trovato);

  return $trovato;
}

function domlist_to_array($domlist) {
  $arr = [];
  for ($i = 0; $i < $domlist->length; $i++) {
    $arr[$i] = $domlist->item($i);
  }
  return $arr;
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

function sort_by_element_txt($elements, $key) {
  usort($elements, function($aElement, $bElement) use ($key) {
    $aValue = $aElement->getElementsByTagName($key)[0]->textContent;
    $bValue = $bElement->getElementsByTagName($key)[0]->textContent;

    return strnatcmp($aValue, $bValue);
  });

  return $elements;
}
?>
