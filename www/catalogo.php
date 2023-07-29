<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');
require_once(RC_ROOT . '/lib/carrello.php');

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'aggiungi') {
  $id_prodotto = $_POST['id_prodotto'];
  $quantita = $_POST['quantita'];

  /*
  if (!$loggato) {
    redirect_login();
  }
  */

  aggiungi_carrello($id_prodotto, $quantita);
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Catalogo &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/catalogo.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto">
    <h2>CATALOGO</h2>
    <div id="catalogo">
<?php
  $doc_prod = load_xml('prodotti');
  $prodotti = $doc_prod->documentElement->childNodes;

  for ($i = 0; $i < $prodotti->length; $i++) {
    $prodotto = $prodotti[$i];

    $p_id = $prodotto->getAttribute('id');
    $p_costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
    $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
    $p_marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
    $p_categoria = $prodotto->getElementsByTagName('categoria')[0]->textContent;
    $p_quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;
?>
      <div id="prodotto_<?php echo($p_id); ?>">
        <img src="res/img/prodotti/<?php echo($p_id); ?>.png" alt="shop_<?php echo($p_id); ?>.png" ></img>
        <p><?php echo($p_categoria); ?></p>
        <p><?php echo($p_marca); ?></p>
        <p><?php echo($p_nome); ?></p>
        <p class="prezzo"><?php echo($p_costo); ?> &euro;</p>
        <form class="pt-1em" action="<?php echo(RC_SUBDIR); ?>/catalogo.php#prodotto_<?php echo($p_id); ?>" method="post">
          <input type="hidden" name="id_prodotto" value="<?php echo($p_id); ?>" />
          <input type="number" name="quantita" value="0" min="0" step="1" size="3" max="99" />
          <button type="submit" name="azione" value="aggiungi" class="button ml-8">Aggiungi</button>
        </form>
      </div>
<?php
  }
?>
    </div>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
