<?php
$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/ordini.php');
require_once($rc_root . '/lib/prodotti.php');
require_once($rc_root . '/lib/xml.php');

if ($e_cliente) {
  $id_utente = $_SESSION['id_utente'];
} else if ($e_gestore) {
  $id_utente = $_GET['id'];
}

$ordini = xpath($doc_ordini, 'ordini', "/ns:ordini/ns:ordine[@idUtente='$id_utente']");
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Storico acquisti &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/storico.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>

  <div id="contenuto" class="centrato">
    <h2>ORDINI</h2>
    <table id="tbl-ordini" class="mx-auto my-32">
      <thead>
        <th>Data e ora</th>
        <th>Indirizzo</th>
        <th>Prezzo pagato</th>
        <th>Articoli</th>
      </thead>
      <tbody>
<?php
foreach ($ordini as $ordine) {
  $result = $ordine->getElementsByTagName('data')[0]->textContent;

  $o_data = date_format(date_create($result), 'Y/m/d H:i:s');
  $o_indirizzo = $ordine->getElementsByTagName('indirizzo')[0]->textContent;
  $o_prezzo = $ordine->getElementsByTagName('prezzoFinale')[0]->textContent;

  $o_prodotti = $ordine->getElementsByTagName('prodotti')[0]->childNodes;
  $o_articoli = '';

  foreach ($o_prodotti as $prodotto) {
    $p_id = $prodotto->getAttribute('id');
    $p_quantita = $prodotto->getAttribute('quantita');

    $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id='$p_id']/ns:nome");
    $p_nome = $result[0]->textContent;
    $o_articoli .= $p_quantita . 'x <a href="' . $rc_subdir . '/prodotto.php?id=' . $p_id . '">' . $p_nome . "</a>\n";
  }
?>
        <tr>
          <td><?php echo($o_data); ?></td>
          <td><?php echo($o_indirizzo); ?></td>
          <td><?php echo(number_format($o_prezzo, 2)); ?> &euro;</td>
          <td><pre class="giustificato"><?php echo($o_articoli); ?></pre></td>
        </tr>
<?php } ?>
      </tbody>
    </table>
    <a class="button" onclick="history.back();">Torna indietro</a>
  </div>

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
