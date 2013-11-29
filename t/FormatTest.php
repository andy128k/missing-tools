<?php

class Format_Test extends PHPUnit_Framework_TestCase
{
    public function testFlags()
    {
        $this->assertEquals(DateFormat::DATE_DEFAULT,  DateFormat::DATE_BIG_ENDIAN    | DateFormat::DATE_HYPHENS);
        $this->assertEquals(DateFormat::DATE_US,       DateFormat::DATE_MIDDLE_ENDIAN | DateFormat::DATE_SLASHES);
        $this->assertEquals(DateFormat::DATE_EUROPEAN, DateFormat::DATE_LITTLE_ENDIAN | DateFormat::DATE_DOTS);
    }

    public function testDateFlagsFromString()
    {
        $this->assertEquals(DateFormat::DATE_DEFAULT,  DateFormat::dateFlagsFromString('Default'));
        $this->assertEquals(DateFormat::DATE_DEFAULT,  DateFormat::dateFlagsFromString('Big-endian hyphEns'));
        $this->assertEquals(DateFormat::DATE_EUROPEAN, DateFormat::dateFlagsFromString('European'));
        $this->assertEquals(DateFormat::DATE_EUROPEAN, DateFormat::dateFlagsFromString('little-endian dots'));
        $this->assertEquals(DateFormat::DATE_US,       DateFormat::dateFlagsFromString('US'));
        $this->assertEquals(DateFormat::DATE_US,       DateFormat::dateFlagsFromString('Middle-Endian Slashes'));
        $this->assertEquals(DateFormat::DATE_BIG_ENDIAN | DateFormat::DATE_SPACES,
                                                       DateFormat::dateFlagsFromString('big-endian spaces'));
    }

    public function testDatetimeFlagsFromString()
    {
        $this->assertEquals(DateFormat::DATETIME_DEFAULT,  DateFormat::datetimeFlagsFromString('Default'));
        $this->assertEquals(DateFormat::DATETIME_DEFAULT,  DateFormat::datetimeFlagsFromString('Big-endian hyphEns 24'));
        $this->assertEquals(DateFormat::DATETIME_EUROPEAN, DateFormat::datetimeFlagsFromString('European'));
        $this->assertEquals(DateFormat::DATETIME_EUROPEAN, DateFormat::datetimeFlagsFromString('little-endian dots 24'));
        $this->assertEquals(DateFormat::DATETIME_US,       DateFormat::datetimeFlagsFromString('US'));
        $this->assertEquals(DateFormat::DATETIME_US,       DateFormat::datetimeFlagsFromString('Middle-Endian Slashes 12-upper'));
        $this->assertEquals(DateFormat::DATE_BIG_ENDIAN | DateFormat::DATE_SPACES | DateFormat::TIME_12HOURS_LOWER | DateFormat::TIME_SECONDS,
                                                           DateFormat::datetimeFlagsFromString("big-endian spaces \n 12-lower Seconds"));
    }
}

