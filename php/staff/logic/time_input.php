<?php
//今日の日付取得
$unixTime = time();
$timeZone = new \DateTimeZone('Asia/Tokyo');

$time = new \DateTime();
$time->setTimestamp($unixTime)->setTimezone($timeZone);

$created_at = $time->format('Y/m/d H:i:s');
$year = $time->format('Y');
$month = $time->format('m');
$day = $time->format('d');
$n_time = $time->format('H');
$n_minutes = $time->format('i');

?>