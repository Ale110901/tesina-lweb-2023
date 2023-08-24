<?php
$rc_root = realpath(__DIR__ . '/..');

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

  $doc = new DOMDocument();
  $doc->load($xmlFile);

  $doc->formatOutput = true;
  $doc->preserveWhiteSpace = false;

  $doc->save($xmlFile);

  printf("## Riscritta tabella %s\n", $table);
}
?>
