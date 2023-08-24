<?php
$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/utils.php');

$sessione = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);
$login = isset($_POST['azione']) && $_POST['azione'] === 'accedi';

if ($sessione) {
  $loggato = true;
  $redir_dest = isset($_GET['redirect']) ? $_GET['redirect'] : '';

} else if (!$login) {
  $loggato = false;
  $redir_dest = isset($_GET['redirect']) ? $_GET['redirect'] : '';

} else {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $redir_dest = isset($_POST['redirect']) ? $_POST['redirect'] : '';

  $result = login_utente($username, $password);
  $loggato = $result === 'Ok';
}

if ($loggato) {
  if ($redir_dest === '') {
    $redir_dest = $rc_subdir . '/index.php';
  }

  redirect(307, $redir_dest, false);
}

$link_reg = $rc_subdir . '/utente/registrazione.php';
if ($redir_dest !== '') {
  $link_reg .= '?redirect=' . $redir_dest;
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Login &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2 class="py-16">LOGIN</h2>
    <form action="<?php echo($rc_subdir); ?>/utente/login.php" method="post" >
      <label for="username">Nome utente:</label><br>
      <input type="text" class="input-box" name="username"><br><br>

      <label for="password">Password:</label><br>
      <input type="password" class="input-box" name="password"><br><br>

      <button type="submit" name="azione" value="accedi" class="button">Accedi</button>

      <input type="hidden" name="redirect" value="<?php echo($redir_dest); ?>"></input>
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
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
