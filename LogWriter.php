<?php

class LogWriter {
    
    public static function write_in_log($new_text){
        
        $file = 'multicurl_demo_log.txt';
        
        $new_text = file_get_contents($file) . "\n" . $new_text;
        
        file_put_contents($file, $new_text);
    }
}
