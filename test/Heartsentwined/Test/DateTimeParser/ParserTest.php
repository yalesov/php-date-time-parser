<?php
namespace Heartsentwined\Test\DateTimeParser;

use Heartsentwined\DateTimeParser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->dateTimeTests = array(
            array(
                'expr' => '19961022T140000',
                'result' => array(
                    'year'      => '1996',
                    'month'     => '10',
                    'day'       => '22',
                    'hour'      => '14',
                    'minute'    => '00',
                    'second'    => '00',
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => '--1022T1400',
                'result' => array(
                    'year'      => null,
                    'month'     => '10',
                    'day'       => '22',
                    'hour'      => '14',
                    'minute'    => '00',
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => '---22T14',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => '22',
                    'hour'      => '14',
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
        );
        $this->dateTests = array(
            array(
                'expr' => '19850412',
                'result' => array(
                    'year'      => '1985',
                    'month'     => '04',
                    'day'       => '12',
                    'hour'      => null,
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => '1985-04',
                'result' => array(
                    'year'      => '1985',
                    'month'     => '04',
                    'day'       => null,
                    'hour'      => null,
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => '1985',
                'result' => array(
                    'year'      => '1985',
                    'month'     => null,
                    'day'       => null,
                    'hour'      => null,
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => '--0412',
                'result' => array(
                    'year'      => null,
                    'month'     => '04',
                    'day'       => '12',
                    'hour'      => null,
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => '---12',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => '12',
                    'hour'      => null,
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
        );
        $this->timeTests = array(
            array(
                'expr' => 'T102200',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => '10',
                    'minute'    => '22',
                    'second'    => '00',
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => 'T1022',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => '10',
                    'minute'    => '22',
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => 'T10',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => '10',
                    'minute'    => null,
                    'second'    => null,
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => 'T-2200',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => null,
                    'minute'    => '22',
                    'second'    => '00',
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => 'T--00',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => null,
                    'minute'    => null,
                    'second'    => '00',
                    'timezone'  => null,
                ),
            ),
            array(
                'expr' => 'T102200Z',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => '10',
                    'minute'    => '22',
                    'second'    => '00',
                    'timezone'  => '+0000',
                ),
            ),
            array(
                'expr' => 'T102200-0800',
                'result' => array(
                    'year'      => null,
                    'month'     => null,
                    'day'       => null,
                    'hour'      => '10',
                    'minute'    => '22',
                    'second'    => '00',
                    'timezone'  => '-0800',
                ),
            ),
        );
    }

    public function testParseDateTime()
    {
        foreach (array_merge(
            $this->dateTimeTests, $this->dateTests, $this->timeTests)
            as $test) {
            $this->assertSame($test['result'],
                Parser::parseDateTime($test['expr']));
        }
    }

    public function testParseDate()
    {
        foreach ($this->dateTests as $test) {
            $this->assertSame($test['result'],
                Parser::parseDate($test['expr']));
        }
    }

    public function testParseTime()
    {
        foreach ($this->timeTests as $test) {
            $this->assertSame($test['result'],
                Parser::parseTime($test['expr']));
            // test 'T' stripped
            $this->assertSame($test['result'],
                Parser::parseTime(substr($test['expr'], 1)));
        }
    }

    public function testCreateDateTime()
    {
        foreach (array_merge(
            $this->dateTimeTests, $this->dateTests, $this->timeTests)
            as $test) {
                $this->assertSame($test['expr'], Parser::createDateTime(
                    $test['result']['year'],
                    $test['result']['month'],
                    $test['result']['day'],
                    $test['result']['hour'],
                    $test['result']['minute'],
                    $test['result']['second'],
                    $test['result']['timezone']
                ));
        }
    }

    public function testCreateDate()
    {
        foreach ($this->dateTests as $test) {
            $this->assertSame($test['expr'], Parser::createDate(
                $test['result']['year'],
                $test['result']['month'],
                $test['result']['day']
            ));
        }
    }

    public function testCreateTime()
    {
        foreach ($this->timeTests as $test) {
            $this->assertSame($test['expr'], Parser::createTime(
                $test['result']['hour'],
                $test['result']['minute'],
                $test['result']['second'],
                $test['result']['timezone']
            ));
        }
    }

    public function testCreateTimestamp()
    {
        $this->assertSame(null, Parser::createTimestamp(
            null, null, null, null, null, null, null));
        $this->assertSame(null, Parser::createTimestamp(
            null, null, null, '01', '01', '01', null));
        $this->assertSame(null, Parser::createTimestamp(
            null, '01', '01', '01', '01', '01', null));
        $this->assertSame(null, Parser::createTimestamp(
            '1901', null, '01', '01', '01', '01', null));
        $this->assertSame(null, Parser::createTimestamp(
            '1901', '01', null, '01', '01', '01', null));

        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', '1901-01-01 01:01:01');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', '01', '01', null));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', '1901-01-01 00:00:00');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', null, null, null, null));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', '1901-01-01 00:01:01');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', null, '01', '01', null));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', '1901-01-01 01:00:01');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', null, '01', null));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', '1901-01-01 01:01:00');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', '01', null, null));

        $dt = \DateTime::createFromFormat('Y-m-d H:i:sT', '1901-01-01 01:01:01+0000');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', '01', '01', '+0000'));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:sT', '1901-01-01 01:01:01-0000');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', '01', '01', '-0000'));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:sT', '1901-01-01 01:01:01+0100');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', '01', '01', '+0100'));
        $dt = \DateTime::createFromFormat('Y-m-d H:i:sT', '1901-01-01 01:01:01-0100');
        $this->assertSame($dt->getTimestamp(), Parser::createTimestamp(
            '1901', '01', '01', '01', '01', '01', '-0100'));
    }

    public function testValidateDateTime()
    {
        $this->assertTrue(Parser::validateDateTime(
            1901, 1, 1, 1, 1, 1));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, null, null, null));

        $this->assertTrue(Parser::validateDateTime(
            1901, 1, 1, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            1901, 1, null, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            1901, 12, null, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, null, 1, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, null, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 1, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 2, 29, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 3, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 4, 30, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 5, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 6, 30, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 7, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 8, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 9, 30, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 10, 31, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 11, 30, null, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, 12, 31, null, null, null));

        $this->assertTrue(Parser::validateDateTime(
            null, null, null, 1, 1, 1));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, 0, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, 23, null, null));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, null, 0, null));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, null, 59, null));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, null, null, 0));
        $this->assertTrue(Parser::validateDateTime(
            null, null, null, null, null, 59));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMinMonth()
    {
        Parser::validateDateTime(1901, 0, null, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMaxMonth()
    {
        Parser::validateDateTime(1901, 13, null, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMinDay()
    {
        Parser::validateDateTime(null, null, 0, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMaxDay()
    {
        Parser::validateDateTime(null, null, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeJan()
    {
        Parser::validateDateTime(null, 1, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeFeb()
    {
        Parser::validateDateTime(null, 2, 30, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMar()
    {
        Parser::validateDateTime(null, 3, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeApr()
    {
        Parser::validateDateTime(null, 4, 31, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMay()
    {
        Parser::validateDateTime(null, 5, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeJun()
    {
        Parser::validateDateTime(null, 6, 31, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeJul()
    {
        Parser::validateDateTime(null, 7, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeAug()
    {
        Parser::validateDateTime(null, 8, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeSep()
    {
        Parser::validateDateTime(null, 9, 31, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeOct()
    {
        Parser::validateDateTime(null, 10, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeNov()
    {
        Parser::validateDateTime(null, 11, 31, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeDec()
    {
        Parser::validateDateTime(null, 12, 32, null, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMinHour()
    {
        Parser::validateDateTime(null, null, null, -1, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMaxHour()
    {
        Parser::validateDateTime(null, null, null, 24, null, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMinMinute()
    {
        Parser::validateDateTime(null, null, null, null, -1, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMaxMinute()
    {
        Parser::validateDateTime(null, null, null, null, 60, null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMinSecond()
    {
        Parser::validateDateTime(null, null, null, null, null, -1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateDateTimeMaxSecond()
    {
        Parser::validateDateTime(null, null, null, null, null, 60);
    }
}
