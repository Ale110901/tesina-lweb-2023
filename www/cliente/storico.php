<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/ordini.php');
require_once(RC_ROOT . '/lib/prodotti.php');
require_once(RC_ROOT . '/lib/xml.php');

$id_utente = $_SESSION['id_utente'];

$ordini = xpath($doc_ordini, 'ordini', "/ns:ordini/ns:ordine[@idUtente='$id_utente']");
?>

<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Storico acquisti &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/storico.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>

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
    $o_articoli .= $p_quantita . "x " . $p_nome . "\n";
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
    <a class="button" href="<?php echo(RC_SUBDIR); ?>/cliente/profilo.php">Torna indietro</a>
  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
