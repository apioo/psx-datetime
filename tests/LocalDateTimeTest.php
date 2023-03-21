<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2022 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace PSX\DateTime\Tests;

use PHPUnit\Framework\TestCase;
use PSX\DateTime\DateTime;
use PSX\DateTime\Exception\InvalidFormatException;
use PSX\DateTime\LocalDateTime;
use PSX\DateTime\Month;

/**
 * DateTimeTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class LocalDateTimeTest extends TestCase
{
    public function testDateTime()
    {
        $date = LocalDateTime::parse('2015-04-25T19:35:20');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(Month::APRIL, $date->getMonth());
        $this->assertEquals(4, $date->getMonthValue());
        $this->assertEquals(25, $date->getDayOfMonth());
        $this->assertEquals(19, $date->getHour());
        $this->assertEquals(35, $date->getMinute());
        $this->assertEquals(20, $date->getSecond());
        $this->assertEquals(0, $date->getNano());
        $this->assertEquals('2015-04-25T19:35:20Z', $date->toString());
        $this->assertEquals('"2015-04-25T19:35:20Z"', \json_encode($date));
    }

    public function testDateTimeMicroSeconds()
    {
        $date = LocalDateTime::parse('2015-04-25T19:35:20.1234');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(Month::APRIL, $date->getMonth());
        $this->assertEquals(4, $date->getMonthValue());
        $this->assertEquals(25, $date->getDayOfMonth());
        $this->assertEquals(19, $date->getHour());
        $this->assertEquals(35, $date->getMinute());
        $this->assertEquals(20, $date->getSecond());
        $this->assertEquals(123400, $date->getNano());
        $this->assertEquals('2015-04-25T19:35:20Z', $date->toString());
    }

    public function testDateTimeOffset()
    {
        $date = LocalDateTime::parse('2015-04-25T19:35:20+01:00');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(Month::APRIL, $date->getMonth());
        $this->assertEquals(4, $date->getMonthValue());
        $this->assertEquals(25, $date->getDayOfMonth());
        $this->assertEquals(19, $date->getHour());
        $this->assertEquals(35, $date->getMinute());
        $this->assertEquals(20, $date->getSecond());
        $this->assertEquals(0, $date->getNano());
        $this->assertEquals('2015-04-25T19:35:20Z', $date->toString());
    }

    public function testDateTimeMicroSecondsAndOffset()
    {
        $date = LocalDateTime::parse('2015-04-25T19:35:20.1234+01:00');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(Month::APRIL, $date->getMonth());
        $this->assertEquals(4, $date->getMonthValue());
        $this->assertEquals(25, $date->getDayOfMonth());
        $this->assertEquals(19, $date->getHour());
        $this->assertEquals(35, $date->getMinute());
        $this->assertEquals(20, $date->getSecond());
        $this->assertEquals(123400, $date->getNano());
        $this->assertEquals('2015-04-25T19:35:20Z', $date->toString());
    }

    /**
     * @dataProvider providerRfc
     */
    public function testRfcExamples($data, $expected)
    {
        $date = LocalDateTime::parse($data);

        $this->assertEquals($expected, $date->toString());
    }

    public function providerRfc()
    {
        return [
            ['1985-04-12T23:20:50.52Z', '1985-04-12T23:20:50Z'],
            ['1996-12-19T16:39:57-08:00', '1996-12-19T16:39:57Z'],
            ['1937-01-01T12:00:27.87+00:20', '1937-01-01T12:00:27Z'],
        ];
    }

    public function testConstructorFull()
    {
        $date = LocalDateTime::of(2014, 1, 1, 13, 37, 12);

        $this->assertEquals('2014-01-01T13:37:12Z', $date->toString());
    }

    public function testToString()
    {
        $date = LocalDateTime::of(2014, 1, 1, 13, 37, 12);

        $this->assertEquals('2014-01-01T13:37:12Z', (string) $date);
    }

    public function testDateTimeNow()
    {
        $date = LocalDateTime::now();

        $this->assertEquals(date('Y-m-d\TH:i:s\Z'), $date->toString());
    }

    public function testDateTimeEmpty()
    {
        $this->expectException(InvalidFormatException::class);

        LocalDateTime::parse('');
    }

    public function testDateTimeInvalid()
    {
        $this->expectException(InvalidFormatException::class);

        LocalDateTime::parse('foo');
    }

    public function testDateTimeInvalidOffset()
    {
        $this->expectException(InvalidFormatException::class);

        LocalDateTime::parse('2015-04-25T19:35:20+50:00');
    }

    public function testMysqlDateTimeFormat()
    {
        $date = LocalDateTime::parse('2015-04-25 19:35:20');

        $this->assertEquals('2015-04-25T19:35:20Z', $date->toString());
    }

    public function testFromDateTime()
    {
        $date = LocalDateTime::from(new \DateTime('2015-04-25T19:35:20'));

        $this->assertEquals('2015-04-25T19:35:20Z', $date->toString());
    }
}
