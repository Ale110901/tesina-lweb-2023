<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/offerte.php');
require_once(RC_ROOT . '/lib/prodotti.php');
require_once(RC_ROOT . '/lib/rating.php');
require_once(RC_ROOT . '/lib/recensioni.php');
require_once(RC_ROOT . '/lib/utenti.php');
require_once(RC_ROOT . '/lib/xml.php');

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);

$aggiungi_rec = isset($_POST['azione']) && $_POST['azione'] === 'aggiungi_recensione';
$elimina_rec = isset($_POST['azione']) && $_POST['azione'] === 'elimina_recensione';
$rating_rec = isset($_POST['azione']) && $_POST['azione'] === 'rating_recensione';

$aggiungi_dom = isset($_POST['azione']) && $_POST['azione'] === 'aggiungi_domanda';
$elimina_dom = isset($_POST['azione']) && $_POST['azione'] === 'elimina_domanda';
$rating_dom = isset($_POST['azione']) && $_POST['azione'] === 'rating_domanda';

$aggiungi_risp = isset($_POST['azione']) && $_POST['azione'] === 'aggiungi_risposta';
$elimina_risp = isset($_POST['azione']) && $_POST['azione'] === 'elimina_risposta';
$rating_risp = isset($_POST['azione']) && $_POST['azione'] === 'rating_risposta';

if ($id_valido) {
  $id_prodotto = $_GET['id'];

  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id=$id_prodotto]");
  if ($result->length !== 1) {
    $id_valido = false;
  } else {
    $prodotto = $result[0];

    $nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
    $marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
    $descrizione = $prodotto->getElementsByTagName('descrizione')[0]->textContent;
    $costo_orig = $prodotto->getElementsByTagName('costo')[0]->textContent;
    $categoria = $prodotto->getElementsByTagName('categoria')[0]->textContent;
    $quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;

    $off_app = offerte_applicabili($prodotto);
    $sconto = calcola_sconto($off_app);
    $bonus = calcola_bonus($off_app);
    $costo_finale = round($costo_orig * (1 - $sconto), 2);

    $disponibile = $quantita > 0;


    if ($aggiungi_rec) {
      $contenuto = $_POST['recensione'];
      aggiungi_recensione($id_prodotto, $contenuto);
    }

    if ($elimina_rec) {
      $id_recensione = $_POST['id_recensione'];
      elimina_recensione($id_recensione);
    }

    if ($rating_rec) {
      $id_recensione = $_POST['id_recensione'];
      $supporto = $_POST['rec_supp'];
      $utilita = $_POST['rec_util'];
      $id_ut_rec_agg = $_POST['utente_recensione'];

      aggiungi_rating_recensione($id_recensione, $supporto, $utilita);
      aggiorna_reputazione($id_ut_rec_agg, $id_prodotto, $supporto, $utilita);
    }


    if ($aggiungi_dom) {
      $contenuto = $_POST['domanda'];
      aggiungi_domanda($id_prodotto, $contenuto); /* CONTROLLARE */
    }

    if ($elimina_dom) {
      $id_domanda = $_POST['id_domanda'];
      elimina_domanda($id_domanda);
    }

    if ($rating_dom) {
      $id_domanda = $_POST['id_domanda'];
      $supporto = $_POST['dom_supp'];
      $utilita = $_POST['dom_util'];
      $id_ut_dom_agg = $_POST['utente_domanda'];

      aggiungi_rating_domanda($id_domanda, $supporto, $utilita);
      aggiorna_reputazione($id_ut_dom_agg, $id_prodotto, $supporto, $utilita);
    }


    if ($aggiungi_risp) {
      $id_domanda = $_POST['id_domanda'];
      $contenuto = $_POST['risposta'];
      aggiungi_risposta($id_domanda, $contenuto);
    }

    if ($elimina_risp) {
      $id_domanda = $_POST['id_domanda'];
      $id_risposta = $_POST['id_risposta'];
      elimina_risposta($id_domanda, $id_risposta);
    }

    if ($rating_risp) {
      $id_domanda = $_POST['id_domanda'];
      $id_risposta = $_POST['id_risposta'];
      $supporto = $_POST['risp_supp'];
      $utilita = $_POST['risp_util'];
      $id_ut_risp_agg = $_POST['utente_risposta'];

      aggiungi_rating_risposta($id_domanda, $id_risposta, $supporto, $utilita);
      aggiorna_reputazione($id_ut_risp_agg, $id_prodotto, $supporto, $utilita);
    }

    $recensioni = xpath($doc_recensioni, 'recensioni', "/ns:recensioni/ns:recensione[@idProdotto='$id_prodotto']");

    $doc_domande = load_xml('domande');
    $domande = xpath($doc_domande, 'domande', "/ns:domande/ns:domanda[@idProdotto='$id_prodotto']");

    $qta_max = $quantita;

    if ($loggato) {
      $id_ut_corr = $_SESSION['id_utente'];

      $result = xpath($doc_utenti, 'utenti', "/ns:utenti/ns:utente[@id='" . $id_ut_corr . "']/ns:carrello/ns:prodotto[@id='" . $id_prodotto . "']");
      if ($result[0] !== null) {
        $qta_carrello = $result[0]->getAttribute('quantita');
        $qta_max -= $qta_carrello;
      }
    }
  }
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title><?php echo($nome); ?> &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/prodotto.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/faq.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>

  <div id="contenuto">
<?php if (!$id_valido) { ?>
    <p>Prodotto non trovato.</p>
<?php } else { ?>
    <div id="pag-prodotto">
    <a href="<?php echo(RC_SUBDIR); ?>/catalogo.php" title="Indietro">&#x2190;</a>
      <div id="prod-top">
        <div id="prod-top-sx">
          <img class="img-prodotto" src="<?php echo(RC_SUBDIR); ?>/res/img/prodotti/<?php echo($id_prodotto); ?>.png" alt="Immagine prodotto <?php echo($id_prodotto); ?>" ></img>
        </div>
        <div id="prod-top-mid">
          <p id="marca"><?php echo($marca); ?></p>
          <p id="nome"><?php echo($nome); ?></p>
          <p class="mb-16" id="descrizione"><?php echo($descrizione); ?></p>
<?php if ($e_gestore) { ?>
          <a class="button" href="<?php echo(RC_SUBDIR); ?>/gestore/prodotto.php?id=<?php echo($id_prodotto); ?>">Modifica info</a>
<?php } ?>
        </div>
        <div id="prod-top-dx" class="riquadro">
          <div id="prod-top-dx-info">
            <div id="col-1">
<?php if ($disponibile) { ?>
              <p>&#x1F44D; Quantità disponibile: <?php echo($quantita); ?></p>
<?php } else { ?>
              <p>&#x274C; Prodotto terminato!</p>
<?php } ?>
<?php if ($sconto > 0.00) { ?>
              <p>&#x1F4B2; <?php echo($sconto * 100); ?>% di sconto</p>
<?php } ?>
<?php if ($bonus > 0) { ?>
              <p>&#x1F4B2; +<?php echo($bonus); ?> crediti</p>
<?php } ?>
            </div>
            <div id="col-2">
<?php if ($costo_orig - $costo_finale >= 0.01) { ?>
              <span id="prezzo-originale" class="barrato"><?php echo(number_format($costo_orig, 2)); ?> &euro;</span>
<?php } ?>
              <span id='prezzo-finale'><?php echo(number_format($costo_finale, 2)); ?> &euro;</span>
            </div>
          </div>
            <form id="prod-top-dx-action" action="<?php echo(RC_SUBDIR); ?>/cliente/carrello.php" method="post">
              <input type="hidden" name="id_prodotto" value="<?php echo($id_prodotto); ?>" />
              <div id="input-qta">
                <input type="number" name="quantita" class="input-box" value="1" min="1" step="1" max="<?php echo($qta_max); ?>" <?php if (!$disponibile || $qta_max === 0) echo ('disabled'); ?>/>
              </div>
              <div id="btn-aggiungi">
                <button type="submit" name="azione" value="aggiungi" class="button ml-8" <?php if (!$disponibile || $qta_max === 0) echo ('disabled'); ?>>Aggiungi al carrello</button>
              </div>
            </form>
        </div>
      </div>
      <button id="tab-rec" class="tab-attiva" onclick="mostraRecensioni()"> Recensioni</button><button id="tab-dr" class="tab-inattiva" onclick="mostraDR()"> Domande e Risposte</button>
      <div id="recensioni">
        <h3 class="mb-16">Recensioni</h3>
<?php
  if ($loggato && ($e_cliente || $e_gestore)) {
    $id_ut_corr = $_SESSION['id_utente'];
?>
          <button id="button-recensione" onclick="mostraAggiuntaRecensione()">&#x1F4DD Scrivi una nuova recensione</button><br />
          <form method="post" id="recensione_nuova" class="nascosto mt-16">
            <textarea id="input-recensione" class="input-flat" name="recensione" rows="6"></textarea>
            <button type="submit" onclick="mostraAggiuntaRecensione()" name="azione" value="aggiungi_recensione" class="ml-8" title="Invia recensione">&#x2714</button>
          </form>
<?php
  }
  foreach ($recensioni as $recensione) {
    $id_recensione = $recensione->getAttribute('id');
    $id_ut_rec = $recensione->getElementsByTagName('idUtente')[0]->textContent;
    $contenuto = $recensione->getElementsByTagName('contenuto')[0]->textContent;

    $info_ut_rec = ottieni_info_utente($id_ut_rec);

    $ratings_rec = $recensione->getElementsByTagName('ratings')[0]->childNodes;
    $rat_med_rec = calcola_rating_medio($ratings_rec);


    $rating_pers = [
      'supporto' => 0,
      'utilita' => 0
    ];
    $rating_abilitato = false;

    if ($loggato && $id_ut_rec !== $id_ut_corr) {
      $result = xpath($doc_recensioni, 'recensioni', "/ns:recensioni/ns:recensione[@id='$id_recensione']/ns:ratings/ns:rating[@idUtente='$id_ut_corr']");

      if ($result->length !== 0) {
        $rating_pers['supporto'] = $result[0]->getElementsByTagName('supporto')[0]->textContent;
        $rating_pers['utilita'] = $result[0]->getElementsByTagName('utilita')[0]->textContent;
      } else {
        $rating_abilitato = true;
      }
    }

    $rs = [];
    $ru = [];

    for ($i = 0; $i < 3; $i++) {
      $rs[$i] = $i < $rating_pers['supporto'] ? '&#x2605' : '&#x2606';
    }
    for ($i = 0; $i < 5; $i++) {
      $ru[$i] = $i < $rating_pers['utilita'] ? '&#x2605' : '&#x2606';
    }

    $controllo_rec = $id_ut_rec === $id_ut_corr;
    $evidenzia_contributo_rec = ($controllo_rec) ? 'colorato-r' : '';
    $form_abilitato_rec = ($controllo_rec) ? 'nascosto' : '';
?>
        <div class="flex-row my-32 <?php echo($evidenzia_contributo_rec); ?>">
          <div class="fb-20">
            Supporto <?php echo(number_format($rat_med_rec['supporto'], 1)); ?>, utilit&agrave; <?php echo(number_format($rat_med_rec['utilita'], 1)); ?>
            <p>da <i class="<?php if ($controllo_rec) echo("grassetto"); ?>"><?php echo($info_ut_rec['nome'] . ' ' . $info_ut_rec['cognome']); ?></i> <?php if ($controllo_rec) echo(" &#x01F464;"); ?></p>
<?php if ($loggato && ($e_cliente || $e_gestore)) { ?>
            <div class="riquadro pa-8 mt-8 mr-32 <?php echo($form_abilitato_rec); ?>">
              <p id="rec_supp_<?php echo($id_recensione); ?>">Supporto:
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_supp', 1)"<?php } ?>><?php echo($rs[0]); ?></a>
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_supp', 2)"<?php } ?>><?php echo($rs[1]); ?></a>
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_supp', 3)"<?php } ?>><?php echo($rs[2]); ?></a>
              </p>
              <form id="rec_rat_<?php echo($id_recensione); ?>" method="post">
                <input type="hidden" name="id_recensione" value="<?php echo($id_recensione); ?>" />
                <input type="hidden" name="utente_recensione" value="<?php echo($id_ut_rec); ?>" />
                <input type="hidden" name="rec_supp" value="0" />
                <input type="hidden" name="rec_util" value="0" />
                <button type="submit" name="azione" value="rating_recensione" class="button-2 destra mr-4" <?php if (!$rating_abilitato) echo ('disabled'); ?>>Invia</button>
              </form>
              <p id="rec_util_<?php echo($id_recensione); ?>">Utilit&agrave;:
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_util', 1)"<?php } ?>><?php echo($ru[0]); ?></a>
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_util', 2)"<?php } ?>><?php echo($ru[1]); ?></a>
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_util', 3)"<?php } ?>><?php echo($ru[2]); ?></a>
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_util', 4)"<?php } ?>><?php echo($ru[3]); ?></a>
                <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('rec_rat', <?php echo($id_recensione); ?>, 'rec_util', 5)"<?php } ?>><?php echo($ru[4]); ?></a>
              </p>
            </div>
<?php } ?>
          </div>
          <div class="fb-80">
<?php if ($e_gestore) { ?>
            <form action="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($id_prodotto); ?>" method="post" class="inline">
              <input type="hidden" name="id_recensione" value="<?php echo($id_recensione); ?>" />
                <button type="submit" class="button-icona" name="azione" value="elimina_recensione" title="elimina recensione">&#x01F5D1</button>
            </form>
<?php } ?>
            <p class="giustificato inline"><?php echo($contenuto); ?></p>
          </div>
        </div>
<?php
  }
?>
      </div>
      <div id="dr" class="nascosto">
        <h3>Domande e risposte</h3>
<?php if ($loggato && ($e_cliente || $e_gestore)) { ?>
        <button id="button-recensione" class="mt-16" onclick="mostraAggiuntaDomande()">&#x1F4DD Scrivi una nuova domanda</button><br />
        <form method="post" id="domanda_nuova" class="nascosto mt-16">
          <textarea class="input-flat" name="domanda" rows="6"></textarea>
          <button type="submit" onclick="mostraAggiuntaDomande()" name="azione" value="aggiungi_domanda" class="ml-8" title="Invia domanda">&#x2714</button>
        </form>
<?php } ?>
<?php
  foreach ($domande as $domanda) {
    $id_domanda = $domanda->getAttribute('id');
    $contenuto_d = $domanda->getElementsByTagName('contenuto')[0]->textContent;
    $risposte = $domanda->getElementsByTagName('risposte')[0]->childNodes;
    $id_ut_d = $domanda->getElementsByTagName('idUtente')[0]->textContent;

    $ratings_d = $domanda->getElementsByTagName('ratings')[0]->childNodes;
    $rat_med_d = calcola_rating_medio($ratings_d);

    $info_ut_d = ottieni_info_utente($id_ut_d);

    $rating_pers = [
      'supporto' => 0,
      'utilita' => 0
    ];
    $rating_abilitato = false;

    if ($loggato && $id_ut_d !== $id_ut_corr) {
      $result = xpath($doc_domande, 'domande', "/ns:domande/ns:domanda[@id='$id_domanda']/ns:ratings/ns:rating[@idUtente='$id_ut_corr']");

      if ($result->length !== 0) {
        $rating_pers['supporto'] = $result[0]->getElementsByTagName('supporto')[0]->textContent;
        $rating_pers['utilita'] = $result[0]->getElementsByTagName('utilita')[0]->textContent;
      } else {
        $rating_abilitato = true;
      }
    }

    $rs = [];
    $ru = [];

    for ($i = 0; $i < 3; $i++) {
      $rs[$i] = $i < $rating_pers['supporto'] ? '&#x2605' : '&#x2606';
    }
    for ($i = 0; $i < 5; $i++) {
      $ru[$i] = $i < $rating_pers['utilita'] ? '&#x2605' : '&#x2606';
    }

    $controllo_dom = $id_ut_d === $id_ut_corr;
    $evidenzia_contributo_dom = ($controllo_dom) ? 'colorato-d' : '';
    $form_abilitato_dom = ($controllo_dom) ? 'nascosto' : '';
?>
        <div class="my-32">
          <div class="flex-row <?php echo($evidenzia_contributo_dom); ?>" id="box-dom">
            <div class="fb-60" onclick="mostraRisposte(<?php echo($id_domanda); ?>)">
<?php if ($e_gestore) { ?>
            <form action="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($id_prodotto); ?>" method="post" class="inline">
              <input type="hidden" name="id_domanda" value="<?php echo($id_domanda); ?>" />
              <button type="submit" class="button-icona" name="azione" value="elimina_domanda" title="elimina domanda">&#x01F5D1</button>
            </form>
<?php } ?>
              <p class="giustificato inline"><?php echo($contenuto_d); ?></p>
            </div>
            <div class="fb-20">
              Supporto <?php echo(number_format($rat_med_d['supporto'], 1)); ?>, utilit&agrave; <?php echo(number_format($rat_med_d['utilita'], 1)); ?>
              <p>da <i class="<?php if ($controllo_dom) echo("grassetto"); ?>"><?php echo($info_ut_d['nome'] . ' ' . $info_ut_d['cognome']); ?></i> <?php if ($controllo_dom) echo(" &#x01F464;"); ?> </p>
            </div>
            <div class="fb-20">
<?php if ($loggato && ($e_cliente || $e_gestore)) { ?>
              <div class="riquadro pa-8 mt-8 mr-32 <?php echo($form_abilitato_dom); ?>">
                <p id="dom_supp_<?php echo($id_domanda); ?>">Supporto:
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_supp', 1)"<?php } ?>><?php echo($rs[0]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_supp', 2)"<?php } ?>><?php echo($rs[1]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_supp', 3)"<?php } ?>><?php echo($rs[2]); ?></a>
                </p>
                <form id="dom_rat_<?php echo($id_domanda); ?>" method="post">
                  <input type="hidden" name="id_domanda" value="<?php echo($id_domanda); ?>" />
                  <input type="hidden" name="utente_domanda" value="<?php echo($id_ut_d); ?>" />
                  <input type="hidden" name="dom_supp" value="0" />
                  <input type="hidden" name="dom_util" value="0" />
                  <button type="submit" name="azione" value="rating_domanda" class="button-2 destra mr-4" <?php if (!$rating_abilitato) echo ('disabled'); ?>>Invia</button>
                </form>
                <p id="dom_util_<?php echo($id_domanda); ?>">Utilit&agrave;:
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_util', 1)"<?php } ?>><?php echo($ru[0]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_util', 2)"<?php } ?>><?php echo($ru[1]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_util', 3)"<?php } ?>><?php echo($ru[2]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_util', 4)"<?php } ?>><?php echo($ru[3]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('dom_rat', <?php echo($id_domanda); ?>, 'dom_util', 5)"<?php } ?>><?php echo($ru[4]); ?></a>
                </p>
              </div>
<?php } ?>
            </div>
          </div>
          <div id="risp_<?php echo($id_domanda); ?>" class="nascosto">
<?php
    foreach ($risposte as $risposta) {
      $id_risposta = $risposta->getAttribute('id');
      $contenuto_r = $risposta->getElementsByTagName('contenuto')[0]->textContent;
      $id_ut_r = $risposta->getElementsByTagName('idUtente')[0]->textContent;

      $ratings_r = $risposta->getElementsByTagName('ratings')[0]->childNodes;
      $rat_med_r = calcola_rating_medio($ratings_r);

      $info_ut_r = ottieni_info_utente($id_ut_r);

      $e_risp_gestore = trova_gestore($id_ut_r);
      $r_sel = $e_risp_gestore ? 'checked' : '';

      $rating_pers = [
        'supporto' => 0,
        'utilita' => 0
      ];
      $rating_abilitato = false;

      if ($loggato && $id_ut_r !== $id_ut_corr) {
        $result = xpath($doc_domande, 'domande', "/ns:domande/ns:domanda[@id='$id_domanda']/ns:risposte/ns:risposta[@id='$id_risposta']/ns:ratings/ns:rating[@idUtente='$id_ut_corr']");

        if ($result->length !== 0) {
          $rating_pers['supporto'] = $result[0]->getElementsByTagName('supporto')[0]->textContent;
          $rating_pers['utilita'] = $result[0]->getElementsByTagName('utilita')[0]->textContent;
        } else {
          $rating_abilitato = true;
        }
      }

      $rs = [];
      $ru = [];

      for ($i = 0; $i < 3; $i++) {
        $rs[$i] = $i < $rating_pers['supporto'] ? '&#x2605' : '&#x2606';
      }
      for ($i = 0; $i < 5; $i++) {
        $ru[$i] = $i < $rating_pers['utilita'] ? '&#x2605' : '&#x2606';
      }

      $controllo_risp = $id_ut_r === $id_ut_corr;
      $evidenzia_contributo_risp = ($controllo_risp) ? 'colorato-risp' : '';
      $form_abilitato_risp = ($controllo_risp) ? 'nascosto' : '';
?>
          <div class="flex-row my-16 <?php echo($evidenzia_contributo_risp); ?>">
            <div class="fb-5">
<?php if ($e_gestore) { ?>
            <form action="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($id_prodotto); ?>" method="post" style="display: inline;">
              <input type="hidden" name="id_domanda" value="<?php echo($id_domanda); ?>" />
              <input type="hidden" name="id_risposta" value="<?php echo($id_risposta); ?>" />
              <button type="submit" class="button-icona" name="azione" value="elimina_risposta" title="elimina risposta">&#x01F5D1</button>
            </form>
<?php } ?>
<?php if ($e_gestore || $e_admin) { ?>
              <input type="radio" name="risposta" value="<?php echo($contenuto_r); ?>" form="eleva-<?php echo($id_domanda); ?>" <?php echo($r_sel); ?> />
<?php } ?>
            </div>
            <div class="fb-55">
              <?php echo($contenuto_r); ?>
            </div>
            <div class="fb-20">
              Supporto <?php echo(number_format($rat_med_r['supporto'], 1)); ?>, utilit&agrave; <?php echo(number_format($rat_med_r['utilita'], 1)); ?>
              <p>da <i class="<?php if ($controllo_risp) echo("grassetto"); ?>">
<?php
      echo($info_ut_r['nome'] . ' ' . $info_ut_r['cognome']);
?>
                </i>
<?php
      if ($controllo_risp) echo(" &#x01F464;");
?>
              </p>
            </div>
            <div class="fb-20">
<?php if ($loggato && ($e_cliente || $e_gestore) && !$e_risp_gestore) { ?>
<?php 
  $rating_abilitato = (!$rating_abilitato) ? 'nascosto' : '';
?>
              <div class="riquadro pa-8 mt-8 mr-32 <?php echo($form_abilitato_risp); ?>">
                <p id="risp_supp_<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>">Supporto:
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat', '<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_supp', 1)"<?php } ?>><?php echo($rs[0]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_supp', 2)"<?php } ?>><?php echo($rs[1]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_supp', 3)"<?php } ?>><?php echo($rs[2]); ?></a>
                </p>
                <form id="risp_rat_<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>" method="post">
                  <input type="hidden" name="id_domanda" value="<?php echo($id_domanda); ?>" />
                  <input type="hidden" name="id_risposta" value="<?php echo($id_risposta); ?>" />
                  <input type="hidden" name="utente_risposta" value="<?php echo($id_ut_r); ?>" />
                  <input type="hidden" name="risp_supp" value="0" />
                  <input type="hidden" name="risp_util" value="0" />
                  <button type="submit" name="azione" value="rating_risposta" class="button-2 destra mr-4 <?php echo($rating_abilitato); ?>">Invia</button>
                </form>
                <p id="risp_util_<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>">Utilit&agrave;:
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_util', 1)"<?php } ?>><?php echo($ru[0]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_util', 2)"<?php } ?>><?php echo($ru[1]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_util', 3)"<?php } ?>><?php echo($ru[2]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_util', 4)"<?php } ?>><?php echo($ru[3]); ?></a>
                  <a class="stellina" <?php if ($rating_abilitato) { ?>onclick="setCampo('risp_rat','<?php echo($id_domanda); ?>_<?php echo($id_risposta); ?>', 'risp_util', 5)"<?php } ?>><?php echo($ru[4]); ?></a>
                </p>
              </div>
<?php } else if ($e_risp_gestore) { ?>
              <span class="grassetto"> RISPOSTA DEL GESTORE </span>
<?php } ?>
            </div>
          </div>
          <hr id="separa-risp"/>
<?php
    }
?>
<?php if ($loggato && ($e_cliente || $e_gestore) && !presenza_gestore_risposta($id_domanda)) { ?>
          <button type="submit" class="button mt-16" onclick="mostraAggiuntaRisposta(<?php echo($id_domanda); ?>);">Rispondi</button>
          <form id="risp-dom-<?php echo($id_domanda); ?>" class="nascosto" method="post" action="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($id_prodotto); ?>">
            <input type="hidden" name="id_domanda" value="<?php echo($id_domanda); ?>" />
            <textarea class="input-flat w-50p mt-16" name="risposta" rows="6" placeholder="Inserisci la tua risposta" oninput="gestisciTextarea(<?php echo($id_domanda); ?>);"></textarea>
            <button type="submit" class="button ml-8" name="azione" value="aggiungi_risposta" disabled>Invia</button>
            <p id="msg-risp-<?php echo($id_domanda); ?>" class="grassetto nascosto">&#x26a0; Inserire la risposta!</p>
          </form>
<?php } ?>
<?php if ($e_gestore || $e_admin) { ?>
          <form id="eleva-<?php echo($id_domanda); ?>" method="post" action="<?php echo(RC_SUBDIR); ?>/admin/aggiungi-faq.php">
            <input type="hidden" name="domanda" value="<?php echo($contenuto_d); ?>"></input>
            <input type="hidden" name="id" value="<?php echo($id_prodotto); ?>"></input>
            <button type="submit" class="button mt-16" name="azione" value="precompila">Eleva</button>
          </form>
<?php } ?>
        </div>
      </div>
<?php
  }
?>
      </div>
    </div>
<?php } ?>
  </div>

  <script type="text/javascript" src="<?php echo(RC_SUBDIR); ?>/res/js/prodotto.js"></script>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
