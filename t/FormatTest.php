<?php

class FormatTest extends PHPUnit_Framework_TestCase
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
}

