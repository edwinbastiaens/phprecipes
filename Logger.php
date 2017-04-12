<?php

class Logger
{
    private static $filePath = "log.txt";
    private static $logTime = true;
    private static $logVisit = true;

    public static function setFilePath($newFilePath)
    {
        self::$filePath = $newFilePath;
    }

    public static function setTimeLogging($onoff = true)
    {
        self::$logTime = $onoff;
    }

    public static function setVisitLogging($onoff = true)
    {
        self::$logVisit = $onoff;
    }

    private static function visitData()
    {
        $ipaddr = $_SERVER['REMOTE_ADDR'];
        //--- get country from hostname!
        $hostname = gethostbyaddr($ipaddr);
        $hostname_slizes = explode('.', $hostname);
        $count_slizes = count($hostname_slizes);
        $piece = $count_slizes - 1;
        $land = $hostname_slizes[$piece];

        //-- define user agent
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        // "/name"  en "/name/123"  => name
        $completePageName = $_SERVER["REQUEST_URI"];
        $parr = explode("/",$completePageName);
        $page = $parr[1];
        return "ipaddr: " . $ipaddr ."\n"
                . "land: ". $land ."\n"
                . "hostname: " . $hostname ."\n"
                . "user agent: " . $user_agent ."\n"
                . "pagename: " . $completePageName ."\n"
                . "page: " . $page ."\n";
    }

    public static function log($txt="")
    {
        $line = ($txt == "")? "\n" : "msg: " . $txt .  "\n\r";
        if (self::$logVisit){
            $line = self::visitData() . $line;
        }
        if (self::$logTime){
            $line = date("Y-m-d h:m:s:i") . "\n" . $line;
        }

        $fileNr = file_put_contents(
            self::$filePath,
            $line,
            FILE_APPEND | LOCK_EX
        );
    }
}
