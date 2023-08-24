<?php
$perm_visitatore = true;
$perm_cliente = true;
$perm_gestore = true;
$perm_admin = true;

$rc_level = 0;
require_once('./lib/start.php');
?>
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
<head>
  <title>Informazioni &ndash; R&amp;C store</title>

  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/header.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/footer.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo($rc_subdir); ?>/res/css/info.css" />
</head>

<body>
  <?php require($rc_root . '/lib/header.php'); ?>
  <div id="contenuto">
    <h2>BENVENUTI!</h2>
    <div class="centrato">
      <a href="https://www.google.com/maps/place/data=!3m1!4b1!4m6!3m5!1s0x13250c86391d8acf:0xd1f1f251e4767ece!8m2!3d41.4705468!4d12.9081403!16s%2Fg%2F1pp2vn0lg"><img class="scatola" src="<?php echo($rc_subdir); ?>/res/img/maps.png" alt="Google Maps"></img></a>
      <p class="pt-1em">
        <img src="<?php echo($rc_subdir); ?>/res/img/location.png" alt="i"></img>
        Via XXIV Maggio, 7, 04100 Latina LT
      </p>
    </div>

    <div id="griglia-info" class="pb-32">
      <div>
        <h3>Contatti</h3>
        <p>
          cecchetto.1941039@studenti.uniroma1.it  <br />
          roccia.1967318@studenti.uniroma1.it
        </p>
      </div>
      <div>
        <table class="scatola">
          <tbody>
            <tr>
              <th>Giorni</th>
              <th>Orari</th>
            </tr>
            <tr>
              <td>Luned&igrave;</td>
              <td>06:00 - 22:00</td>
            </tr>
            <tr>
              <td>Marted&igrave;</td>
              <td>06:00 - 22:00</td>
            </tr>
            <tr>
              <td>Mercoled&igrave;</td>
              <td>06:00 - 22:00</td>
            </tr>
            <tr>
              <td>Gioved&igrave;</td>
              <td>06:00 - 22:00</td>
            </tr>
            <tr>
              <td>Venerd&igrave;</td>
              <td>06:00 - 22:00</td>
            </tr>
            <tr>
              <td>Sabato</td>
              <td>06:00 - 20:00</td>
            </tr>
            <tr>
              <td>Domenica</td>
              <td>08:00 - 16:00</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php require($rc_root . '/lib/footer.php'); ?>
</body>

</html>
