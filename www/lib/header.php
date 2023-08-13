<?php
require_once(RC_ROOT . '/lib/carrello.php');

$qta_carrello = conta_carrello();
?>
<div id="header">
  <div id="icone">
    <span>
      <a href="<?php echo(RC_SUBDIR); ?>/utente/index.php" title="Dashboard">&#x01F464</a>
    </span>
<?php if (!isset($_SESSION['tipo_utente']) || $_SESSION['tipo_utente'] === 'cliente') { ?>
    <span class="icona-con-notifica">
      <a href="<?php echo(RC_SUBDIR); ?>/cliente/carrello.php" title="Carrello">&#x01F6D2</a>
      <span><?php echo($qta_carrello); ?></span>
    </span>
<?php } ?>
  </div>
  <h1 id="titolo">
    <a href="<?php echo(RC_SUBDIR); ?>/index.php">
      <img src="<?php echo(RC_SUBDIR); ?>/res/img/title.svg" alt="R&C store" />
    </a>
  </h1>
  <ul id="menu">
    <li><a href="<?php echo(RC_SUBDIR); ?>/index.php">Homepage</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/catalogo.php">Catalogo</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/faq.php">FAQ</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/info.php">Informazioni</a></li>
  </ul>
</div>
<hr id="riga-sezioni" />
