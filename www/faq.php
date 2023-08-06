<?php
require_once('config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');

$doc_faq = load_xml('faq');
$faqs = $doc_faq->documentElement->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>FAQ &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/faq.css" />
</head>

<body>
  <script>
    function mostra(id) {
      document.getElementById('risposta' + id).classList.toggle("nascosto");
    }
  </script>

  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2>FAQ</h2>
    <div id="faq">
<?php
foreach ($faqs as $faq) {
  $id = $faq->getAttribute('id');
  $domanda =  $faq->getElementsByTagName('domanda')[0]->textContent;
  $risposta = $faq->getElementsByTagName('risposta')[0]->textContent;
?>
      <div>
        <button class="btn-domanda" onclick="mostra(<?php echo($id); ?>)"><?php echo($domanda); ?></button>
        <span class="btn-freccia">&#x21D3;</span>
      </div>
      <div id="risposta<?php echo($id); ?>" class="nascosto">
        <p><?php echo($risposta); ?></p>
      </div>
<?php
}
?>
    </div>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
