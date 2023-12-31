<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/categorie.php');
require_once($rc_root . '/lib/prodotti.php');
require_once($rc_root . '/lib/xml.php');

$elimina = isset($_POST['azione']) && $_POST['azione'] === 'elimina';

if ($elimina) {
  elimina_prodotto($_POST['id']);
}

$root = $doc_prodotti->documentElement;
$prodotti = $root->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione prodotti &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/prodotti-gestore.css" />

</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2 class="mb-32">GESTIONE PRODOTTI</h2>
    <div class="table mt-32 mx-auto mb-32 text-left">
      <div class="thg grassetto">
        <div class="td"></div>
        <div class="td">Marca</div>
        <div class="td">Nome</div>
        <div class="td">Costo</div>
        <div class="td">Categoria</div>
        <div class="td">Quantita</div>
        <div class="td">Visualizza</div>
        <div class="td">Modifica</div>
        <div class="td">Elimina</div>
      </div>
<?php
$prodotti = domlist_to_array($prodotti);
$prodotti = ordina_per_categoria($prodotti, false);

foreach ($prodotti as $prodotto) {
  $p_id = $prodotto->getAttribute('id');
  $p_marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
  $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
  $p_costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
  $p_cat_id = $prodotto->getElementsByTagName('categoria')[0]->textContent;
  $p_quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;

  $p_categoria = ottieni_categoria($p_cat_id);
  $p_icona = $p_quantita == 0 ? '&#x26a0;' : '';
?>
      <div class="tr">
        <p class="td grassetto"><?php echo($p_icona); ?></p>
        <div class="td"><?php echo($p_marca); ?></div>
        <div class="td"><?php echo($p_nome); ?></div>
        <div class="td"><?php echo(number_format($p_costo, 2)); ?> &euro;</div>
        <div class="td"><?php echo($p_categoria); ?></div>
        <div class="td centrato"><?php echo($p_quantita); ?></div>
        <div class="td centrato"><a href="<?php echo($rc_subdir); ?>/prodotto.php?id=<?php echo($p_id); ?>">&#x1F441</a></div>
        <div class="td centrato"><a href="<?php echo($rc_subdir); ?>/gestore/prodotto.php?id=<?php echo($p_id); ?>">&#x01F4DD</a></div>
        <form class="td centrato" action="<?php echo($rc_subdir); ?>/gestore/prodotti.php" method="post">
          <input type="hidden" name="id" value="<?php echo($p_id); ?>" />
          <button type="submit" class="button-icona" name="azione" value="elimina">&#x01F5D1</button>
        </form>
      </div>
<?php
}
?>
    </div>
      <p class="l-h3em">
        <a class="button b-32" href="<?php echo($rc_subdir);?>/gestore/prodotto.php">Aggiungi prodotto</a><br />
        <a class="button" href="<?php echo($rc_subdir);?>/gestore/index.php">Torna indietro</a>
      </p>

  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
