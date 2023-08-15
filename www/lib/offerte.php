<?php
require_once(RC_ROOT . '/lib/utente.php');
require_once(RC_ROOT . '/lib/xml.php');

$doc_offerte = load_xml('offerte');

function calcola_sconto($offerte) {
  $sconto_tot = 0.00;

  foreach ($offerte as $offerta) {
    $tipo = $offerta->getElementsByTagName('tipo')[0]->textContent;
    if ($tipo !== 'sconto') {
      continue;
    }

    $sconto_off = $offerta->getElementsByTagName('percentuale')[0]->textContent;
    $sconto_tot += $sconto_off / 100;
  }

  return $sconto_tot;
}

function calcola_bonus($offerte) {
  $bonus_tot = 0;

  foreach ($offerte as $offerta) {
    $tipo = $offerta->getElementsByTagName('tipo')[0]->textContent;
    if ($tipo !== 'bonus') {
      continue;
    }

    $bonus_off = $offerta->getElementsByTagName('numCrediti')[0]->textContent;
    $bonus_tot += $bonus_off;
  }

  return $bonus_tot;
}

function offerte_applicabili($prodotto) {
  global $doc_offerte;
  global $doc_utenti;

  $id_utente = isset($_SESSION['id_utente']) ? $_SESSION['id_utente'] : 0;

  $root = $doc_offerte->documentElement;
  $offerte = $root->childNodes;

  $off_app = [];

  foreach ($offerte as $offerta) {
    $data_inizio = '1970-01-01';
    $target = $offerta->getElementsByTagName('target')[0]->textContent;

    switch ($target) {
      case 'credData':
        // "clienti che hanno speso X crediti da una certa data"
        $data_inizio = $offerta->getElementsByTagName('dataInizio')[0]->textContent;
      case 'credInizio':
        if ($id_utente === 0) {
          break;
        }
        // TODO
        break;
      case 'reputazione':
        // "clienti che hanno una reputazione >= X"
        if ($id_utente === 0) {
          break;
        }
        $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_utente']/ns:reputazione");
        $result = $result[0]->textContent;
        $reputazione = $offerta->getElementsByTagName('reputazione')[0]->textContent;
        if ($result >= $reputazione) {
          array_push($off_app, $offerta);
        }
        break;
      case 'dataReg':
        // "clienti che sono con noi da X anni"
        if ($id_utente === 0) {
          break;
        }
        $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='$id_utente']/ns:dataRegistrazione");
        $result = $result[0]->textContent;

        $data_reg = date_create($result);
        $oggi = date_create();
        $diff = date_diff($data_reg, $oggi)->format('%Y');
        $anni = $offerta->getElementsByTagName('anni')[0]->textContent;

        if ($diff >= $anni) {
          array_push($off_app, $offerta);
        }
        break;
      case 'prodSpec':
        // "e' un prodotto particolare"
        $id_prod_prod = $prodotto->getAttribute('id');
        $id_prod_off = $offerta->getElementsByTagName('idProdotto')[0]->textContent;
        if ($id_prod_off === $id_prod_prod) {
          array_push($off_app, $offerta);
        }
        break;
      case 'categoria':
        // "il prodotto e' di una determinata categoria"
        $id_cat_prod = $prodotto->getElementsByTagName('categoria')[0]->textContent;
        $id_cat_off = $offerta->getElementsByTagName('idCategoria')[0]->textContent;
        if ($id_cat_off === $id_cat_prod) {
          array_push($off_app, $offerta);
        }
        break;
      case 'eccMag':
        // "e' presente in magazzino un’eccedenza del prodotto"
        $qta_prod = $prodotto->getElementsByTagName('quantita')[0]->textContent;
        $qta_off = $offerta->getElementsByTagName('quantitaMin')[0]->textContent;
        if ($qta_prod >= $qta_off) {
          array_push($off_app, $offerta);
        }
        break;
    }
  }

  return $off_app;
}
?>
