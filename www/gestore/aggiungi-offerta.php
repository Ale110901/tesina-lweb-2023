<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/categorie.php');
require_once(RC_ROOT . '/lib/offerte.php');
require_once(RC_ROOT . '/lib/prodotti.php');
require_once(RC_ROOT . '/lib/utils.php');

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'aggiungi') {
  aggiungi_offerta($_POST);
  redirect(307, RC_SUBDIR . '/gestore/offerte.php', false);
}

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
    <h2>AGGIUNGI OFFERTA</h2>
    <form id="offerta" class="mb-32" method="post" action="<?php echo(RC_SUBDIR); ?>/gestore/aggiungi-offerta.php">
        <div class="my-32">
          <div id="input-tipo" class="my-16">
            <label for="tipo" class="grassetto mr-8">Tipo di offerta:</label>
            <select name="tipo" onchange="aggiornaTipo();">
              <option value="sconto">Sconto</option>
              <option value="bonus">Bonus</option>
            </select>
            <br />
          </div>

          <div id="input-percentuale" class="my-16">
            <label for="percentuale" class="grassetto mr-8">Percentuale:</label>
            <input type="number" class="input-flat" name="percentuale" min="0" step="1" />%
            <br />
          </div>

          <div id="input-numCrediti" class="my-16 nascosto">
            <label for="numCrediti" class="grassetto mr-8">Numero crediti:</label>
            <input type="number" class="input-flat" name="numCrediti" min="0" step="1"/><br />
          </div>

          <div id="input-target" class="my-16">
            <label for="target" class="grassetto mr-8">Target:</label>
            <select name="target" onchange="aggiornaTarget();">
              <option value="credData">Crediti spesi da una determinata data</option>
              <option value="reputazione">Reputazione minima</option>
              <option value="dataReg">Clienti presenti da X anni</option>
              <option value="prodSpec">Prodotto specifico</option>
              <option value="categoria">Categoria</option>
              <option value="eccMag">Eccedenza in magazzino</option>
            </select>
          </div>

          <div id="input-creditiSpesi" class="my-16">
            <label for="creditiSpesi" class="grassetto mr-8">Crediti spesi:</label>
            <input type="number" class="input-flat" name="creditiSpesi" min="0" step="1" />
            <br />
          </div>

          <div id="input-dataInizio" class="my-16">
            <label for="dataInizio" class="grassetto mr-8">Data di inizio:</label>
            <input type="date" class="input-flat" name="dataInizio" />
            <br />
          </div>

          <div id="input-reputazione" class="my-16 nascosto">
            <label for="reputazione" class="grassetto mr-8">Reputazione minima:</label>
            <input type="number" class="input-flat" name="reputazione" min="0" step="1" />
            <br />
          </div>

          <div id="input-anni" class="my-16 nascosto">
            <label for="anni" class="grassetto mr-8">Numero di anni:</label>
            <input type="number" class="input-flat" name="anni" min="0" step="1" />
            <br />
          </div>

          <div id="input-idProdotto" class="my-16 nascosto">
            <label for="idProdotto" class="grassetto mr-8">Prodotto:</label>
            <select name="idProdotto">
<?php
foreach ($prodotti as $prodotto) {
  $p_id = $prodotto->getAttribute('id');
  $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
?>
              <option value="<?php echo ($p_id); ?>"><?php echo ($p_nome); ?></option>
<?php
}
?>
            </select>
            <br />
          </div>

          <div id="input-idCategoria" class="my-16 nascosto">
            <label for="idCategoria" class="grassetto mr-8">Categoria:</label>
            <select name="idCategoria">
<?php
foreach ($categorie as $categoria) {
  $c_id = $categoria->getAttribute('id');
  $c_nome = $categoria->getElementsByTagName('nome')[0]->textContent;
?>
              <option value="<?php echo ($c_id); ?>"><?php echo ($c_nome); ?></option>
<?php
}
?>
            </select>
            <br />
          </div>

          <div id="input-quantitaMin" class="my-16 nascosto">
            <label for="quantitaMin" class="grassetto mr-8">Soglia:</label>
            <input type="number" class="input-flat mb-16" name="quantitaMin" min="0" step="1" />
            <br />
          </div>
        </div>
        <br />
        <button type="submit" class="button" name="azione" value="aggiungi">Aggiungi</button>
    </form>

    <a class="button" onclick="history.back();">Torna indietro</a>
  </div>

  <script type="text/javascript" src="<?php echo(RC_SUBDIR); ?>/res/js/aggiungi-offerta.js">
  </script>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
