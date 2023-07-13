<?php
require_once('../config.php');

header('Content-Type: text/plain');

$tables = [
  'accrediti',
  'categorie',
  'domande',
  'faq',
  'offerte',
  'ordini',
  'prodotti',
  'utenti'
  'recensioni',
];

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
  echo ("" . $errstr . "\n");
});

foreach ($tables as $table) {
  $xmlFile = RC_ROOT . '/data/' . $table . '.xml';
  $xsdFile = RC_ROOT . '/data/' . $table . '.xsd';

  $doc = new DOMDocument();
  $doc->load($xmlFile);

  $result = $doc->schemaValidate($xsdFile);
  if ($result) {
    printf("## %s: valida\n", $table);
  } else {
    printf("## %s: NON valida\n", $table);
  }
}
?>
