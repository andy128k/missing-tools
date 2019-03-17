<?php

namespace PFF;

use \Pegp\Pegp as Pegp;

final class DateFormat
{
    const DATE_BIG_ENDIAN     =  0;
    const DATE_LITTLE_ENDIAN  =  4;
    const DATE_MIDDLE_ENDIAN  =  8;

    const DATE_SLASHES        =  0;
    const DATE_DOTS           =  1;
    const DATE_HYPHENS        =  2;
    const DATE_SPACES         =  3;

    const TIME_24HOURS        = 16;
    const TIME_12HOURS        = 32;
    const TIME_12HOURS_UPPER  = 32;
    const TIME_12HOURS_LOWER  = 48;

    const TIME_SECONDS        = 64;

    const DATE_DEFAULT        =  2; // DATE_BIG_ENDIAN    | DATE_HYPHENS
    const DATE_US             =  8; // DATE_MIDDLE_ENDIAN | DATE_SLASHES
    const DATE_EUROPEAN       =  5; // DATE_LITTLE_ENDIAN | DATE_DOTS

    const DATETIME_DEFAULT    = 18; // DATE_DEFAULT  | TIME_24HOURS
    const DATETIME_US         = 40; // DATE_US       | TIME_12HOURS
    const DATETIME_EUROPEAN   = 21; // DATE_EUROPEAN | TIME_24HOURS

    public static $strftimeControls, $dateControls, $pickerControls;

    private static $dateSeparators;

    public static function init()
    {
        self::$dateSeparators = array('/', '.', '-', ' ');

        self::$strftimeControls = array('%Y', '%m', '%d', '%H', '%l', '%p', '%P', '%M', '%S');
        self::$dateControls     = array( 'Y',  'm',  'd',  'H',  'h',  'A',  'a',  'i',  's');
        self::$pickerControls   = array('yy', 'mm', 'dd', 'HH', 'hh', 'TT', 'tt', 'mm', 'ss');
    }

    public static function dateFlagsFromString($str)
    {
        return
            Pegp::oneOf(
                Pegp::stri('default')->value(self::DATE_DEFAULT),
                Pegp::stri('us')->value(self::DATE_US),
                Pegp::stri('european')->value(self::DATE_EUROPEAN),
                Pegp::seq(
                    Pegp::optional(
                        Pegp::seq(
                            Pegp::oneOf(
                                Pegp::stri('big-endian')->value(self::DATE_BIG_ENDIAN),
                                Pegp::stri('little-endian')->value(self::DATE_LITTLE_ENDIAN),
                                Pegp::stri('middle-endian')->value(self::DATE_MIDDLE_ENDIAN)),
                            Pegp::re('\s+')->drop())->bitOr(), 0),
                    Pegp::oneOf(
                        Pegp::stri('slashes')->value(self::DATE_SLASHES),
                        Pegp::stri('dots')->value(self::DATE_DOTS),
                        Pegp::stri('hyphens')->value(self::DATE_HYPHENS),
                        Pegp::stri('spaces')->value(self::DATE_SPACES)))->bitOr())
        ->parseString($str);
    }

    public static function datetimeFlagsFromString($str)
    {
        return
            Pegp::oneOf(
                Pegp::stri('default')->value(self::DATETIME_DEFAULT),
                Pegp::stri('us')->value(self::DATETIME_US),
                Pegp::stri('european')->value(self::DATETIME_EUROPEAN),
                Pegp::seq(
                    Pegp::optional(
                        Pegp::seq(
                            Pegp::oneOf(
                                Pegp::stri('big-endian')->value(self::DATE_BIG_ENDIAN),
                                Pegp::stri('little-endian')->value(self::DATE_LITTLE_ENDIAN),
                                Pegp::stri('middle-endian')->value(self::DATE_MIDDLE_ENDIAN)),
                            Pegp::re('\s+')->drop())->bitOr(), 0),
                    Pegp::oneOf(
                        Pegp::stri('slashes')->value(self::DATE_SLASHES),
                        Pegp::stri('dots')->value(self::DATE_DOTS),
                        Pegp::stri('hyphens')->value(self::DATE_HYPHENS),
                        Pegp::stri('spaces')->value(self::DATE_SPACES)),
                    Pegp::re('\s+')->drop(),
                    Pegp::oneOf(
                        Pegp::stri('24')->value(self::TIME_24HOURS),
                        Pegp::stri('12-upper')->value(self::TIME_12HOURS_UPPER),
                        Pegp::stri('12-lower')->value(self::TIME_12HOURS_LOWER),
                        Pegp::stri('12')->value(self::TIME_12HOURS)),
                    Pegp::optional(
                        Pegp::seq(
                            Pegp::re('\s+')->drop(),
                            Pegp::stri('seconds'))->value(self::TIME_SECONDS), 0))->bitOr())
        ->parseString($str);
    }

    /**
     * Builds format string corresponding to given flags and controls.
     *
     * @param	integer Format flags
     * @param	array Format values.
     *    Examples:
     *      array(
     *        'Y', // year
     *        'm', // month
     *        'd', // day
     *      )
     *      array(
     *        '%Y',                   // year
     *        '%m',                   // month
     *        '%d',                   // day
     *        '%H', '%l', '%p', '%P', // hour-24, hour-12, AM/PM, am/pm
     *        '%M',                   // minute
     *        '%S',                   // second
     *      )
     * @return string Format string
     */
    public static function makeFormat($flags, $controls)
    {
        $date = self::makeDateFormat($flags, $controls);
        $time = self::makeTimeFormat($flags, $controls);
        if ($time)
            return $date . ' ' . $time;
        else
            return $date;
    }

    public static function makeDateFormat($flags, $controls)
    {
        $separator = self::$dateSeparators[$flags % 4];
        switch ($flags & 12) {
            case self::DATE_BIG_ENDIAN:
                return $controls[0].$separator.$controls[1].$separator.$controls[2];
            case self::DATE_LITTLE_ENDIAN:
                return $controls[2].$separator.$controls[1].$separator.$controls[0];
            case self::DATE_MIDDLE_ENDIAN:
                return $controls[1].$separator.$controls[2].$separator.$controls[0];
            default:
                throw new Exception('Bad date format');
        }
    }

    public static function makeTimeFormat($flags, $controls)
    {
        if (!($flags & 112)) // no time flags
            return '';

        $seconds = $flags & self::TIME_SECONDS;
        switch ($flags & 48) {
            case self::TIME_24HOURS:
                $result = $controls[3].':'.$controls[7];
                if ($seconds)
                    $result .= ':'.$controls[8];
                break;
            case self::TIME_12HOURS_UPPER:
                $result = $controls[4].':'.$controls[7];
                if ($seconds)
                    $result .= ':'.$controls[8];
                $result .= ' '.$controls[5];
                break;
            case self::TIME_12HOURS_LOWER:
                $result = $controls[4].':'.$controls[7];
                if ($seconds)
                    $result .= ':'.$controls[8];
                $result .= ' '.$controls[6];
                break;
            default:
                throw new \Exception('Bad time format');
        }
        return $result;
    }

    public static function parseDate($format, $str)
    {
        return strptime($str, self::makeFormat($format, self::$strftimeControls));
    }

    public static function formatDate($format, $timestamp)
    {
        return strftime(self::makeFormat($format, self::$strftimeControls), $timestamp);
    }
}

DateFormat::init();

