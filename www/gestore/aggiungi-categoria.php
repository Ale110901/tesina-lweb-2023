<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = false;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/categorie.php');
require_once(RC_ROOT . '/lib/utils.php');

if (!isset($_POST['azione'])) {
  // Non fa niente
} else if ($_POST['azione'] === 'aggiungi') {
    aggiungi_categoria($_POST['nome']);
    redirect(307, RC_SUBDIR . '/gestore/categorie.php', false);
}

?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Aggiungi categoria &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>AGGIUNGI CATEGORIA</h2>
    <form method="post" action="<?php echo(RC_SUBDIR); ?>/gestore/aggiungi-categoria.php">
      <div class="my-32">
        <label for="nome" class="mr-8 grassetto">Nome:</label>
        <input type="text" class="input-flat" name="nome" />
      </div>
      <button type="submit" class="button mb-16" name="azione" value="aggiungi">Aggiungi</button><br />
    </form>

    <a class="button" onclick="history.back()">Torna indietro</a>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
