<?php
require_once($rc_root . '/lib/carrello.php');

$qta_carrello = conta_carrello();
?>
<div id="header">
  <div id="icone">
    <span>
      <a href="<?php echo($rc_subdir); ?>/utente/index.php" title="Dashboard">&#x01F464</a>
    </span>
<?php if ($e_visitatore || $e_cliente) { ?>
    <span class="icona-con-notifica">
      <a href="<?php echo($rc_subdir); ?>/cliente/carrello.php" title="Carrello">&#x01F6D2</a>
      <span><?php echo($qta_carrello); ?></span>
    </span>
<?php } ?>
  </div>
  <h1 id="titolo">
    <a href="<?php echo($rc_subdir); ?>/index.php">
      <img src="<?php echo($rc_subdir); ?>/res/img/title.svg" alt="R&C store" />
    </a>
  </h1>
  <ul id="menu">
    <li><a href="<?php echo($rc_subdir); ?>/index.php">Homepage</a></li>
    <li><a href="<?php echo($rc_subdir); ?>/catalogo.php">Catalogo</a></li>
    <li><a href="<?php echo($rc_subdir); ?>/faq.php">FAQ</a></li>
    <li><a href="<?php echo($rc_subdir); ?>/info.php">Informazioni</a></li>
  </ul>
</div>
<hr id="riga-sezioni" />
