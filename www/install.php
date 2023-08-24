<?php
header('Content-Type: text/plain');

if (isset($_GET['subdir'])) {
  $subdir = rtrim($_GET['subdir'], '/');
} else {
  $subdir = '';
}

file_put_contents('config.php',
  "<?php\n" .
  "\$rc_root = __DIR__;\n" .
  "\$rc_subdir = '$subdir';\n" .
  "?>"
);

echo ("Installazione completata\n");
?>
