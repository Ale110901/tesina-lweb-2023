<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = false;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');

$id_utente = $_SESSION['id_utente'];

$doc_utenti = load_xml('utenti');
$doc_ordini = load_xml('ordini');
$doc_prodotti = load_xml('prodotti');

$indirizzo_utente = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:indirizzo')[0]->textContent;
$err_ind = false;
$ordine_creato = false;

if(isset($_POST['totale-ordine'])) {
  $totale_ordine = $_POST['totale-ordine'];
}

if(isset($_POST['azione']) && $_POST['azione'] === 'invio-ordine'){
    $prodotti_ordine = xpath($doc_utenti, 'utenti', '/ns:utenti/ns:utente[@id=' . $id_utente . ']/ns:carrello/ns:prodotto');
    $indirizzo_ordine = $_POST['indirizzo-ordine'];

    if($indirizzo_ordine !== $indirizzo_utente){
      if (!preg_match('/^([[:alnum:] ]+), ([a-zA-Z ]+), ([a-zA-Z ]+)$/', $indirizzo_ordine)) {
        $err_ind = true;
        $indirizzo_utente = '';
      }
    }

    $root = $doc_ordini->documentElement;
    $ordini = $root->childNodes;

    $id_ordine = get_next_id($ordini);

    $nuovo_ordine = $doc_ordini->createElement('ordine');

    $nuovo_ordine->setAttribute('id', $id_ordine);
    $nuovo_ordine->setAttribute('idUtente', $id_utente);

    $el_indirizzo = $doc_ordini->createElement('indirizzo', $indirizzo_ordine);
    $nuovo_ordine->appendChild($el_indirizzo);
    $el_prezzo = $doc_ordini->createElement('prezzoFinale', $totale_ordine);
    $nuovo_ordine->appendChild($el_prezzo);
    $el_data = $doc_ordini->createElement('data', date('Y-m-d H:i:s'));
    $nuovo_ordine->appendChild($el_data);

    $el_prodotti = $doc_ordini->createElement('prodotti');

    foreach ($prodotti_ordine as $prodotto) {
      $nuovo_prodotto = $doc_ordini->createElement('prodotto');

      $id_prod = $prodotto->getAttribute('id');
      $qta_prod = $prodotto->getAttribute('quantita');

      $nuovo_prodotto->setAttribute('id', $id_prod);
      $nuovo_prodotto->setAttribute('quantita', $qta_prod);

      $el_prodotti->appendChild($nuovo_prodotto);
    }

    /*$nuovo_ordine->appendChild($el_prodotti);*/

    save_xml($doc_ordini, 'ordini');
    $ordine_creato = true;
}
?>

<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Carrello &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/ordine.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2>ORDINE</h2>
    <h3 class="my-32">Riepilogo ordine:</h3>
    <ul>
<?php
foreach ($prodotti as $prodotto) {
  $id_prod = $prodotto->getAttribute('id');
  $qta_prod = $prodotto->getAttribute('quantita');

  $result = xpath($doc_prodotti, 'prodotti', "/ns:prodotti/ns:prodotto[@id='$id_prod']");
  $prodotto = $result[0];

  $nome_prod = $prodotto->getElementsByTagName('nome')[0]->textContent;
?>
        <pre class="giustificato">
            <?php echo($qta_prod . 'x ' . $nome_prod); ?>
        </pre>
<?php
}
?>
    </ul>

    <hr class="mt-8 mb-32" />
    <div>
        <form method="post">
            <label>Indirizzo di spedizione:</label>
            <input type="text" class="ml-32 input-flat" name="indirizzo-ordine" value="<?php echo($indirizzo_utente); ?>"/><br />
            <button type="submit" class="button" name="azione" value="invio-ordine">Conferma ordine</button>
        </form>
<?php if($err_ind) {?>
        <p class="mt-32">L'indirizzo deve essere nel formato: VIA CIVICO, CITTA, PAESE</p>
<?php } ?>
<?php if($ordine_creato) { ?>
        <h4 class="mt-32">Ordine creato con successo!, verrai reindirizzato al catalogo...</h4>
        <meta http-equiv="refresh" content="3;../catalogo.php">
<?php } ?>
    </div>

  </div>

  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
