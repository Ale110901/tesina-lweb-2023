<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/categorie.php');
require_once(RC_ROOT . '/lib/offerte.php');
require_once(RC_ROOT . '/lib/prodotti.php');
require_once(RC_ROOT . '/lib/utils.php');

$aggiungi = isset($_POST['azione']) && $_POST['azione'] === 'aggiungi';
$modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

if ($aggiungi) {
  aggiungi_offerta(0, $_POST);
  redirect(307, RC_SUBDIR . '/gestore/offerte.php', false);
}

$o_info = [
  'tipo' => 'sconto',
  'percentuale' => '0',
  'numCrediti' => '0',
  'target' => 'credData',
  'creditiSpesi' => '0',
  'dataInizio' => '1970-01-01',
  'reputazione' => '0',
  'anni' => '0',
  'idProdotto' => '1',
  'idCategoria' => '1',
  'quantitaMin' => '0',
];

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);

if ($id_valido) {
  $o_id = $_GET['id'];

  $o_info_t = ottieni_info_offerta($o_id);
  if (!$o_info_t) {
    $id_valido = false;
  } else if ($modifica) {
    modifica_offerta($o_id, $_POST);
    redirect(307, RC_SUBDIR . '/gestore/offerte.php', false);
  } else {
    $o_info = $o_info_t;
  }
}

$da_modificare = $id_valido;

$sel_creditiSpesi = $o_info['target'] === 'credData';
$sel_dataInizio = $o_info['target'] === 'credData';
$sel_reputazione = $o_info['target'] === 'reputazione';
$sel_anni = $o_info['target'] === 'dataReg';
$sel_idProdotto = $o_info['target'] === 'prodSpec' || $o_info['target'] === 'eccMag';
$sel_idCategoria = $o_info['target'] === 'categoria';
$sel_quantitaMin = $o_info['target'] === 'eccMag';

$categorie = $doc_categorie->documentElement->childNodes;
$prodotti =  $doc_prodotti->documentElement->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Aggiungi offerta &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/aggiungi-offerta.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>GESTIONE OFFERTA</h2>
<?php
$a_id = $da_modificare ? '?id=' . $o_id : '';
?>
    <form id="offerta" class="mb-32" method="post" action="<?php echo(RC_SUBDIR); ?>/gestore/offerta.php<?php echo($a_id); ?>">
        <div class="my-32">
          <div id="input-tipo" class="my-16">
            <label for="tipo" class="grassetto mr-8">Tipo di offerta:</label>
            <select name="tipo" onchange="aggiornaTipo();">
              <option value="sconto" <?php if ($o_info['tipo'] === 'sconto') echo('selected'); ?>>Sconto</option>
              <option value="bonus" <?php if ($o_info['tipo'] === 'bonus') echo('selected'); ?>>Bonus</option>
            </select>
            <br />
          </div>

          <div id="input-percentuale" class="my-16 <?php if ($o_info['tipo'] !== 'sconto') echo('nascosto'); ?>">
            <label for="percentuale" class="grassetto mr-8">Percentuale:</label>
            <input type="number" class="input-flat" name="percentuale" min="0" step="1" value="<?php echo($o_info['percentuale']); ?>" />%
            <br />
          </div>

          <div id="input-numCrediti" class="my-16 <?php if ($o_info['tipo'] !== 'bonus') echo('nascosto'); ?>">
            <label for="numCrediti" class="grassetto mr-8">Numero crediti:</label>
            <input type="number" class="input-flat" name="numCrediti" min="0" step="1" value="<?php echo($o_info['numCrediti']); ?>" /><br />
          </div>

          <div id="input-target" class="my-16">
            <label for="target" class="grassetto mr-8">Target:</label>
            <select name="target" onchange="aggiornaTarget();">
              <option value="credData" <?php if ($o_info['target'] === 'credData') echo('selected'); ?>>Crediti spesi da una determinata data</option>
              <option value="reputazione" <?php if ($o_info['target'] === 'reputazione') echo('selected'); ?>>Reputazione minima</option>
              <option value="dataReg" <?php if ($o_info['target'] === 'dataReg') echo('selected'); ?>>Clienti presenti da X anni</option>
              <option value="prodSpec" <?php if ($o_info['target'] === 'prodSpec') echo('selected'); ?>>Prodotto specifico</option>
              <option value="categoria" <?php if ($o_info['target'] === 'categoria') echo('selected'); ?>>Categoria</option>
              <option value="eccMag" <?php if ($o_info['target'] === 'eccMag') echo('selected'); ?>>Eccedenza in magazzino</option>
            </select>
          </div>

          <div id="input-creditiSpesi" class="my-16 <?php if (!$sel_creditiSpesi) echo('nascosto'); ?>">
            <label for="creditiSpesi" class="grassetto mr-8">Crediti spesi:</label>
            <input type="number" class="input-flat" name="creditiSpesi" min="0" step="1" value="<?php echo($o_info['creditiSpesi']); ?>" />
            <br />
          </div>

          <div id="input-dataInizio" class="my-16 <?php if (!$sel_dataInizio) echo('nascosto'); ?>">
            <label for="dataInizio" class="grassetto mr-8">Data di inizio:</label>
            <input type="date" class="input-flat" name="dataInizio" value="<?php echo($o_info['dataInizio']); ?>" />
            <br />
          </div>

          <div id="input-reputazione" class="my-16 <?php if (!$sel_reputazione) echo('nascosto'); ?>">
            <label for="reputazione" class="grassetto mr-8">Reputazione minima:</label>
            <input type="number" class="input-flat" name="reputazione" min="0" step="1" value="<?php echo($o_info['reputazione']); ?>" />
            <br />
          </div>

          <div id="input-anni" class="my-16 <?php if (!$sel_anni) echo('nascosto'); ?>">
            <label for="anni" class="grassetto mr-8">Numero di anni:</label>
            <input type="number" class="input-flat" name="anni" min="0" step="1" value="<?php echo($o_info['anni']); ?>" />
            <br />
          </div>

          <div id="input-idProdotto" class="my-16 <?php if (!$sel_idProdotto) echo('nascosto'); ?>">
            <label for="idProdotto" class="grassetto mr-8">Prodotto:</label>
            <select name="idProdotto">
<?php
foreach ($prodotti as $prodotto) {
  $p_id = $prodotto->getAttribute('id');
  $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
  $p_sel = $prodotto->getAttribute('id') === $o_info['idProdotto'] ? 'selected' : '';
?>
              <option value="<?php echo ($p_id); ?>" <?php echo ($p_sel); ?>><?php echo ($p_nome); ?></option>
<?php
}
?>
            </select>
            <br />
          </div>

          <div id="input-idCategoria" class="my-16 <?php if (!$sel_idCategoria) echo('nascosto'); ?>">
            <label for="idCategoria" class="grassetto mr-8">Categoria:</label>
            <select name="idCategoria">
<?php
foreach ($categorie as $categoria) {
  $c_id = $categoria->getAttribute('id');
  $c_nome = $categoria->getElementsByTagName('nome')[0]->textContent;
  $c_sel = $categoria->getAttribute('id') === $o_info['idCategoria'] ? 'selected' : '';
?>
              <option value="<?php echo ($c_id); ?>" <?php echo ($c_sel); ?>><?php echo ($c_nome); ?></option>
<?php
}
?>
            </select>
            <br />
          </div>

          <div id="input-quantitaMin" class="my-16 <?php if (!$sel_quantitaMin) echo('nascosto'); ?>">
            <label for="quantitaMin" class="grassetto mr-8">Soglia:</label>
            <input type="number" class="input-flat mb-16" name="quantitaMin" min="0" step="1" value="<?php echo($o_info['quantitaMin']); ?>" />
            <br />
          </div>
        </div>
        <br />
<?php if ($da_modificare) { ?>
        <input type="hidden" name="id" value="<?php echo ($o_id); ?>" />
        <button type="submit" name="azione" value="modifica" class="button" title="Modifica">Modifica</button>
<?php } else { ?>
        <button type="submit" name="azione" value="aggiungi" class="button" title="Aggiungi">Aggiungi</button>
<?php } ?>
    </form>

    <a class="button" onclick="history.back();">Torna indietro</a>
  </div>

  <script type="text/javascript" src="<?php echo(RC_SUBDIR); ?>/res/js/offerta.js">
  </script>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
