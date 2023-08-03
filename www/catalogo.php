<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');
require_once(RC_ROOT . '/lib/categoria.php');

$doc_prod = load_xml('prodotti');
$prodotti = $doc_prod->documentElement->childNodes;
$prodotti = domlist_to_array($prodotti);

$ord_desc = $_GET['desc'] === 'on';
switch ($_GET['ordina']) {
  case 'nome':
    $prodotti = sort_by_element_txt($prodotti, 'nome', $ord_desc);
    break;
  case 'marca':
    $prodotti = sort_by_element_txt($prodotti, 'marca', $ord_desc);
    break;
  case 'categoria':
    $prodotti = sort_by_element_dec($prodotti, 'categoria', $ord_desc);
    break;
  case 'prezzo':
    $prodotti = sort_by_element_dec($prodotti, 'costo', $ord_desc);
    break;
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
  <div id="contenuto" class="centrato">
    <h2 class="pb-32">CATALOGO</h2>
    <form action="<?php echo(RC_SUBDIR); ?>/catalogo.php" method="get">
      <label for="sort">Ordinamento:</label>

      <select name="ordina">
        <option value="nome">Nome</option>
        <option value="categoria">Categoria</option>
        <option value="marca">Marca</option>
        <option value="prezzo">Prezzo</option>
      </select>

      <input type="checkbox" name="desc">Ordine contrario</input>

      <button type="submit">Invia</button>
    </form>
    <div id="catalogo">
<?php
  foreach ($prodotti as $prodotto) {
    $p_id = $prodotto->getAttribute('id');
    $p_costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
    $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
    $p_marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
    $p_categoria = $prodotto->getElementsByTagName('categoria')[0]->textContent;
    $p_quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;
?>
      <div>
        <a href="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($p_id); ?>" class="">
          <img id="img-prodotto" src="<?php echo(RC_SUBDIR); ?>/res/img/prodotti/<?php echo($p_id); ?>.png" alt="Immagine prodotto <?php echo($p_id); ?>" ></img>
          <p><?php echo(ottieni_categoria($p_categoria)); ?></p>
          <p><?php echo($p_marca); ?></p>
          <p><?php echo($p_nome); ?></p>
          <p class="prezzo"><?php echo($p_costo); ?> &euro;</p>
        </a>
      </div>
<?php
  }
?>
    </div>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
