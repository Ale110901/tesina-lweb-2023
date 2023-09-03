<?php
require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

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
        $cred_spesi_off = $offerta->getElementsByTagName('creditiSpesi')[0]->textContent;
        $cred_spesi_att = calcola_crediti_spesi($id_utente, $data_inizio);
        if ($cred_spesi_att >= $cred_spesi_off) {
          array_push($off_app, $offerta);
        }
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
        if ($id_prod_off == $id_prod_prod) {
          array_push($off_app, $offerta);
        }
        break;
      case 'categoria':
        // "il prodotto e' di una determinata categoria"
        $id_cat_prod = $prodotto->getElementsByTagName('categoria')[0]->textContent;
        $id_cat_off = $offerta->getElementsByTagName('idCategoria')[0]->textContent;
        if ($id_cat_off == $id_cat_prod) {
          array_push($off_app, $offerta);
        }
        break;
      case 'eccMag':
        // "e' presente in magazzino un’eccedenza del prodotto"
        $id_prod_prod = $prodotto->getAttribute('id');
        $qta_prod = $prodotto->getElementsByTagName('quantita')[0]->textContent;

        $id_prod_off = $offerta->getElementsByTagName('idProdotto')[0]->textContent;
        $qta_off = $offerta->getElementsByTagName('quantitaMin')[0]->textContent;

        if ($id_prod_off == $id_prod_prod && $qta_prod >= $qta_off) {
          array_push($off_app, $offerta);
        }
        break;
    }
  }

  return $off_app;
}

function aggiungi_offerta($id, $campi) {
  global $doc_offerte;

  $root = $doc_offerte->documentElement;

  $offerte = $root->childNodes;
  if ($id == 0) {
    $id = get_next_id($offerte);
  }

  $offerta = $doc_offerte->createElement('offerta');
  $offerta->setAttribute('id', $id);


  $tipo = $doc_offerte->createElement('tipo', $campi['tipo']);
  $offerta->appendChild($tipo);

  if ($campi['tipo'] === 'sconto') {
    $percentuale = $doc_offerte->createElement('percentuale', $campi['percentuale']);
    $offerta->appendChild($percentuale);
  } else if ($campi['tipo'] === 'bonus') {
    $numCrediti = $doc_offerte->createElement('numCrediti', $campi['numCrediti']);
    $offerta->appendChild($numCrediti);
  }


  $target = $doc_offerte->createElement('target', $campi['target']);
  $offerta->appendChild($target);

  switch ($campi['target']) {
    case 'credData':
      $creditiSpesi = $doc_offerte->createElement('creditiSpesi', $campi['creditiSpesi']);
      $offerta->appendChild($creditiSpesi);
      $dataInizio = $doc_offerte->createElement('dataInizio', $campi['dataInizio']);
      $offerta->appendChild($dataInizio);
      break;

    case 'reputazione':
      $reputazione = $doc_offerte->createElement('reputazione', $campi['reputazione']);
      $offerta->appendChild($reputazione);
      break;

    case 'dataReg':
      $anni = $doc_offerte->createElement('anni', $campi['anni']);
      $offerta->appendChild($anni);
      break;

    case 'prodSpec':
      $idProdotto = $doc_offerte->createElement('idProdotto', $campi['idProdotto']);
      $offerta->appendChild($idProdotto);
      break;

    case 'categoria':
      $idCategoria = $doc_offerte->createElement('idCategoria', $campi['idCategoria']);
      $offerta->appendChild($idCategoria);
      break;

    case 'eccMag':
      $idProdotto = $doc_offerte->createElement('idProdotto', $campi['idProdotto']);
      $offerta->appendChild($idProdotto);
      $quantitaMin = $doc_offerte->createElement('quantitaMin', $campi['quantitaMin']);
      $offerta->appendChild($quantitaMin);
      break;
  }

  $root->appendChild($offerta);

  save_xml($doc_offerte, 'offerte');

  // BUG: se non ricarico il documento i prossimi xpath() continuano ad usare il documento vecchio
  $doc_offerte = load_xml('offerte');

  return true;
}

function modifica_offerta($id, $campi) {
  elimina_offerta($id);
  aggiungi_offerta($id, $campi);
}

function elimina_offerta($id) {
  global $doc_offerte;

  $result = xpath($doc_offerte, 'offerte', '/ns:offerte/ns:offerta[@id=' . $id . ']');
  $offerta = $result[0];

  $offerte = $offerta->parentNode;
  $offerte->removeChild($offerta);

  save_xml($doc_offerte, 'offerte');

  return true;
}

function elimina_offerte_prodotto($id_prodotto) {
  global $doc_offerte;

  $root = $doc_offerte->documentElement;
  $offerte = $root->childNodes;

  $da_eliminare = [];

  foreach ($offerte as $offerta) {
    $target = $offerta->getElementsByTagName('target')[0]->textContent;

    if ($target !== 'prodSpec' && $target !== 'eccMag') {
      continue;
    }

    $id_prod_off = $offerta->getElementsByTagName('idProdotto')[0]->textContent;

    if ($id_prod_off == $id_prodotto) {
      array_push($da_eliminare, $offerta);
    }
  }

  foreach ($da_eliminare as $offerta) {
    $root->removeChild($offerta);
  }

  save_xml($doc_offerte, 'offerte');

  return true;
}

function ottieni_info_offerta($id) {
  global $doc_offerte;

  $result = xpath($doc_offerte, 'offerte', "/ns:offerte/ns:offerta[@id=$id]");
  if ($result->length !== 1) {
    return null;
  }

  $offerta = $result[0];

  $result = [
    'percentuale' => 0,
    'numCrediti' => 0,
    'creditiSpesi' => 0,
    'dataInizio' => '1970-01-01',
    'reputazione' => 0,
    'anni' => 0,
    'idProdotto' => 1,
    'idCategoria' => 1,
    'quantitaMin' => 0,
  ];

  $result['tipo'] = $offerta->getElementsByTagName('tipo')[0]->textContent;

  if ($result['tipo'] === 'sconto') {
    $result['percentuale'] = $offerta->getElementsByTagName('percentuale')[0]->textContent;
  } else if ($result['tipo'] === 'bonus') {
    $result['numCrediti'] = $offerta->getElementsByTagName('numCrediti')[0]->textContent;
  }

  $result['target'] = $offerta->getElementsByTagName('target')[0]->textContent;

  switch ($result['target']) {
    case 'credData':
      $result['creditiSpesi'] = $offerta->getElementsByTagName('creditiSpesi')[0]->textContent;
      $result['dataInizio'] = $offerta->getElementsByTagName('dataInizio')[0]->textContent;
      break;

    case 'reputazione':
      $result['reputazione'] = $offerta->getElementsByTagName('reputazione')[0]->textContent;
      break;

    case 'dataReg':
      $result['anni'] = $offerta->getElementsByTagName('anni')[0]->textContent;
      break;

    case 'prodSpec':
      $result['idProdotto'] = $offerta->getElementsByTagName('idProdotto')[0]->textContent;
      break;

    case 'categoria':
      $result['idCategoria'] = $offerta->getElementsByTagName('idCategoria')[0]->textContent;
      break;

    case 'eccMag':
      $result['idProdotto'] = $offerta->getElementsByTagName('idProdotto')[0]->textContent;
      $result['quantitaMin'] = $offerta->getElementsByTagName('quantitaMin')[0]->textContent;
      break;
  }

  return $result;
}
?>
