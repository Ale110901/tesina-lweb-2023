<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/offerte.php');
require_once(RC_ROOT . '/lib/utils.php');


if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'aggiungi' && $_POST['domanda'] !== '' && $_POST['risposta'] !== '') {

  aggiungi_offerta($_POST['domanda'], $_POST['risposta']);/* TO DO */
  redirect(307, RC_SUBDIR . '/gestore/offerte.php', false);
}

$root_off = $doc_offerte->documentElement;
$offerte = $root_off->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Aggiungi Offerta &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/aggiungi-offerta.css" />

</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>AGGIUNGI OFFERTA</h2>
    <form class="mb-32" method="post" action="<?php echo(RC_SUBDIR); ?>/gestore/aggiungi-offerta.php">
        <div class="my-32">
            <label for="tipo" class="grassetto mr-8">Tipo di offerta:</label>
            <select class="mb-16" name="tipo">
                <option value="" selected hidden disabled></option>
                <option value="1"> Sconto </option>
                <option value="2"> Bonus </option>
            </select>
            <br />
            <label for="quantita">Quantita:</label>
            <input type="number" class="input-flat mb-16" name="quantita" min="0" step="1"/><br />
            <label for="target">Target:</label>
            <select class="mb-16" name="target">
                <option value="" selected hidden disabled></option>
                <option value="credData"> Crediti spesi da una determinata data </option>
                <option value="reputazione"> Reputazione </option>
                <option value="dataReg"> Clienti presenti da X anni </option>
                <option value="prodSpec"> Prodotto specifico </option>
                <option value="categoria"> Categoria </option>
                <option value="eccMag"> Eccedenza in magazzino </option>
            </select>
        </div>
        <br />
        <button type="submit" class="button" name="azione" value="aggiungi">Aggiungi</button>
    </form>

    <a class="button" onclick="history.back();">Torna indietro</a>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
