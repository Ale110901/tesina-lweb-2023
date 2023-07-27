<?php
require_once('xml.php');

session_start();
$sessione = isset($_SESSION['id_utente']) && !is_nan($_SESSION['id_utente']);
/* if (!$sessione) {
  header('Location: login.php');
} */

$email = "mario.rossi@gmail.com";
$password = "prova";
access_verification($email, $password);
?>
