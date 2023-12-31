<?php
$perm_visitatore = false;
$perm_cliente = false;
$perm_gestore = true;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/faq.php');
require_once($rc_root . '/lib/utils.php');
require_once($rc_root . '/lib/xml.php');

$faq_domanda = '';
$faq_risposta = '';
$errore = false;

$id_valido = isset($_GET['id']) && !is_nan($_GET['id']);

if ($id_valido) {
  $faq_id = $_GET['id'];

  $result = xpath($doc_faq, 'faq', '/ns:faqs/ns:faq[@id=' . $faq_id . ']');

  if ($result->length !== 1) {
    $id_valido = false;
  } else {
    $faq = $result[0];

    $faq_domanda = $faq->getElementsByTagName('domanda')[0]->textContent;
    $faq_risposta = $faq->getElementsByTagName('risposta')[0]->textContent;

    $modifica = isset($_POST['azione']) && $_POST['azione'] === 'modifica';

    if ($modifica) {
      $ce_domanda = isset($_POST['domanda']) && $_POST['domanda'] !== '';
      $ce_risposta = isset($_POST['risposta']) && $_POST['risposta'] !== '';
      $errore = !$ce_domanda || !$ce_risposta;

      if (!$errore) {
        $faq_id = $_POST['id'];
        $faq_domanda =  $_POST['domanda'];
        $faq_risposta = $_POST['risposta'];

        modifica_faq($faq_id, $_POST['domanda'], $_POST['risposta']);

        redirect(307, $rc_subdir . '/faq.php', false);
      } else {
        if (isset($_POST['domanda'])) {
          $faq_domanda = $_POST['domanda'];
        }

        if (isset($_POST['risposta'])) {
          $faq_risposta = $_POST['risposta'];
        }
      }
    }
  }
}
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Modifica FAQ &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
</head>
<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="pagina-form" class="centrato">
    <h2>MODIFICA FAQ</h2>
    <form method="post" action="<?php echo($rc_subdir); ?>/admin/modifica-faq.php?id=<?php echo($faq_id); ?>">
      <input type="hidden" name="id" value="<?php echo($faq_id); ?>"></input>
      <div class="my-32">
        <label for="domanda" class="grassetto">Domanda:</label><br />
        <textarea class="input-flat w-50p" name="domanda"  rows="6" placeholder="Inserisci la domanda qui"><?php echo($faq_domanda); ?></textarea>
      </div>
      <div class="my-32">
        <label for="risposta" class="grassetto">Risposta:</label><br />
        <textarea class="input-flat w-50p" name="risposta" rows="6" placeholder="Inserisci la risposta qui"><?php echo($faq_risposta); ?></textarea>
      </div>
      <button type="submit" class="button mb-16" name="azione" value="modifica">Modifica</button><br />
      <a class="button" href="<?php echo($rc_subdir);?>/faq.php" >Torna indietro</a>

<?php if ($errore) { ?>
      <p class="mt-32 grassetto">
        &#x26a0; inserire tutti i campi!
      </p>
<?php } ?>

    </form>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>
</html>
