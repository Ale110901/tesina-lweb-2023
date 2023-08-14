<?php
require_once('../config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);

$p_nome = '';
$p_marca = '';
$p_descrizione = '';
$p_costo = 0.00;
$p_categoria = '';
$p_quantita = '';

if ($id_valido) {
  $p_id = $_GET['id'];

  $doc_prodotti = load_xml('prodotti');
  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id=$p_id]");
  if ($result->length !== 1) {
    $id_valido = false;
  } else {
    $prodotto = $result[0];

    $modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

    if ($modifica) {
      $prodotto->getElementsByTagName('nome')[0]->textContent = $_POST['nome'];
      $prodotto->getElementsByTagName('marca')[0]->textContent = $_POST['marca'];
      $prodotto->getElementsByTagName('descrizione')[0]->textContent = $_POST['descrizione'];
      $prodotto->getElementsByTagName('costo')[0]->textContent = $_POST['costo'];
      $prodotto->getElementsByTagName('categoria')[0]->textContent = $_POST['categoria'];
      $prodotto->getElementsByTagName('quantita')[0]->textContent = $_POST['quantita'];

      save_xml($doc_prodotti, 'prodotti');
    }

    $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
    $p_marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
    $p_descrizione = $prodotto->getElementsByTagName('descrizione')[0]->textContent;
    $p_costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
    $p_categoria = $prodotto->getElementsByTagName('categoria')[0]->textContent;
    $p_quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;
  }
}

$da_modificare = $id_valido;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione prodotto &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2>GESTIONE PRODOTTO</h2>
    <form action="<?php echo(RC_SUBDIR); ?>/gestore/prodotto.php?id=<?php echo($p_id); ?>" method="post">
      <table class="py-1em giustificato">
        <tr>
          <td><b>Immagine:</b></td>
          <td class="w-100p">Da fare l'upload</td>
        </tr>
        <tr>
          <td><b>Marca:</b></td>
          <td class="w-100p"><input type="text" class="input-flat w-100p" name="marca" value="<?php echo ($p_marca); ?>" /></td>
        </tr>
        <tr>
          <td><b>Nome:</b></td>
          <td class="w-100p"><input type="text" class="input-flat w-100p" name="nome" value="<?php echo ($p_nome); ?>" /></td>
        </tr>
        <tr>
          <td><b>Descrizione:</b></td>
          <td class="w-100p"><textarea class="input-flat w-100p" name="descrizione" rows="6" placeholder="Inserisci la descrizione qui"><?php echo($p_descrizione); ?></textarea></td>
        </tr>
        <tr>
          <td><b>Costo:</b></td>
          <td class="w-100p"><input type="number" class="input-flat" name="costo" value="<?php echo ($p_costo); ?>" min="0.00" step="0.01" size="7" /></td>
        </tr>
        <tr>
          <td><b>Categoria:</b></td>
          <td class="w-100p"><input type="text" class="input-flat w-100p" name="categoria" value="<?php echo ($p_categoria); ?>" /></td>
        </tr>
        <tr>
          <td><b>Quantita:</b></td>
          <td class="w-100p"><input type="number" class="input-flat" name="quantita" value="<?php echo ($p_quantita); ?>" min="0" step="1" size="5" /></td>
        </tr>
      </table>
<?php if ($da_modificare) { ?>
      <input type="hidden" name="id" value="<?php echo ($p_id); ?>" />
      <button type="submit" name="azione" value="modifica" class="button" title="Conferma modifiche">Modifica</button>
<?php } else { ?>
      <button type="submit" name="azione" value="aggiungi" class="button" title="Aggiungi">Aggiungi</button>
<?php } ?>
    </form>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
