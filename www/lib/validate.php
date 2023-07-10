<?php
$xmlPath = '../data/';
$tables = [
  'accrediti',
  'categorie',
  'domande',
  'faq',
  'offerte',
  'ordini',
  'prodotti',
  'utenti'
];

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
  echo ("" . $errstr . "\n");
});

foreach ($tables as $table) {
  $xmlFile = $xmlPath . $table . '.xml';
  $xsdFile = $xmlPath . $table . '.xsd';

  /*
  $xmlString = '';
  $lines = file($xmlFile);
  if (!$lines) {
    printf("## %s: ERRORE\n", $table);
    continue;
  }
  foreach ($lines as $line) {
    $xmlString .= trim($line);
  }
  */
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
