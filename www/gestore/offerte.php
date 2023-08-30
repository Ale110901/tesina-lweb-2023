<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/categorie.php');
require_once($rc_root . '/lib/prodotti.php');
require_once($rc_root . '/lib/offerte.php');
require_once($rc_root . '/lib/xml.php');

$elimina = isset($_POST['azione']) && $_POST['azione'] === 'elimina';

if ($elimina) {
  elimina_offerta($_POST['id']);
}

$offerte = xpath($doc_offerte, 'offerte', '/ns:offerte/ns:offerta');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione offerte &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/offerte-gestore.css" />

</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>

  <div id="contenuto">
    <h2>GESTIONE OFFERTE</h2>
    <div class="table mt-32 mb-32 mx-auto centrato">
      <div id="intestazione" class="thg grassetto">
        <div class="td">Tipo</div>
        <div class="td">Quantita</div>
        <div class="td">Target</div>
        <div class="td">X</div>
        <div class="td">Y</div>
        <div class="td">Modifica</div>
        <div class="td">Elimina</div>
      </div>
<?php
foreach ($offerte as $offerta) {
  $of_id = $offerta->getAttribute('id');

  $of_tipo = $offerta->getElementsByTagName('tipo')[0]->textContent;
  if ($of_tipo === 'sconto') {
    $tmp = $offerta->getElementsByTagName('percentuale')[0]->textContent;
    $of_quantita = $tmp . '%';
  } else if ($of_tipo === 'bonus') {
    $tmp = $offerta->getElementsByTagName('numCrediti')[0]->textContent;
    $of_quantita = '+' . $tmp;
  }

  $of_target = $offerta->getElementsByTagName('target')[0]->textContent;

  switch ($of_target) {
    case 'credData':
      $of_desc = 'spesi crediti X da data Y';
      $of_key_x = 'creditiSpesi';
      $of_key_y = 'dataInizio';
      break;

    case 'reputazione':
      $of_desc = "reputazione >= X";
      $of_key_x = "reputazione";
      $of_key_y = null;
      break;

    case 'dataReg':
      $of_desc = "cliente da X anni";
      $of_key_x = "anni";
      $of_key_y = null;
      break;

    case 'prodSpec':
      $of_desc = "prodotto == X";
      $of_key_x = "idProdotto";
      $of_key_y = null;
      break;

    case 'categoria':
      $of_desc = "categoria == X";
      $of_key_x = "idCategoria";
      $of_key_y = null;
      break;

    case 'eccMag':
      $of_desc = 'quantita di prodotto X >= Y';
      $of_key_x = 'idProdotto';
      $of_key_y = 'quantitaMin';
      break;
  }

  $of_val_x = $offerta->getElementsByTagName($of_key_x)[0]->textContent;

  if ($of_key_y === null) {
    $of_val_y = '&ndash;';
  } else {
    $of_val_y = $offerta->getElementsByTagName($of_key_y)[0]->textContent;
  }

  if ($of_key_x === 'idCategoria') {
    $of_val_x = ottieni_categoria($of_val_x);
  } else if ($of_key_x === 'idProdotto') {
    $of_val_x = ottieni_info_prodotto($of_val_x)['nome'];
  }
?>
      <div class="tr">
        <div class="td"><span><?php echo($of_tipo); ?></span></div>
        <div class="td"><span><?php echo($of_quantita); ?></span></div>
        <div class="td"><span><?php echo($of_desc); ?></span></div>
        <div class="td"><span><?php echo($of_val_x); ?></span></div>
        <div class="td"><span><?php echo($of_val_y); ?></span></div>
        <div class="td"><a class="button-icona" href="<?php echo($rc_subdir); ?>/gestore/offerta.php?id=<?php echo($of_id); ?>">&#x01F4DD</a></div>
        <form class="td centrato" action="<?php echo($rc_subdir); ?>/gestore/offerte.php" method="post">
          <input type="hidden" name="id" value="<?php echo($of_id); ?>" />
          <button type="submit" class="button-icona" name="azione" value="elimina">&#x01F5D1</button>
        </form>
      </div>
<?php
}
?>
    </div>

    <p class="centrato l-h3em">
        <a class="button" href="<?php echo($rc_subdir);?>/gestore/offerta.php">Aggiungi offerta</a><br />
        <a class="button" href="<?php echo($rc_subdir); ?>/gestore/index.php">Torna indietro</a>
    </p>

  </div>

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
