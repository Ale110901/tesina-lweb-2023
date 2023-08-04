<?php
require_once(RC_ROOT . '/lib/carrello.php');

$qta_carrello = conta_carrello();
?>
<div id="header">
  <div id="icone">
    <a href="<?php echo(RC_SUBDIR); ?>/utente/profilo.php" title="Profilo">&#x01F464</a>
    <a href="<?php echo(RC_SUBDIR); ?>/cliente/carrello.php" title="carrello">&#x01F6D2</a>
    <span id="numero-item-carrello"><?php echo($qta_carrello); ?></span>
  </div>
  <h1 id="titolo"><a href="<?php echo(RC_SUBDIR); ?>/index.php">R&amp;C store</a></h1>
  <ul id="menu">
    <li><a href="<?php echo(RC_SUBDIR); ?>/index.php">Homepage</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/catalogo.php">Catalogo</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/faq.php">FAQ</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/info.php">Informazioni</a></li>
  </ul>
</div>
<hr id="riga-sezioni" />
