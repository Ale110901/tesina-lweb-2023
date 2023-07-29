<?php
require_once('../lib/auth.php');

header('Content-Type: text/plain');

$email = "mario.rossi@gmail.com";
$password = "password";
access_verification($email, $password);
?>
