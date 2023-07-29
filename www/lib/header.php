<div id="header">
  <div id="icone">
<?php if ($loggato) { ?>
    <a href="<?php echo(RC_SUBDIR); ?>/utente/profilo.php" title="Profilo">&#x01F464</a>
<?php } else { ?>
    <a href="<?php echo(RC_SUBDIR); ?>/registrazione.php" title="profilo">&#x01F464</a>
<?php }?>
    <a href="#" title="carrello">&#x01F6D2</a>
    <span id="numero-item-carrello">0</span>
  </div>
  <h1 id="titolo"><a href="<?php echo(RC_SUBDIR); ?>/index.php">R&amp;C store</a></h1>
  <ul id="menu">
    <li><a href="<?php echo(RC_SUBDIR); ?>/index.php">Homepage</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/catalogo.php">Catalogo</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/servizi.php">Servizi</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/shop.php">Shop</a></li>
    <li><a href="<?php echo(RC_SUBDIR); ?>/info.php">Informazioni</a></li>
  </ul>
</div>
<hr id="riga-sezioni" />
