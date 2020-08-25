<?php

// autoload:
spl_autoload_register(
    function ($class_name) {
        include_once $class_name . '.php';
    }
);


$time_start = microtime(true);

$start = (int) $_POST['start'];
$end = (int) $_POST['end'];
$number = trim($_POST['number']);

// some "big task":
$sum = 0;
for( $i = $start; $i < $end; $i++ ){
    
    $sum += $i;
}

$time = microtime(true) - $time_start;

$result = (string) $sum;
LogWriter::write_in_log('thread #'.$number.', time: '.$time.' => '.$result);

exit($result);

?>
