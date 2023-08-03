<?php
require_once('../config.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/utente.php');

$sessione = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);
$login = isset($_POST['azione']) && $_POST['azione'] === 'accedi';

if ($sessione) {
  $loggato = true;
  $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

} else if (!$login) {
  $loggato = false;
  $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

} else {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '';

  $result = login_utente($username, $password);
  $loggato = $result === 'Ok';
}

if ($loggato) {
  if ($redirect === '') {
    $redirect = RC_SUBDIR . '/index.php';
  }
  header('Location: ' . $redirect);
  exit();
}

$link_reg = RC_SUBDIR . '/utente/registrazione.php';
if ($redirect !== '') {
  $link_reg .= '?redirect=' . $redirect;
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Login &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2 class="py-16">LOGIN</h2>
    <form action="<?php echo(RC_SUBDIR); ?>/utente/login.php" method="post" >
      <label for="username">Nome utente:</label><br>
      <input type="text" class="input-box" name="username" value="mario.rossi@gmail.com"><br><br>

      <label for="password">Password:</label><br>
      <input type="password" class="input-box" name="password" value="password"><br><br>

      <button type="submit" name="azione" value="accedi" class="button">Accedi</button>

      <input type="hidden" name="redirect" value="<?php echo($redirect); ?>"></input>
    </form>
    <div class="pt-16 mb-8">
      <a href="<?php echo($link_reg); ?>">Registra un nuovo account</a>
<?php
if ($login && !$loggato) {
  if ($result === 'Errore abilitazione') {
?>
      <p>L'account &egrave; disabilitato. Contattare l'amministratore.</p>
<?php } else { ?>
      <p>Nome utente o password non corretti.</p>
<?php
  }
}
?>
    </div>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
