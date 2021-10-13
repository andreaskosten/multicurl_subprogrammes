<?php

// autoloading:
spl_autoload_register(
    function ($className) {
        include_once $className . '.php';
    }
);


$timeStart = microtime(true);

$start = (int) $_POST['start'];
$end = (int) $_POST['end'];
$number = trim($_POST['number']);

// some "big task", assigned to this subprogramme:
$sum = 0;
for ($i = $start; $i < $end; $i++) {
    $sum += $i;
}

$time = microtime(true) - $timeStart;

$result = (string) $sum;
LogWriter::writeLog('thread #'.$number.', time: '.$time.' => '.$result);

exit($result);
