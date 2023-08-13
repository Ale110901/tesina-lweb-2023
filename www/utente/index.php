<?php
require_once('../config.php');

$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

require_once(RC_ROOT . '/lib/start.php');
require_once(RC_ROOT . '/lib/utils.php');

switch ($_SESSION['tipo_utente']) {
  case 'admin':
    $home_page = '/admin/index.php';
    break;
  case 'cliente':
    $home_page = '/cliente/profilo.php';
    break;
  case 'gestore':
    $home_page = '/gestore/index.php';
    break;
  default:
    $home_page = '/index.php';
    break;
}

redirect(307, $home_page, false);
?>
