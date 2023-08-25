<?php
$rc_root = realpath(__DIR__ . '/..');
require_once($rc_root . '/lib/utils.php');
require_once($rc_root . '/lib/xml.php');

$num_utenti = 3;
$nomi = file($rc_root . '/generatori/ut_nomi.txt');
$cognomi = file($rc_root . '/generatori/ut_cognomi.txt');

$doc_utenti = load_xml('utenti');

for ($i = 0; $i < $num_utenti; $i++) {
  $attivo = rand(0, 1) === 1 ? 'true' : 'false';

  $nome = $nomi[array_rand($nomi)];
  $nome = substr($nome, 0, strlen($nome) - 1);

  $cognome = $cognomi[array_rand($cognomi)];
  $cognome = substr($cognome, 0, strlen($cognome) - 1);

  $email = strtolower($nome) . '.' . strtolower($cognome) . '@gmail.com';
  $telefono = '+390773' . rand(1000000, 9999999);
  $indirizzo = 'Lololol';
  $codice_fiscale = 'Lerolerolero';
  $reputazione = rand(0, 1000);
  $credito = rand(1, 300);
  $data_reg = rand_date();

  registra_utente($attivo, $nome, $cognome, $email, 'password',
    $telefono, $indirizzo, $codice_fiscale, $reputazione, $credito, $data_reg);
}

save_xml($doc_utenti, 'utenti');


function registra_utente($attivo, $nome, $cognome, $email, $password,
  $telefono, $indirizzo, $codice_fiscale, $reputazione, $credito, $data_reg)
{
  global $doc_utenti;

  $root = $doc_utenti->documentElement;
  $utenti = $root->childNodes;

  $nuovo_utente = $doc_utenti->createElement('utente');

  $id_utente = get_next_id($utenti);
  $nuovo_utente->setAttribute('id', $id_utente);
  $nuovo_utente->setAttribute('tipo', 'cliente');
  $nuovo_utente->setAttribute('email', $email);

  $el_attivo = $doc_utenti->createElement('attivo', $attivo);
  $nuovo_utente->appendChild($el_attivo);

  $el_nome = $doc_utenti->createElement('nome', $nome);
  $nuovo_utente->appendChild($el_nome);

  $el_cognome = $doc_utenti->createElement('cognome', $cognome);
  $nuovo_utente->appendChild($el_cognome);

  $el_telefono = $doc_utenti->createElement('telefono', $telefono);
  $nuovo_utente->appendChild($el_telefono);

  $el_indirizzo = $doc_utenti->createElement('indirizzo', $indirizzo);
  $nuovo_utente->appendChild($el_indirizzo);

  $el_codice_fiscale = $doc_utenti->createElement('codiceFiscale', $codice_fiscale);
  $nuovo_utente->appendChild($el_codice_fiscale);

  $el_password = $doc_utenti->createElement('password', md5($password));
  $nuovo_utente->appendChild($el_password);

  $el_reputazione = $doc_utenti->createElement('reputazione', $reputazione);
  $nuovo_utente->appendChild($el_reputazione);

  $el_credito = $doc_utenti->createElement('credito', $credito);
  $nuovo_utente->appendChild($el_credito);

  $el_data_reg = $doc_utenti->createElement('dataRegistrazione', $data_reg);
  $nuovo_utente->appendChild($el_data_reg);

  $el_carrello = $doc_utenti->createElement('carrello');
  $nuovo_utente->appendChild($el_carrello);

  $root->appendChild($nuovo_utente);

  return true;
}
?>
