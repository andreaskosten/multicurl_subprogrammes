<?php


class LogWriter
{
	public static function writeLog($text)
	{
		file_put_contents('multicurl_demo_log.txt', $text . PHP_EOL, FILE_APPEND);
	}
}
