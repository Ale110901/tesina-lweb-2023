<?php
function rand_date() {
  $year = rand(2017, 2023);
  $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
  $day = str_pad(rand(1, 30), 2, '0', STR_PAD_LEFT);

  $result = $year . '-' . $month . '-' . $day;

  return $result;
}

function rand_datetime() {
  $res_date = rand_date();

  $hour = str_pad(rand(0, 23), 2, '0', STR_PAD_LEFT);
  $minute = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);
  $second = str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT);

  $res_time = $hour . ':' . $minute . ':' . $second;

  $result = $res_date . 'T' . $res_time;

  return $result;
}
?>
