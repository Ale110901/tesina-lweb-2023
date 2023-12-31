<?php
$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

$rc_level = 0;
require_once('./lib/start.php');

require_once($rc_root . '/lib/faq.php');
require_once($rc_root . '/lib/xml.php');

$perm_modifica = $e_gestore || $e_admin;

$elimina = isset($_POST['azione']) && $_POST['azione'] === 'elimina';

if ($perm_modifica && $elimina) {
  elimina_faq($_POST['id']);
}

$faqs = $doc_faq->documentElement->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>FAQ &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/faq.css" />
</head>

<body>
  <script>
    function mostra(id) {
      document.getElementById('risposta' + id).classList.toggle("nascosto");
    }
  </script>

  <?php require($rc_root . '/lib/header.php'); ?>
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
        <span class="btn-freccia">&#x21D3;</span><br />
<?php if ($perm_modifica) { ?>
        <a href="<?php echo($rc_subdir); ?>/admin/modifica-faq.php?id=<?php echo($id); ?>" id="posizione-modifica"  class="button-icona" title="modifica">&#x01F4DD</a>
        <form method="post" action="<?php echo($rc_subdir); ?>/faq.php">
          <input type="hidden" name="id" value="<?php echo($id); ?>" />
          <button type="submit" id="posizione-elimina" class="button-icona" name="azione" value="elimina" title="elimina">&#x01F5D1</button>
        </form>

<?php } ?>

      </div>
      <div id="risposta<?php echo($id); ?>" class="nascosto w-70p mx-auto">
        <p><?php echo($risposta); ?></p>
      </div>
<?php
}
?>

<?php if ($perm_modifica) { ?>
      <a href="<?php echo($rc_subdir); ?>/admin/aggiungi-faq.php" class="button">Aggiungi FAQ</a>
<?php } ?>
    </div>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
