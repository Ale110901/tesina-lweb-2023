<?php
$rc_root = realpath(__DIR__ . '/../www/');

header('Content-Type: text/plain');

$tables = [
  'accrediti',
  'categorie',
  'domande',
  'faq',
  'offerte',
  'ordini',
  'prodotti',
  'recensioni',
  'utenti'
];

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
  echo ("" . $errstr . "\n");
});

foreach ($tables as $table) {
  $xmlFile = $rc_root . '/data/' . $table . '.xml';
  $xsdFile = $rc_root . '/data/' . $table . '.xsd';

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
