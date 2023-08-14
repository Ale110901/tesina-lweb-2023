<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/faq.php');
require_once(RC_ROOT . '/lib/utils.php');
require_once(RC_ROOT . '/lib/xml.php');

$faq_domanda = '';
$faq_risposta = '';

$doc_faq = load_xml('faq');

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);

if ($id_valido) {
  $faq_id = $_GET['id'];

  $result = xpath($doc_faq, 'faq', '/ns:faqs/ns:faq[@id=' . $faq_id . ']');
  if ($result->length !== 1) {
    $id_valido = false;
  } else {
    $faq = $result[0];

    $modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

    if ($modifica) {
      $faq_id = $_POST['id'];
      $faq_domanda =  $_POST['domanda'];
      $faq_risposta = $_POST['risposta'];

      modifica_faq($doc_faq, $faq_id, $_POST['domanda'], $_POST['risposta']);

      redirect(307, RC_SUBDIR . '/faq.php', false);
    } else {
      $faq_domanda = $faq->getElementsByTagName('domanda')[0]->textContent;
      $faq_risposta = $faq->getElementsByTagName('risposta')[0]->textContent;
    }
  }
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Modifica FAQ &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo(RC_SUBDIR); ?>/res/css/footer.css" />
</head>
<body>
  <?php require(RC_ROOT . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>MODIFICA FAQ</h2>
    <form method="post" action="<?php echo(RC_SUBDIR); ?>/admin/modifica-faq.php?id=<?php echo($faq_id); ?>">
      <input type="hidden" name="id" value="<?php echo($faq_id); ?>"></input>
      <div class="my-32">
        <label for="domanda">Domanda:</label><br />
        <textarea class="input-flat w-50p" name="domanda"  rows="6" placeholder="Inserisci la domanda qui"><?php echo($faq_domanda); ?></textarea>
      </div>
      <div class="my-32">
        <label for="risposta">Risposta:</label><br />
        <textarea class="input-flat w-50p" name="risposta" rows="6" placeholder="Inserisci la risposta qui"><?php echo($faq_risposta); ?></textarea>
      </div>
      <button type="submit" class="button" name="azione" value="modifica">Modifica</button>
    </form>
  </div>
  <?php require(RC_ROOT . '/lib/footer.php'); ?>
</body>
</html>
