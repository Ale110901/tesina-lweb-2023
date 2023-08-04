<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');
require_once(RC_ROOT . '/lib/offerte.php');

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);
if ($id_valido) {
  $id_prodotto = $_GET['id'];

  $doc_prodotti = load_xml('prodotti');
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

    $doc_offerte = load_xml('offerte');
    $off_app = offerte_applicabili($doc_offerte, $prodotto);
    $sconto = calcola_sconto($off_app);
    $bonus = calcola_bonus($off_app);
    $costo_finale = round($costo_orig * (1 - $sconto), 2);
  }
}
?>

<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title><?php echo($nome); ?> &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/prodotto.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>

  <div id="contenuto">
<?php if (!$id_valido) { ?>
    <p>Prodotto non trovato.</p>
<?php } else { ?>
    <div id="pag-prodotto">
      <div id="prod-top">
        <div id="prod-top-sx">
          <img src="<?php echo(RC_SUBDIR); ?>/res/img/prodotti/<?php echo($id_prodotto); ?>.png" alt="Immagine prodotto <?php echo($id_prodotto); ?>" ></img>
        </div>
        <div id="prod-top-mid">
          <p id="marca"><?php echo($marca); ?></p>
          <p id="nome"><?php echo($nome); ?></p>
          <p id="descrizione"><?php echo($descrizione); ?></p>
        </div>
        <div id="prod-top-dx">
          <div id="prod-top-dx-info">
            <div id="col-1">
<?php if ($quantita > 0) { ?>
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
                <input type="number" name="quantita" class="input-box" value="0" min="0" step="1" size="3" max="<?php echo($quantita); ?>" />
              </div>
              <div id="btn-aggiungi">
                <button type="submit" name="azione" value="aggiungi" class="button ml-8">Aggiungi al carrello</button>
              </div>
            </form>
        </div>
      </div>
      <hr class="my-32" />
      <div>
        <h3>Recensioni</h3>
        <p>E' buono.</p>
      </div>
      <hr class="my-32" />
      <div>
        <h3>Domande e risposte</h3>
        <h4>Ciao?</h4>
        <p>Ciao.</p>
      </div>
    </div>
<?php } ?>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
