<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/offerte.php');
require_once(RC_ROOT . '/lib/xml.php');

const STR_TARGET = [
  'credData' => 'spesi crediti X da data Y',
  'eccMag' => 'quantita di X >= Y',
];

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'niente') {
  // Non fa niente nemmeno qua
}

$offerte = xpath($doc_offerte, 'offerte', '/ns:offerte/ns:offerta');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione offerte &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>

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
  $of_target_str = STR_TARGET[$of_target];

  switch ($of_target) {
    case 'credData':
      $of_key_x = 'creditiSpesi';
      $of_key_y = 'dataInizio';
      break;
    case 'eccMag':
      $of_key_x = 'idProdotto';
      $of_key_y = 'quantitaMin';
      break;
  }

  $of_val_x = $offerta->getElementsByTagName($of_key_x)[0]->textContent;
  $of_val_y = $offerta->getElementsByTagName($of_key_y)[0]->textContent;
?>
      <form class="tr" id="offerta-<?php echo($of_id); ?>" method="post" action="<?php echo(RC_SUBDIR); ?>/accesso_negato.php">
        <input type="hidden" name="id" value="<?php echo($of_id); ?>"></input>
        <div class="td"><span><?php echo($of_tipo); ?></span></div>
        <div class="td"><span><?php echo($of_quantita); ?></span></div>
        <div class="td"><span><?php echo($of_target_str); ?></span></div>
        <div class="td"><span><?php echo($of_val_x); ?></span></div>
        <div class="td"><span><?php echo($of_val_y); ?></span></div>
        <div class="td">
          <button type="submit" name="azione" value="accetta" class="button-icona">&#x2705</button>
        </div>
      </form>
<?php
}
?>
    </div>
    <a class="button" onclick="history.back();">Torna indietro</a>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
