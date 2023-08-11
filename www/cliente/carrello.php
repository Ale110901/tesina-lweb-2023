<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/carrello.php');
require_once(RC_ROOT . '/lib/offerte.php');

if (isset($_SESSION['agg_carr_id_prod'])) {
  aggiungi_carrello($_SESSION['agg_carr_id_prod'], $_SESSION['agg_carr_qta']);
  unset($_SESSION['agg_carr_id_prod']);
  unset($_SESSION['agg_carr_qta']);
}

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'modifica') {
  $id_prodotto = $_POST['id_prodotto'];
  $quantita = $_POST['quantita'];

  modifica_carrello($id_prodotto, $quantita);
} else if ($_POST['azione'] === 'rimuovi') {
  $id_prodotto = $_POST['id_prodotto'];

  modifica_carrello($id_prodotto, 0);
}

$id_utente = $_SESSION['id_utente'];

$doc_utenti = load_xml('utenti');
$prodotti = xpath($doc_utenti, 'utenti',
  '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello/ns:prodotto'
);

$credito_utente = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:credito')[0]->textContent;

$doc_prodotti = load_xml('prodotti');
$doc_offerte = load_xml('offerte');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Carrello &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/carrello.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2>CARRELLO</h2>
    <ul>
<?php
$totale = 0;
$bonus_totali = 0;

foreach ($prodotti as $prodotto) {
  $id_prod = $prodotto->getAttribute('id');
  $qta_prod = $prodotto->getAttribute('quantita');

  $prodotto_offerta = xpath($doc_prodotti, 'prodotti',
  '/ns:prodotti/ns:prodotto[@id=' . $id_prod . ']'
  )[0];

  $offerte = offerte_applicabili($doc_offerte, $prodotto_offerta);
  $sconto = calcola_sconto($offerte);
  $bonus = calcola_bonus($offerte);

  if ($bonus > 0) {
    $bonus_totali += $bonus;
  }

  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id='$id_prod']");
  $prodotto = $result[0];

  $nome_prod = $prodotto->getElementsByTagName('nome')[0]->textContent;
  $prezzo_prod_or = $prodotto->getElementsByTagName('costo')[0]->textContent;

  $prezzo_prod_sc = 0;

  if ($sconto > 0) {
    $prezzo_prod_sc = $prezzo_prod_or * (1 - $sconto);
    $totale += ($prezzo_prod_sc * $qta_prod);
  } else {
    $totale += ($prezzo_prod_or * $qta_prod);
  }

?>
      <li>
        <p>
        <?php echo($nome_prod); ?>,
<?php if ($prezzo_prod_sc > 0) { ?>
          <span id="valore-barrato" class="mr-16">
<?php  echo(number_format($prezzo_prod_or, 2)); ?> &euro;
          </span>
<?php echo(number_format($prezzo_prod_sc, 2)); ?> &euro;
<?php } else { echo(number_format($prezzo_prod_or, 2)); ?> &euro;
<?php } ?>
        </p>
        <form class="mt-8" action="<?php echo(RC_SUBDIR); ?>/cliente/carrello.php" method="post">
          <input type="hidden" name="id_prodotto" value="<?php echo ($id_prod); ?>" />
          <input type="number" name="quantita" value="<?php echo($qta_prod); ?>" min="0" step="1" size="4" max="99" />
          <button type="submit" name="azione" class="ml-8 button-icona" value="modifica" title="Modifica quantita">&#x01F4DD</button>
          <button type="submit" name="azione" class="ml-8 button-icona" value="rimuovi"  title="Rimuovi elemento">&#x01F5D1</button>
<?php if ($sconto > 0) { ?>
          <label class="ml-32 bold">&#x1F4B2; Sconto applicato: <?php echo($sconto*100); ?>&percnt; </label>
<?php } ?>
<?php if ($bonus > 0.0) { ?>
          <label class="ml-32 bold">&#x1F4B2;Bonus: &plus;<?php echo($bonus); ?> crediti!</label>
<?php } ?>
          
        </form>
        <hr class="my-8" />
      </li>
<?php
}

?>
    </ul>

    <p>Totale: <?php echo(number_format($totale, 2)); ?>&euro;</p> <br />

    <div class="mt-32">
      <a class="button <?php if ($totale > 0) echo('sinistra'); ?>" href="<?php echo(RC_SUBDIR); ?>/catalogo.php">Indietro</a>
<?php if ($totale > 0) { ?>
<?php   if ($credito_utente >= $totale) { ?>
      <form action="<?php echo(RC_SUBDIR); ?>/cliente/ordine.php" method="post">
        <button type="submit" class="button" id="button-acquista" name="azione" value="modifica-indirizzo">Termina acquisto</button>
        <input type="hidden" name="bonus-totali" value="<?php echo($bonus_totali); ?>"/>
        <input type="hidden" name="totale" value="<?php echo($totale); ?>"/>
      </form>
<?php   } else { ?>
      <a class="button" id="button-acquista" onclick="creditoInsufficiente();">Termina acquisto</a>
      <div class="mt-32 nascosto" id="credito-insufficiente">
        <p>Credito insufficiente!</p>
        <form action="<?php echo(RC_SUBDIR); ?>/cliente/ricarica.php" method="post">
          <input type="hidden" name="azione" value="carrello" />
          <button class="button mt-8"> <span>Ricarica!</span> </a>
          </form>
        </div>
<?php   } ?>
<?php } ?>
    </div>

  </div>

  <script type="text/javascript" >
    function creditoInsufficiente() {
      document.getElementById('credito-insufficiente').classList.remove('nascosto');
    }
  </script>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
