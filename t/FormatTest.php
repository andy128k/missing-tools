<?php

class FormatTest extends \PHPUnit\Framework\TestCase
{
    public function testFlags()
    {
        $this->assertEquals(\PFF\DateFormat::DATE_DEFAULT,  \PFF\DateFormat::DATE_BIG_ENDIAN    | \PFF\DateFormat::DATE_HYPHENS);
        $this->assertEquals(\PFF\DateFormat::DATE_US,       \PFF\DateFormat::DATE_MIDDLE_ENDIAN | \PFF\DateFormat::DATE_SLASHES);
        $this->assertEquals(\PFF\DateFormat::DATE_EUROPEAN, \PFF\DateFormat::DATE_LITTLE_ENDIAN | \PFF\DateFormat::DATE_DOTS);
    }

    public function testDateFlagsFromString()
    {
        $this->assertEquals(\PFF\DateFormat::DATE_DEFAULT,  \PFF\DateFormat::dateFlagsFromString('Default'));
        $this->assertEquals(\PFF\DateFormat::DATE_DEFAULT,  \PFF\DateFormat::dateFlagsFromString('Big-endian hyphEns'));
        $this->assertEquals(\PFF\DateFormat::DATE_EUROPEAN, \PFF\DateFormat::dateFlagsFromString('European'));
        $this->assertEquals(\PFF\DateFormat::DATE_EUROPEAN, \PFF\DateFormat::dateFlagsFromString('little-endian dots'));
        $this->assertEquals(\PFF\DateFormat::DATE_US,       \PFF\DateFormat::dateFlagsFromString('US'));
        $this->assertEquals(\PFF\DateFormat::DATE_US,       \PFF\DateFormat::dateFlagsFromString('Middle-Endian Slashes'));
        $this->assertEquals(\PFF\DateFormat::DATE_BIG_ENDIAN | \PFF\DateFormat::DATE_SPACES,
                                                               \PFF\DateFormat::dateFlagsFromString('big-endian spaces'));
    }

    public function testDatetimeFlagsFromString()
    {
        $this->assertEquals(\PFF\DateFormat::DATETIME_DEFAULT,  \PFF\DateFormat::datetimeFlagsFromString('Default'));
        $this->assertEquals(\PFF\DateFormat::DATETIME_DEFAULT,  \PFF\DateFormat::datetimeFlagsFromString('Big-endian hyphEns 24'));
        $this->assertEquals(\PFF\DateFormat::DATETIME_EUROPEAN, \PFF\DateFormat::datetimeFlagsFromString('European'));
        $this->assertEquals(\PFF\DateFormat::DATETIME_EUROPEAN, \PFF\DateFormat::datetimeFlagsFromString('little-endian dots 24'));
        $this->assertEquals(\PFF\DateFormat::DATETIME_US,       \PFF\DateFormat::datetimeFlagsFromString('US'));
        $this->assertEquals(\PFF\DateFormat::DATETIME_US,       \PFF\DateFormat::datetimeFlagsFromString('Middle-Endian Slashes 12-upper'));
        $this->assertEquals(\PFF\DateFormat::DATE_BIG_ENDIAN | \PFF\DateFormat::DATE_SPACES | \PFF\DateFormat::TIME_12HOURS_LOWER | \PFF\DateFormat::TIME_SECONDS,
                                                               \PFF\DateFormat::datetimeFlagsFromString("big-endian spaces \n 12-lower Seconds"));
    }

    public function testMakeFormat()
    {
        $flags = \PFF\DateFormat::DATETIME_DEFAULT;
        $this->assertEquals('%Y-%m-%d %H:%M', \PFF\DateFormat::makeFormat($flags, \PFF\DateFormat::$strftimeControls));
        $this->assertEquals('Y-m-d H:i', \PFF\DateFormat::makeFormat($flags, \PFF\DateFormat::$dateControls));
        $this->assertEquals('yy-mm-dd HH:mm', \PFF\DateFormat::makeFormat($flags, \PFF\DateFormat::$pickerControls));

        $flags2 = \PFF\DateFormat::DATETIME_US;
        $this->assertEquals('%m/%d/%Y %l:%M %p', \PFF\DateFormat::makeFormat($flags2, \PFF\DateFormat::$strftimeControls));
        $this->assertEquals('m/d/Y h:i A', \PFF\DateFormat::makeFormat($flags2, \PFF\DateFormat::$dateControls));
        $this->assertEquals('mm/dd/yy hh:mm TT', \PFF\DateFormat::makeFormat($flags2, \PFF\DateFormat::$pickerControls));

        $flags3 = \PFF\DateFormat::DATETIME_EUROPEAN;
        $this->assertEquals('%d.%m.%Y %H:%M', \PFF\DateFormat::makeFormat($flags3, \PFF\DateFormat::$strftimeControls));
        $this->assertEquals('d.m.Y H:i', \PFF\DateFormat::makeFormat($flags3, \PFF\DateFormat::$dateControls));
        $this->assertEquals('dd.mm.yy HH:mm', \PFF\DateFormat::makeFormat($flags3, \PFF\DateFormat::$pickerControls));

        $flags4 = \PFF\DateFormat::DATE_US | \PFF\DateFormat::TIME_12HOURS_LOWER;
        $this->assertEquals('%m/%d/%Y %l:%M %P', \PFF\DateFormat::makeFormat($flags4, \PFF\DateFormat::$strftimeControls));
        $this->assertEquals('m/d/Y h:i a', \PFF\DateFormat::makeFormat($flags4, \PFF\DateFormat::$dateControls));

        $flags5 = \PFF\DateFormat::DATE_BIG_ENDIAN | \PFF\DateFormat::TIME_24HOURS | \PFF\DateFormat::TIME_SECONDS | \PFF\DateFormat::DATE_SPACES;
        $this->assertEquals('Y m d H:i:s', \PFF\DateFormat::makeFormat($flags5, \PFF\DateFormat::$dateControls));

        $flags6 = \PFF\DateFormat::DATE_BIG_ENDIAN | \PFF\DateFormat::TIME_12HOURS_LOWER | \PFF\DateFormat::TIME_SECONDS | \PFF\DateFormat::DATE_SLASHES;
        $this->assertEquals('Y/m/d h:i:s a', \PFF\DateFormat::makeFormat($flags6, \PFF\DateFormat::$dateControls));

        $flags7 = \PFF\DateFormat::DATE_BIG_ENDIAN | \PFF\DateFormat::TIME_12HOURS_UPPER | \PFF\DateFormat::TIME_SECONDS | \PFF\DateFormat::DATE_SLASHES;
        $this->assertEquals('Y/m/d h:i:s A', \PFF\DateFormat::makeFormat($flags7, \PFF\DateFormat::$dateControls));
    }

    public function testMakeFormatWOTime()
    {
        $flags = \PFF\DateFormat::DATE_DEFAULT;
        $this->assertEquals(
            \PFF\DateFormat::makeFormat($flags, \PFF\DateFormat::$strftimeControls),
            \PFF\DateFormat::makeDateFormat($flags, \PFF\DateFormat::$strftimeControls)
        );
    }

    public function testImpossibleDate()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Bad/');
        \PFF\DateFormat::makeFormat(\PFF\DateFormat::DATE_LITTLE_ENDIAN | \PFF\DateFormat::DATE_MIDDLE_ENDIAN, \PFF\DateFormat::$dateControls);
    }

    public function testImpossibleTime()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageMatches('/Bad/');
        \PFF\DateFormat::makeTimeFormat(\PFF\DateFormat::TIME_SECONDS, \PFF\DateFormat::$dateControls);
    }

    public function testParse()
    {
        $date = \PFF\DateFormat::parseDate(\PFF\DateFormat::DATE_US, '05/01/2018');
        $this->assertEquals(118, $date['tm_year']);
        $this->assertEquals(4, $date['tm_mon']);
        $this->assertEquals(1, $date['tm_mday']);
    }

    public function testFormat()
    {
        $str = \PFF\DateFormat::formatDate(\PFF\DateFormat::DATE_DEFAULT, 1525132800);
        $this->assertEquals('2018-05-01', $str);
    }
}
