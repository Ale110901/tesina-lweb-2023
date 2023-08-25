<?php
require_once($rc_root . '/lib/categorie.php');

function load_xml($table) {
  global $rc_root;

  $xmlFile = $rc_root . '/data/' . $table . '.xml';

  $xmlData = file($xmlFile);
  if (!$xmlData) {
    return null;
  }

  $xmlString = '';
  foreach($xmlData as $line) {
    $xmlString .= trim($line);
  }
  $doc = new DOMDocument();
  $doc->loadXML($xmlString);

  return $doc;
}

function save_xml($doc, $table) {
  global $rc_root;

  $xmlFile = $rc_root . '/data/' . $table . '.xml';

  $doc->formatOutput = true;
  $doc->preserveWhiteSpace = false;

  $doc->save($xmlFile);
}

function domlist_to_array($domlist) {
  $arr = [];
  for ($i = 0; $i < $domlist->length; $i++) {
    $arr[$i] = $domlist->item($i);
  }
  return $arr;
}

function sort_by_element_dec($elements, $key, $desc) {
  usort($elements, function($aElement, $bElement) use ($key, $desc) {
    $aValue = $aElement->getElementsByTagName($key)[0]->textContent;
    $bValue = $bElement->getElementsByTagName($key)[0]->textContent;

    $aValue = floatval($aValue);
    $bValue = floatval($bValue);

    if ($desc) {
      return $bValue <=> $aValue;
    } else {
      return $aValue <=> $bValue;
    }
  });

  return $elements;
}

function sort_by_element_txt($elements, $key, $desc) {
  usort($elements, function($aElement, $bElement) use ($key, $desc) {
    $aValue = $aElement->getElementsByTagName($key)[0]->textContent;
    $bValue = $bElement->getElementsByTagName($key)[0]->textContent;

    if ($key == 'categoria') {
      $aValue = ottieni_categoria($aValue);
      $bValue = ottieni_categoria($bValue);
    }

    if ($desc) {
      return strnatcmp($bValue, $aValue);
    } else {
      return strnatcmp($aValue, $bValue);
    }
  });

  return $elements;
}

function get_next_id($elements) {
  $next_id = 0;
  foreach ($elements as $element) {
    $cur_id = $element->getAttribute('id');
    if ($next_id < $cur_id) {
      $next_id = $cur_id;
    }
  }
  $next_id++;

  return $next_id;
}

function xpath($doc, $table, $query) {
  $xp = new DOMXPath($doc);
  $xp->registerNameSpace('ns', 'http://www.lweb.uni/tesina-rcstore/' . $table . '/');
  $result = $xp->evaluate($query);
  return $result;
}
?>
