<?php
require_once('../config.php');
require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/xml.php');

$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

$nome = 'test'; // $_POST['nome'];
$cognome = 'test'; // $_POST['cognome'];
$username = 'test'; // $_POST['username'];
$password = 'test'; // $_POST['password'];
$telefono = 'test'; // $_POST['telefono'];
$indirizzo = 'test'; // $_POST['indirizzo'];
$codice_fiscale = 'test'; // $_POST['codice_fiscale'];

$err_pwd = false;
$err_tel = false;
$err_indir = false;
$err_cf = false;

$registrato = false;

if ($nome === '' || $cognome === '' || $username === '' || $password === '') {
  $err_vuoto = true;
} else if (!preg_match('/^[A-Za-z0-9!£$%&()=?^,.;:_|]{8,}$/', $password)) {
  $err_pwd = true;
} else if (!preg_match('/^[A-Z]{6}[0-9]{2}[A-Z][0-9]{2}[A-Z][0-9]{3}[A-Z]$/', $codice_fiscale)) { 
  $err_cf = true;
} else if (!preg_match('/^\+\[0-9]{12}$/', $telefono)) { 
  $err_tel = true;
} else if (!preg_match('/^([[:alnum:] ]+), ([a-zA-Z ]+), ([a-zA-Z ]+)$/', $indirizzo)) {
  $err_indir = true;
}


$errore = $err_pwd || $err_tel || $err_indir || $err_cf || $err_vuoto;

$link_log = RC_SUBDIR . '/utente/login.php';
if ($redirect !== '') {
  $link_log .= '?redirect=' . $redirect;
}

if (isset($_POST['azione']) && $_POST['azione'] === 'registrazione' && !$errore) {
  $doc_utenti = load_xml('utenti');
  $root = $doc_utenti->documentElement;
  $utenti = $root->childNodes;

  $nuovo_utente = $doc_utenti->createElement('utente');

  $id_utente = get_next_id($utenti);
  $nuovo_utente->setAttribute('id', $id_utente);
  $nuovo_utente->setAttribute('tipo', 'cliente');
  $nuovo_utente->setAttribute('email', $username);

  $attivo = $doc_utenti->createElement('attivo', true);
  $nuovo_utente->appendChild($attivo);
  $nome = $doc_utenti->createElement('nome', $nome);
  $nuovo_utente->appendChild($nome);
  $cognome = $doc_utenti->createElement('indirizzo', $cognome);
  $nuovo_utente->appendChild($cognome);
  $telefono = $doc_utenti->createElement('telefono', $telefono);
  $nuovo_utente->appendChild($telefono);
  $indirizzo = $doc_utenti->createElement('indirizzo', $indirizzo);
  $nuovo_utente->appendChild($indirizzo);
  $codice_fiscale = $doc_utenti->createElement('codice_fiscale', $codice_fiscale);
  $nuovo_utente->appendChild($codice_fiscale);
  $password = $doc_utenti->createElement('password', $password);
  $nuovo_utente->appendChild($password);
  $reputazione = $doc_utenti->createElement('reputazione', 0);
  $nuovo_utente->appendChild($reputazione);
  $credito = $doc_utenti->createElement('credito', 0);
  $nuovo_utente->appendChild($credito);

  $root->appendChild($nuovo_utente);

  save_xml($doc_utenti, 'utenti');

  $registrato = true;
}

?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Home page &ndash; R&amp;C store</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rampart+One&amp;display=swap" />

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/index.css" />
</head>

<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2 class="py-16">REGISTRAZIONE</h2>
<?php if (!$registrazione || $errore) { ?>
    <form action="<?php echo(RC_SUBDIR); ?>/utente/registrazione.php" method="POST">
      <label for="nome">Nome:</label><br>
      <input type="text" class="input-box" name="nome" value="<?php echo($nome); ?>"><br>

      <label for="cognome">Cognome:</label><br>
      <input type="text" class="input-box" name="cognome" value="<?php echo($cognome); ?>"><br>

      <label for="username">Email:</label><br>
      <input type="text" class="input-box" name="username" value="<?php echo($username); ?>"><br>

      <label for="password">Password:</label><br>
      <input type="password" class="input-box" name="password" value="<?php if (!$err_pwd) { echo($password); } ?>"><br>

      <label for="password">Telefono:</label><br>
      <input type="text" class="input-box" name="telefono" value="<?php if (!$err_tel) { echo($telefono); } ?>"><br>

      <label for="password">Indirizzo:</label><br>
      <input type="text" class="input-box" name="indirizzo" value="<?php if (!$err_indir) { echo($indirizzo); } ?>"><br>

      <label for="password">Codice fiscale:</label><br>
      <input type="text" class="input-box" name="codice_fiscale" value="<?php if (!$err_cf) { echo($codice_fiscale); } ?>"><br>

      <button type="submit" name="azione" value="registrazione" class="button">Registrati!</button>

      <input type="hidden" name="redirect" value="<?php echo($redirect); ?>"></input>
    </form>

    <div class="pt-16 mb-8">
      <a href="<?php echo($link_log); ?>">Accedi con un account esistente</a>
<?php
  if ($err_vuoto) {
?>
      <p>Tutti i campi devono essere compilati!</p>
<?php
  } else {
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
?>
      <p class="mt-8">Telefono non valido. (specificare anche il prefisso, es: +39)</p>
<?php
    }
    if ($err_indir) {
?>
      <p class="mt-8">Indirizzo non valido.</p>
    </div>
<?php
    }
    if ($err_cf) {
?>
      <p class="mt-8">Codice fiscale non valido.</p>
<?php
    }
  }
} else if ($registrato) {
?>
      <p>Account registrato!</p>
      <a href="<?php echo(RC_SUBDIR); ?>/login.php">Accedi</a>
<?php } else { ?>
      <p>Errore nella creazione dell'account.</p>
      <p>L'account esiste gi&agrave;?</p>
      <p>Prova ad accedere...</p>
      <a href="<?php echo(RC_SUBDIR); ?>/login.php">Accedi</a>
<?php } ?>
    </div>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>

</html>
