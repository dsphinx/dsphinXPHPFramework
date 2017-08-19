<?php
/**
 * difF PHP Framework :: Timer.php
 *
 * @version: 0.2
 * @date   : 10/07/2012
 * @author Melisides Constantinos (dsphinx@gmail.com)
            @author Apazidis kostas konpaz@gmail.com
 * @Description:   index / startup file for difF PHP Framework
 *
 *
 * Licensed under MIT licence:
 *   http://www.opensource.org/licenses/mit-license.php
 **/


class ExecTimer
{
    private static $starttime = "", $endtime = "", $timeparts = "";

    static function  Start()
    {
        self::reStart();
    }

    static function reStart()
    {
        self::$timeparts = explode(' ', microtime());
        self::$starttime = self::$timeparts[1] . substr(self::$timeparts[0], 1);
    }

    static function catchTime()
    {
        self::$timeparts = explode(' ', microtime());
        self::$endtime = self::$timeparts[1] . substr(self::$timeparts[0], 1);
    }

    static function theTime()
    {
        self::catchTime();

        return bcsub(self::$endtime, self::$starttime, 6);
    }

}
