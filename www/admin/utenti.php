<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = false;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/utenti.php');
require_once($rc_root . '/lib/xml.php');

$modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

if ($modifica) {
  $id_utente = $_POST['id'];
  $tipo_utente = $_POST['tipo'];

  if ($tipo_utente === 'gestore' || $tipo_utente === 'admin') {
    modifica_ut_spec($id_utente,
      $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['password']);
  } else {
    $attivo = isset($_POST['attivo']) && $_POST['attivo'] === 'on';

    modifica_cliente($id_utente, $attivo,
      $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['password'],
      $_POST['telefono'], $_POST['indirizzo'], $_POST['codice-fiscale']);
  }
}

$utenti = $doc_utenti->documentElement->childNodes;
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Dashboard admin &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/utenti-admin.css" />

</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>

  <div id="gestione-utenti">
    <h2>GESTIONE UTENTI</h2>
    <div class="table mt-32">
        <div id="intestazione" class="thg grassetto">
          <div class="td">Tipo</div>
          <div class="td">Email</div>
          <div class="td">Nome</div>
          <div class="td">Cognome</div>
          <div class="td">Telefono</div>
          <div class="td">Indirizzo</div>
          <div class="td">Codice fiscale</div>
          <div class="td">Password</div>
          <div class="td">Attivo</div>
          <div class="td">Modifica</div>
        </div>
<?php
foreach ($utenti as $utente) {
  $ut_id = $utente->getAttribute('id');
  $ut_tipo = $utente->getAttribute('tipo');
  $ut_nome = $utente->getElementsByTagName('nome')[0]->textContent;
  $ut_cognome = $utente->getElementsByTagName('cognome')[0]->textContent;
  $ut_email = $utente->getAttribute('email');

  if ($ut_tipo === 'cliente') {
    $ut_telefono = $utente->getElementsByTagName('telefono')[0]->textContent;
    $ut_indirizzo = $utente->getElementsByTagName('indirizzo')[0]->textContent;
    $ut_codice_fiscale = $utente->getElementsByTagName('codiceFiscale')[0]->textContent;
    $ut_attivo = $utente->getElementsByTagName('attivo')[0]->textContent;
  }
?>
        <form class="tr" id="utente-<?php echo($ut_id); ?>" method="post" action="<?php echo($rc_subdir); ?>/admin/utenti.php">
          <input type="hidden" name="id" value="<?php echo($ut_id); ?>"></input>
          <input type="hidden" name="tipo" value="<?php echo($ut_tipo); ?>"></input>
          <div class="td">
            <span><?php echo($ut_tipo); ?></span>
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_email); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="email" value="<?php echo($ut_email); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_nome); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="nome" value="<?php echo($ut_nome); ?>" />
          </div>
          <div class="td">
            <span class="con-toggle"><?php echo($ut_cognome); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="cognome" value="<?php echo($ut_cognome); ?>" />
          </div>
          <div class="td">
<?php if ($ut_tipo === 'cliente') { ?>
            <span class="con-toggle"><?php echo($ut_telefono); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="telefono" value="<?php echo($ut_telefono); ?>" />
<?php } ?>
          </div>
          <div class="td">
<?php if ($ut_tipo === 'cliente') { ?>
            <span class="con-toggle"><?php echo($ut_indirizzo); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="indirizzo" value="<?php echo($ut_indirizzo); ?>" />
<?php } ?>
          </div>
          <div class="td">
<?php if ($ut_tipo === 'cliente') { ?>
            <span class="con-toggle"><?php echo($ut_codice_fiscale); ?></span>
            <input type="text" class="input-flat con-toggle nascosto" name="codice-fiscale" value="<?php echo($ut_codice_fiscale); ?>" />
<?php } ?>
          </div>
          <div class="td">
            <span class="con-toggle">&ndash;</span>
            <input type="password" class="input-flat con-toggle nascosto" name="password" />
          </div>
          <div class="td">
<?php if ($ut_tipo === 'cliente') { ?>
            <input type="checkbox" class="con-toggle" <?php if ($ut_attivo === 'true') echo("checked"); ?> disabled></input>
            <input type="checkbox" class="con-toggle nascosto" name="attivo" <?php if ($ut_attivo === 'true') echo("checked"); ?>></input>
<?php } ?>
          </div>
          <div class="td">
            <a href="#" class="button-icona con-toggle" onclick="visibilita(<?php echo($ut_id); ?>);">&#x01F4DD</a>
            <button type="submit" name="azione" value="modifica" class="button-icona con-toggle nascosto">&#x2705</button>
          </div>
        </form>
<?php
}
?>
    </div>

    <div class="centrato l-h3em">
      <a class="button" href="<?php echo($rc_subdir); ?>/admin/index.php">Torna indietro</a>
    </div>
  </div>

  <script type="text/javascript">
    function visibilita(id) {
      var elemInfo = document.querySelectorAll('#utente-' + id + ' > div > .con-toggle');
      for (var i = 0; i < elemInfo.length; i++) {
        elemInfo[i].classList.toggle("nascosto");
      }
    }
  </script>

  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
