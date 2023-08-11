<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');
require_once(RC_ROOT . '/lib/categoria.php');
require_once(RC_ROOT . '/lib/offerte.php');

$doc_prod = load_xml('prodotti');
$prodotti = $doc_prod->documentElement->childNodes;
$prodotti = domlist_to_array($prodotti);

$ord_type = isset($_GET['ordina']) ? $_GET['ordina'] : 'nome';
$ord_desc = isset($_GET['desc']) && $_GET['desc'] === 'on';
$solo_disp = isset($_GET['solo_disp']) && $_GET['solo_disp'] === 'on';

switch ($ord_type) {
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

$doc_categorie = load_xml('categorie');
$doc_offerte = load_xml('offerte');
$doc_utenti = load_xml('utenti');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Catalogo &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/catalogo.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2 class="pb-32">CATALOGO</h2>

    <form id="filtri" action="<?php echo(RC_SUBDIR); ?>/catalogo.php" method="get" class="mb-32">
      <label for="ordina">Ordinamento:</label>

      <select name="ordina">
        <option value="nome" <?php if ($ord_type === 'nome') echo ('selected="true"'); ?> >Nome</option>
        <option value="categoria" <?php if ($ord_type === 'categoria') echo ('selected="true"'); ?> >Categoria</option>
        <option value="marca" <?php if ($ord_type === 'marca') echo ('selected="true"'); ?> >Marca</option>
        <option value="prezzo" <?php if ($ord_type === 'prezzo') echo ('selected="true"'); ?> >Prezzo</option>
      </select>

      <input type="checkbox" name="desc" <?php if ($ord_desc) echo ('checked="true"'); ?>> Ordine contrario</input>
      <input type="checkbox" name="solo_disp" <?php if ($solo_disp) echo ('checked="true"'); ?>> Solo in stock</input>

      <button type="submit" class="ml-8">Invia</button>
    </form>

    <div id="catalogo">
<?php
  foreach ($prodotti as $prodotto) {
    $p_quantita = $prodotto->getElementsByTagName('quantita')[0]->textContent;
    if ($p_quantita === '0' && $solo_disp) {
      continue;
    }

    $p_id = $prodotto->getAttribute('id');
    $p_costo = $prodotto->getElementsByTagName('costo')[0]->textContent;
    $p_nome = $prodotto->getElementsByTagName('nome')[0]->textContent;
    $p_marca = $prodotto->getElementsByTagName('marca')[0]->textContent;
    $p_categoria = $prodotto->getElementsByTagName('categoria')[0]->textContent;

    $off_app = offerte_applicabili($doc_offerte, $doc_utenti, $prodotto);
    $sconto = calcola_sconto($off_app);
    $costo_finale = round($p_costo * (1 - $sconto), 2);
?>
      <div class="card-prodotto <?php if ($p_quantita === '0') echo('out-of-stock'); ?>">
        <a href="<?php echo(RC_SUBDIR); ?>/prodotto.php?id=<?php echo($p_id); ?>">
          <img src="<?php echo(RC_SUBDIR); ?>/res/img/prodotti/<?php echo($p_id); ?>.png" alt="Immagine prodotto <?php echo($p_id); ?>"></img>
          <div class="overlay">terminato</div>
          <p><i><?php echo(ottieni_categoria($doc_categorie, $p_categoria)); ?></i></p>
          <p><i><?php echo($p_marca); ?></i></p>
          <p><b><?php echo($p_nome); ?></b></p>
          <p>
            <span class="prezzo"><?php echo(number_format($costo_finale, 2)); ?> &euro;</span>
<?php if ($p_costo - $costo_finale >= 0.01) { ?>
            <span class="prezzo barrato"><?php echo(number_format($p_costo, 2)); ?> &euro;</span>
<?php } ?>
          </p>
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
