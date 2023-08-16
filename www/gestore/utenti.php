<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');

$doc_utenti = load_xml('utenti');
$root = $doc_utenti->documentElement;
$utenti = $root->childNodes;
?>

<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione utenti &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />

</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto">
    <h2 class="mb-32">GESTIONE UTENTI</h2>
    <div class="table mt-32 mx-auto mb-32">
      <div class="thg grassetto">
        <div class="td">Marca</div>
        <div class="td">Nome</div>
        <div class="td">Costo</div>
        <div class="td">Categoria</div>
        <div class="td">Quantita</div>
        <div class="td">Visualizza</div>
        <div class="td">Modifica</div>
      </div>
<?php
foreach ($utenti as $utente) {
  $p_id = $prodotto->getAttribute('id');
  $p_marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
  $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
  $p_costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
  $p_cat_id = $prodotto->getElementsByTagName('categoria')[0]->textContent;
  $p_quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;

  $p_categoria = ottieni_categoria($p_cat_id);
?>
      <div class="tr">
        <div class="td"><?php echo($p_marca); ?></div>
        <div class="td"><?php echo($p_nome); ?></div>
        <div class="td"><?php echo(number_format($p_costo, 2)); ?> &euro;</div>
        <div class="td"><?php echo($p_categoria); ?></div>
        <div class="td"><?php echo($p_quantita); ?></div>
        <div class="td centrato" ><a href="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($p_id); ?>">&#x1F441</a></div>
        <div class="td centrato"><a href="<?php echo(RC_SUBDIR); ?>/gestore/prodotto.php?id=<?php echo($p_id); ?>">&#x01F4DD</a></div>
      </div>
<?php
}
?>
    </div>
    <a class="button" href="<?php echo(RC_SUBDIR);?>/gestore/index.php">Torna indietro</a>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>