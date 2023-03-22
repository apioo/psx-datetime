<?php
/*
 * PSX is an open source PHP framework to develop RESTful APIs.
 * For the current version and information visit <https://phpsx.org>
 *
 * Copyright 2010-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
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
use PSX\DateTime\Exception\InvalidFormatException;
use PSX\DateTime\LocalTime;

/**
 * TimeTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class LocalTimeTest extends TestCase
{
    public function testTime()
    {
        $time = LocalTime::parse('19:35:20');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(0, $time->getNano());
        $this->assertEquals('19:35:20', $time->toString());
        $this->assertEquals('"19:35:20"', \json_encode($time));
    }

    public function testTimeOffset()
    {
        $time = LocalTime::parse('19:35:20+01:00');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(0, $time->getNano());
        $this->assertEquals('19:35:20', $time->toString());
    }

    public function testTimeMicroSeconds()
    {
        $time = LocalTime::parse('19:35:20.1234');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(123400, $time->getNano());
        $this->assertEquals('19:35:20', $time->toString());
    }

    public function testTimeMicroSecondsAndOffset()
    {
        $time = LocalTime::parse('19:35:20.1234+01:00');

        $this->assertEquals(19, $time->getHour());
        $this->assertEquals(35, $time->getMinute());
        $this->assertEquals(20, $time->getSecond());
        $this->assertEquals(123400, $time->getNano());
        $this->assertEquals('19:35:20', $time->toString());
    }

    public function testConstructorFull()
    {
        $time = LocalTime::of(13, 37, 12);

        $this->assertEquals('13:37:12', $time->toString());
    }

    public function testToString()
    {
        $time = LocalTime::of(13, 37, 12);

        $this->assertEquals('13:37:12', (string) $time);
    }

    public function testTimeEmpty()
    {
        $this->expectException(InvalidFormatException::class);

        LocalTime::parse('');
    }

    public function testTimeInvalid()
    {
        $this->expectException(InvalidFormatException::class);

        LocalTime::parse('foo');
    }

    public function testTimeInvalidOffset()
    {
        $this->expectException(InvalidFormatException::class);

        LocalTime::parse('19:35:20+50:00');
    }

    public function testTimeInvalidMicroSeconds()
    {
        $this->expectException(InvalidFormatException::class);

        LocalTime::parse('19:35:20.foo');
    }

    public function testFromDateTime()
    {
        $time = LocalTime::from(new \DateTime('2015-04-25T19:35:20'));

        $this->assertEquals('19:35:20', $time->toString());
    }
}
