<?php
$perm_visitatore = false;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

$rc_level = 1;
require_once('../lib/start.php');

require_once($rc_root . '/lib/utils.php');

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

redirect(307, $rc_subdir . $home_page, false);
?>
