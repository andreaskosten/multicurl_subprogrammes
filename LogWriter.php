<?php


class LogWriter
{
    public static function writeLog(string $text):void
    {
        file_put_contents('multicurl_demo_log.txt', $text . PHP_EOL, FILE_APPEND);
    }
}
