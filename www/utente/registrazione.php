<?php
$perm_visitatore = true;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = false;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/constants.php');
require_once($rc_root . '/lib/utenti.php');

$err_vuoto = false;
$err_match = false;
$err_pwd = false;
$err_tel = false;
$err_indir = false;
$err_cf = false;
$errore = false;

$registrazione = isset($_POST['azione']) && $_POST['azione'] === 'registrazione';
$registrato = false;

if ($registrazione) {
  $nome = $_POST['nome'];
  $cognome = $_POST['cognome'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];
  $telefono = $_POST['telefono'];
  $indirizzo = $_POST['indirizzo'];
  $codice_fiscale = $_POST['codice_fiscale'];

  if ($nome === '' || $cognome === '' || $email === '' || $password === '' ||
    $telefono === '' || $indirizzo === '' || $codice_fiscale === '')
  {
    $err_vuoto = true;
  } else {
    if ($password !== $password2) {
      $err_match = true;
      $password = '';
      $password2 = '';
    }

    if ($password !== '' && !preg_match(REGEX_PASSWORD, $password)) {
      $err_pwd = true;
      $password = '';
      $password2 = '';
    }

    if ($codice_fiscale !== '' && !preg_match(REGEX_CF, $codice_fiscale)) {
      $err_cf = true;
      $codice_fiscale = '';
    }

    if ($telefono !== '' && !preg_match(REGEX_TELEFONO, $telefono)) {
      $err_tel = true;
      $telefono = '';
    }

    if ($indirizzo !== '' && !preg_match(REGEX_INDIRIZZO, $indirizzo)) {
      $err_indir = true;
      $indirizzo = '';
    }
  }

  $errore = $err_pwd || $err_tel || $err_indir || $err_cf || $err_vuoto;

  if (!$errore) {
    $registrato = registra_utente($nome, $cognome, $email, $password, $telefono, $indirizzo, $codice_fiscale);
  }

  $redir_dest = isset($_POST['redirect']) ? $_POST['redirect'] : '';
} else {
  $nome = '';
  $cognome = '';
  $email = '';
  $password = '';
  $password2 = '';
  $telefono = '';
  $indirizzo = '';
  $codice_fiscale = '';

  $redir_dest = isset($_GET['redirect']) ? $_GET['redirect'] : '';
}

$link_log = $rc_subdir . '/utente/login.php';
if ($redir_dest !== '') {
  $link_log .= '?redirect=' . $redir_dest;
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Registrazione &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2 class="py-16">REGISTRAZIONE</h2>
<?php if (!$registrazione || $errore) { ?>
    <form action="<?php echo($rc_subdir); ?>/utente/registrazione.php" method="post">
      <label for="nome">Nome:</label><br>
      <input type="text" class="input-box" name="nome" value="<?php echo($nome); ?>"><br>

      <label for="cognome">Cognome:</label><br>
      <input type="text" class="input-box" name="cognome" value="<?php echo($cognome); ?>"><br>

      <label for="email">Email:</label><br>
      <input type="text" class="input-box" name="email" value="<?php echo($email); ?>"><br>

      <label for="password">Password:</label><br>
      <input type="password" class="input-box" name="password" value="<?php echo($password); ?>"><br>

      <label for="password2">Conferma password:</label><br>
      <input type="password" class="input-box" name="password2" value="<?php echo($password2); ?>"><br>

      <label for="telefono">Telefono:</label><br>
      <input type="text" class="input-box" name="telefono" value="<?php echo($telefono); ?>"><br>

      <label for="indirizzo">Indirizzo:</label><br>
      <input type="text" class="input-box" name="indirizzo" value="<?php echo($indirizzo); ?>"><br>

      <label for="codice_fiscale">Codice fiscale:</label><br>
      <input type="text" class="input-box" name="codice_fiscale" value="<?php echo($codice_fiscale); ?>"><br>

      <button type="submit" name="azione" value="registrazione" class="button mt-32">Registrati!</button>

      <input type="hidden" name="redirect" value="<?php echo($redir_dest); ?>"></input>
    </form>

    <div class="pt-16 mb-8">
      <a href="<?php echo($link_log); ?>">Accedi con un account esistente</a>
      <div class="giustificato px-16">
<?php
  if ($err_vuoto) {
    ?><p class="centrato grassetto mt-16">Tutti i campi devono essere compilati!</p><?php
  }
  if ($err_match) {
    ?><p class="mt-8">Le password non corrispondono.</p><?php
  }
  if ($err_pwd) {
?>
        <p class="mt-8">La password deve contenere almeno 8 caratteri, tra i quali sono ammessi:</p>
        <ul id="formato_password">
          <li>lettere maiuscole: A&ndash;Z</li>
          <li>lettere minuscole: a&ndash;z</li>
          <li>numeri: 0&ndash;9</li>
          <li>caratteri speciali: &excl;&pound;&dollar;&percnt;&amp;&lpar;&rpar;&equals;&quest;&Hat;&comma;&period;&semi;&colon;&lowbar;&vert;</li>
        </ul>
<?php
  }
  if ($err_tel) {
    ?><p class="mt-8">Telefono non valido. (specificare anche il prefisso senza spazi, es: +39)</p><?php
  }
  if ($err_indir) {
    ?><p class="mt-8">L'indirizzo deve essere nel formato: VIA CIVICO, CITTA, PAESE</p><?php
  }
  if ($err_cf) {
    ?><p class="mt-8">Codice fiscale non valido.</p><?php
  }
?>
<?php
} else if ($registrato) {
?>
      <p>Account registrato!</p>
      <a href="<?php echo($link_log); ?>">Accedi</a>
<?php } else { ?>
      <p>Errore nella creazione dell'account.</p>
      <p>L'account esiste gi&agrave;?</p>
      <p>Prova ad accedere...</p>
      <a href="<?php echo($link_log); ?>">Accedi</a>
<?php } ?>
      </div>
    </div>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
