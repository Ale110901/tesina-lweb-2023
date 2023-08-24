<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

require_once($rc_root . '/lib/start.php');
require_once($rc_root . '/lib/carrello.php');
require_once($rc_root . '/lib/offerte.php');
require_once($rc_root . '/lib/prodotti.php');
require_once($rc_root . '/lib/utenti.php');

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

$prodotti = xpath($doc_utenti, 'utenti',
  '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello/ns:prodotto'
);

$credito_utente = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:credito')[0]->textContent;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Carrello &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/carrello.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2>CARRELLO</h2>
    <ul>
<?php
$totale = 0;
$bonus_totali = 0;

foreach ($prodotti as $prod_carrello) {
  $id_prod = $prod_carrello->getAttribute('id');
  $qta_prod = $prod_carrello->getAttribute('quantita');

  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id='$id_prod']");
  $prod_info = $result[0];

  $nome_prod = $prod_info->getElementsByTagName('nome')[0]->textContent;
  $prezzo_prod_or = $prod_info->getElementsByTagName('costo')[0]->textContent;
  $qta_mag = $prod_info->getElementsByTagName('quantita')[0]->textContent;

  $offerte = offerte_applicabili($prod_info);


  $sconto = calcola_sconto($offerte);
  $prezzo_prod_sc = $prezzo_prod_or * (1 - $sconto);
  $totale += ($prezzo_prod_sc * $qta_prod);


  $bonus = calcola_bonus($offerte);
  $bonus_totali += $bonus;
?>
      <li>
        <p>
        <?php echo($nome_prod); ?>,
<?php if ($prezzo_prod_or - $prezzo_prod_sc >= 0.01) { ?>
          <span id="valore-barrato" class="mr-16">
            <?php echo(number_format($prezzo_prod_or, 2)); ?> &euro;
          </span>
          <?php echo(number_format($prezzo_prod_sc, 2)); ?> &euro;
<?php } else { ?>
          <?php echo(number_format($prezzo_prod_or, 2)); ?> &euro;
<?php } ?>
        </p>
        <form class="mt-8" action="<?php echo($rc_subdir); ?>/cliente/carrello.php" method="post">
          <input type="hidden" name="id_prodotto" value="<?php echo ($id_prod); ?>" />
          <input type="number" name="quantita" value="<?php echo($qta_prod); ?>" min="0" step="1" size="4" max="<?php echo($qta_mag); ?>" />
          <button type="submit" name="azione" class="ml-8 button-icona" value="modifica" title="Modifica quantita">&#x01F4DD</button>
          <button type="submit" name="azione" class="ml-8 button-icona" value="rimuovi"  title="Rimuovi elemento">&#x01F5D1</button>
<?php if ($sconto > 0) { ?>
          <label class="ml-32 grassetto">&#x1F4B2; Sconto applicato: <?php echo ($sconto * 100); ?>&percnt; </label>
<?php } ?>
<?php if ($bonus > 0.0) { ?>
          <label class="ml-32 grassetto">&#x1F4B2;Bonus: &plus;<?php echo ($bonus); ?> crediti</label>
<?php } ?>

        </form>
        <hr class="my-8" />
      </li>
<?php
}

?>
    </ul>

    <p>Totale: <?php echo(number_format($totale, 2)); ?>&euro;</p><br />
    <p>Bonus: &plus;<?php echo($bonus_totali); ?> crediti</p><br />

    <div class="mt-32">
      <a class="button <?php if ($totale > 0) echo('sinistra'); ?>" href="<?php echo($rc_subdir); ?>/catalogo.php">Indietro</a>
<?php if ($totale > 0) { ?>
<?php   if ($credito_utente >= $totale) { ?>
      <form action="<?php echo($rc_subdir); ?>/cliente/ordine.php" method="post">
        <button type="submit" class="button" id="button-acquista" name="azione" value="modifica-indirizzo">Termina acquisto</button>
        <input type="hidden" name="bonus" value="<?php echo($bonus_totali); ?>"/>
        <input type="hidden" name="totale" value="<?php echo($totale); ?>"/>
      </form>
<?php   } else { ?>
      <a class="button" id="button-acquista" onclick="creditoInsufficiente();">Termina acquisto</a>
      <div class="mt-32 nascosto" id="credito-insufficiente">
        <p>Credito insufficiente!</p>
        <form action="<?php echo($rc_subdir); ?>/cliente/ricarica.php" method="post">
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

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
