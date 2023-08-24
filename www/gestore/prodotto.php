<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once($rc_root . '/lib/start.php');
require_once($rc_root . '/lib/categorie.php');
require_once($rc_root . '/lib/prodotti.php');
require_once($rc_root . '/lib/xml.php');

$aggiungi = isset($_POST['azione']) && $_POST['azione'] === 'aggiungi';
$modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

if ($aggiungi) {
  aggiungi_prodotto($_POST['nome'], $_POST['marca'],
    $_POST['descrizione'], $_POST['costo'], $_POST['categoria'],
    $_POST['quantita'], $_FILES['immagine']);

  redirect(307, $rc_subdir . '/gestore/prodotti.php', false);
}

$p_info = [
  'nome' => '',
  'marca' => '',
  'descrizione' => '',
  'costo' => 0.01,
  'categoria' => 1,
  'quantita' => 0,
];

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);

if ($id_valido) {
  $p_id = $_GET['id'];

  $p_info_t = ottieni_info_prodotto($p_id);
  if (!$p_info_t) {
    $id_valido = false;
  } else if ($modifica) {
    modifica_prodotto($p_id, $_POST['nome'], $_POST['marca'],
      $_POST['descrizione'], $_POST['costo'], $_POST['categoria'],
      $_POST['quantita'], $_FILES['immagine']);

    redirect(307, $rc_subdir . '/gestore/prodotti.php', false);
  } else {
    $p_info = $p_info_t;
  }
}

$da_modificare = $id_valido;

$categorie = $doc_categorie->documentElement->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione prodotto &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/prodotto-gestore.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2>GESTIONE PRODOTTO</h2>
<?php
$a_id = $da_modificare ? '?id=' . $p_id : '';
?>
    <form action="<?php echo($rc_subdir); ?>/gestore/prodotto.php<?php echo($a_id); ?>" method="post" enctype="multipart/form-data">
      <table class="py-1em giustificato">
        <tr>
          <td class="grassetto">Immagine:</td>
          <td class="w-100p pb-16">
<?php
if ($da_modificare) {
?>
            <img class="img-prodotto" src="<?php echo($rc_subdir); ?>/res/img/prodotti/<?php echo($p_id); ?>.png" alt="Immagine prodotto <?php echo($p_id); ?>"></img>
<?php
}
?>
            <input type="file" name="immagine" accept="image/png" />
          </td>
        </tr>
        <tr>
          <td class="grassetto">Marca:</td>
          <td class="w-100p pb-16"><input type="text" class="input-flat w-100p" name="marca" value="<?php echo ($p_info['marca']); ?>" /></td>
        </tr>
        <tr>
          <td class="grassetto">Nome:</td>
          <td class="w-100p pb-16"><input type="text" class="input-flat w-100p" name="nome" value="<?php echo ($p_info['nome']); ?>" /></td>
        </tr>
        <tr>
          <td class="grassetto">Descrizione:</td>
          <td class="w-100p pb-16"><textarea class="input-flat w-100p" name="descrizione" rows="6" placeholder="Inserisci la descrizione qui"><?php echo($p_info['descrizione']); ?></textarea></td>
        </tr>
        <tr>
          <td class="grassetto">Costo:</td>
          <td class="w-100p pb-16"><input type="number" class="input-flat" name="costo" value="<?php echo ($p_info['costo']); ?>" min="0.01" step="0.01" size="7" /></td>
        </tr>
        <tr>
          <td class="grassetto">Categoria:</td>
          <td class="w-100p">
            <select name="categoria">
<?php
foreach ($categorie as $categoria) {
  $c_id = $categoria->getAttribute('id');
  $c_nome = $categoria->getElementsByTagName('nome')[0]->textContent;
  $c_sel = $c_id === $p_info['categoria'] ? 'selected' : '';
?>
              <option value="<?php echo ($c_id); ?>" <?php echo ($c_sel); ?>><?php echo ($c_nome); ?></option>
<?php
}
?>
            </select>
          </td>
        </tr>
        <tr>
          <td><b>Quantita:</b></td>
          <td class="w-100p pb-16"><input type="number" class="input-flat" name="quantita" value="<?php echo ($p_info['quantita']); ?>" min="0" step="1" size="5" /></td>
        </tr>
      </table>
<?php if ($da_modificare) { ?>
      <input type="hidden" name="id" value="<?php echo ($p_id); ?>" />
      <button type="submit" name="azione" value="modifica" class="button mb-16" title="Modifica">Modifica</button><br />
<?php } else { ?>
      <button type="submit" name="azione" value="aggiungi" class="button mb-16" title="Aggiungi">Aggiungi</button><br />
<?php } ?>
      <a class="button" onclick="history.back();">Torna indietro</a>
    </form>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
