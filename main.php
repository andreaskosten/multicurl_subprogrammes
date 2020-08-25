<?php

/**
 * There is code of "main" program, where some "big task" can be completed
 * in 2 different ways with different time complexities:
 * 1) "single" mode - complete whole task in sequence;
 * 2) "multi" mode - divide task between parallel autonomous subprogrammes.
 * 
 * Also, you can launch "compare_both_modes" mode, and program will perform
 * both modes and compare their time perfomance.
 * 
 */


// autoload:
spl_autoload_register(
    function ($class_name) {
        include_once $class_name . '.php';
    }
);


// mode switcher (single | multi | compare_both_modes):
$MODE = 'compare_both_modes';
if( isset($_GET['mode']) ){
    $MODE = $_GET['mode'];
}


if( !in_array($MODE, array('single', 'multi', 'compare_both_modes')) ){
    exit('mode is not detected!');
}


// path to subprogramme file must be provided here:
$PATH_TO_SUBPROGRAMME = 'https://my_domain/my_folder/subprogramme.php';
$PATH_TO_SUBPROGRAMME = 'https://severqs.com.ua/xTests_of_php/multicurl_2/task.php';


// "single" mode - complete whole task in sequence:
if( $MODE == 'single' || $MODE == 'compare_both_modes' ){
    
    $time_start = microtime(true);
    
    // some "big task":
    $sum = 0;
    for( $i = 1; $i < 120000000; $i++ ){
        
        $sum += $i;
    }
    
    echo '<br>"single" algorithm result = '.$sum.'<br>';
    
    $time_single = microtime(true) - $time_start;
}



// "multi" mode - when task is divided between subprogrammes:
if( $MODE == 'multi' || $MODE == 'compare_both_modes' ){
    
    $time_start = microtime(true);
    
    // let's manually divide this "big task":
    $start = array(0, 30000000, 60000000, 90000000);
    $end = array(30000000, 60000000, 90000000, 120000000);
    $ch_array = array();
    
    // let's create 4 subprogrammes:
    $SUBPROGRAMMES = 4;
    
    $log = "\n".date('Y.m.d H:i:s').': beginning '.$SUBPROGRAMMES.' subprogrammes...';
    LogWriter::write_in_log($log);
    
    // build the multi-curl handle:
    $mh = curl_multi_init();
    
    for( $i = 0; $i < $SUBPROGRAMMES; $i++ ){
        
        $post_data = array('start' => $start[$i], 'end' => $end[$i], 'number' => $i);
        
        // 1 - build the individual requests, but do not execute them:
        $ch = curl_init($PATH_TO_SUBPROGRAMME);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        
        array_push($ch_array, $ch);
        
        // 2 - add $ch to the multi-curl handle:
        curl_multi_add_handle($mh, $ch);
    }
    
    
    // 3 - execute all queries simultaneously, and continue when all are complete:
    $running = null;
    do {
        curl_multi_exec($mh, $running);
    } while ($running);
    
    
    // 4 - close the handles:
    for( $i = 0; $i < $SUBPROGRAMMES; $i++ ){
        curl_multi_remove_handle($mh, $ch_array[$i]);
    }
    curl_multi_close($mh);
    
    
    // 5 - all of our requests are done, we can now access the results:
    $results = '';
    $sum = 0;
    for( $i = 0; $i < $SUBPROGRAMMES; $i++ ){
        $current = curl_multi_getcontent($ch_array[$i]);
        $results = $results.'<br>'.$current;
        $sum += (int) $current;
    }
    
    echo '<br>results from subprogrammes:'.$results;
    echo '<br><br>"multi" algorithm result = '.$sum;
    
    $time_multi = microtime(true) - $time_start;
    
    $log = 'total time: '.$time_multi;
    LogWriter::write_in_log($log);
}



// simple test:
if( $sum == 7199999940000000 ){
    echo '<br><br>result is ok';
}
else{
    echo '<br><br>result is wrong: '.$sum.' != 7199999940000000';
}



// perfomance evaluation:
if( $MODE == 'compare_both_modes' ){
    
    $time_difference = $time_single - $time_multi;
    
    if( $time_difference > 0 ){
        $verdict = '"multi" is faster than "single": '.$time_difference.' s.';
    }
    
    if( $time_difference < 0 ){
        $verdict = '"single" is faster than "multi": '.abs($time_difference).' s.';
    }
    
    if( $time_difference == 0 ){
        $verdict = 'What a miracle! "single" and "multi" took equal time: '.$time_difference.' s.';
    }
    
    echo '<br><br>$time_single = '.$time_single;
    echo '<br>$time_multi = '.$time_multi;
    echo '<br><br>time difference between algorithms:<br>'.$verdict;
}
else{
    echo "<br><br>time taken: $time s";
}

?>
