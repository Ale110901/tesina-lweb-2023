<?php
require_once('xml.php');

session_start();
$sessione = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);
$email = "prova";
$password = "prova";
$doc = load_xml('utenti');
access_verification($doc, $email, $password);
?>

<div id="header">
  <div id="icone">
    <a href="<?php if($sessione) { ?> profilo.php <?php } else { ?> registrazione.php <?php }?>" title="profilo">&#x01F464</a>   
    <a href="#" title="carrello">&#x01F6D2</a>
    <span id="numero-item-carrello">0</span>
  </div>
  <h1 id="titolo"><a href="index.php">R&amp;C store</a></h1>
  <ul id="menu">
    <li><a href="index.php">Homepage</a></li>
    <li><a href="corsi.php">Catalogo</a></li>
    <li><a href="servizi.php">Servizi</a></li>
    <li><a href="shop.php">Shop</a></li>
    <li><a href="info.php">Informazioni</a></li>
  </ul>
</div>
<hr id="riga-sezioni" />
