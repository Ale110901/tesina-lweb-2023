<?php
if (isset($_GET['subdir'])) {

  $subdir = rtrim($_GET['subdir'], '/');
} else {
  $subdir = '';
}

file_put_contents('config.php',
  "<?php\n" .
  "const RC_ROOT = __DIR__;\n" .
  "const RC_SUBDIR = '$subdir';\n" .
  "?>"
);

echo ("Installazione completata\n");
?>
