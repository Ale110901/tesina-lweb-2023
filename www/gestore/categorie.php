<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once($rc_root . '/lib/start.php');
require_once($rc_root . '/lib/categorie.php');
require_once($rc_root . '/lib/xml.php');

$elimina = isset($_POST['azione']) && $_POST['azione'] === 'elimina';

if ($elimina) {
  elimina_categoria($_POST['id']);
}

$categorie = $doc_categorie->documentElement->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Gestione categorie &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />

</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="contenuto" class="centrato">
    <h2 class="mb-32">GESTIONE CATEGORIE</h2>
    <div class="table mt-32 mx-auto mb-32 giustificato">
      <div class="thg grassetto">
        <div class="td">Nome</div>
        <div class="td">Elimina</div>
      </div>
<?php
foreach ($categorie as $categoria) {
  $c_id = $categoria->getAttribute('id');
  $c_nome = $categoria->getElementsByTagName('nome')[0]->textContent;

?>
      <div class="tr">
        <div class="td"><?php echo($c_nome); ?></div>
        <form class="td centrato" action="<?php echo($rc_subdir); ?>/gestore/categorie.php" method="post">
          <input type="hidden" name="id" value="<?php echo($c_id); ?>" />
          <button type="submit" class="button-icona" name="azione" value="elimina">&#x01F5D1</button>
        </form>
      </div>
<?php
}
?>
    </div>
      <p class="l-h3em">
        <a class="button b-32" href="<?php echo($rc_subdir);?>/gestore/aggiungi-categoria.php">Aggiungi categoria</a><br />
        <a class="button" href="<?php echo($rc_subdir);?>/gestore/index.php">Torna indietro</a>
      </p>

  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
