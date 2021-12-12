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
use PSX\DateTime\Time;

/**
 * TimeTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class TimeTest extends TestCase
{
    public function testTime()
    {
        $time = new Time('19:35:20');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(0, $time->getMicroSecond());
        $this->assertEquals(0, $time->getOffset());
        $this->assertInstanceOf('DateTimeZone', $time->getTimeZone());
        $this->assertEquals('19:35:20', $time->toString());
        $this->assertEquals('"19:35:20"', \json_encode($time));
    }

    public function testTimeOffset()
    {
        $time = new Time('19:35:20+01:00');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(0, $time->getMicroSecond());
        $this->assertEquals(3600, $time->getOffset());
        $this->assertInstanceOf('DateTimeZone', $time->getTimeZone());
        $this->assertEquals('19:35:20+01:00', $time->toString());
    }

    public function testTimeMicroSeconds()
    {
        $time = new Time('19:35:20.1234');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(123400, $time->getMicroSecond());
        $this->assertEquals(0, $time->getOffset());
        $this->assertInstanceOf('DateTimeZone', $time->getTimeZone());
        $this->assertEquals('19:35:20.123400', $time->toString());
    }

    public function testTimeMicroSecondsAndOffset()
    {
        $time = new Time('19:35:20.1234+01:00');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(123400, $time->getMicroSecond());
        $this->assertEquals(3600, $time->getOffset());
        $this->assertInstanceOf('DateTimeZone', $time->getTimeZone());
        $this->assertEquals('19:35:20.123400+01:00', $time->toString());
    }

    public function testConstructorFull()
    {
        $time = new Time(13, 37, 12);

        $this->assertEquals('13:37:12', $time->toString());
    }

    public function testToString()
    {
        $time = new Time(13, 37, 12);

        $this->assertEquals('13:37:12', (string) $time);
    }

    public function testTimeEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Time('');
    }

    public function testTimeInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Time('foo');
    }

    public function testTimeInvalidOffset()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Time('19:35:20+50:00');
    }

    public function testTimeInvalidMicroSeconds()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Time('19:35:20.foo');
    }

    public function testFromDateTime()
    {
        $time = Time::fromDateTime(new \DateTime('2015-04-25T19:35:20'));

        $this->assertEquals('19:35:20', $time->toString());
    }
}
