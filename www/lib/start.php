<?php
session_start();

$sessione = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);

/*
if (!$sessione) {
  header("Location: /login.php?redirect=profilo.php");
  exit();
}
*/
?>
