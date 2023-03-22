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

use DateInterval;
use PHPUnit\Framework\TestCase;
use PSX\DateTime\Duration;
use PSX\DateTime\Exception\InvalidFormatException;

/**
 * DurationTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class DurationTest extends TestCase
{
    public function testDuration()
    {
        $duration = Duration::parse('P2015Y4M25DT19H35M20S');

        $this->assertEquals(19, $duration->getHours());
        $this->assertEquals(35, $duration->getMinutes());
        $this->assertEquals(20, $duration->getSeconds());
        $this->assertEquals('PT19H35M20S', $duration->toString());
        $this->assertEquals('"PT19H35M20S"', \json_encode($duration));
    }

    public function testDurationYear()
    {
        $duration = Duration::parse('P2015Y');

        $this->assertEquals(0, $duration->getHours());
        $this->assertEquals(0, $duration->getMinutes());
        $this->assertEquals(0, $duration->getSeconds());
        $this->assertEquals('PT', $duration->toString());
    }

    public function testDurationMonth()
    {
        $duration = Duration::parse('P4M');

        $this->assertEquals(0, $duration->getHours());
        $this->assertEquals(0, $duration->getMinutes());
        $this->assertEquals(0, $duration->getSeconds());
        $this->assertEquals('PT', $duration->toString());
    }

    public function testDurationDay()
    {
        $duration = Duration::parse('P25D');

        $this->assertEquals(0, $duration->getHours());
        $this->assertEquals(0, $duration->getMinutes());
        $this->assertEquals(0, $duration->getSeconds());
        $this->assertEquals('PT', $duration->toString());
    }

    public function testDurationHour()
    {
        $duration = Duration::parse('PT19H');

        $this->assertEquals(19, $duration->getHours());
        $this->assertEquals(0, $duration->getMinutes());
        $this->assertEquals(0, $duration->getSeconds());
        $this->assertEquals('PT19H', $duration->toString());
    }

    public function testDurationMinute()
    {
        $duration = Duration::parse('PT35M');

        $this->assertEquals(0, $duration->getHours());
        $this->assertEquals(35, $duration->getMinutes());
        $this->assertEquals(0, $duration->getSeconds());
        $this->assertEquals('PT35M', $duration->toString());
    }

    public function testDurationSecond()
    {
        $duration = Duration::parse('PT20S');

        $this->assertEquals(0, $duration->getHours());
        $this->assertEquals(0, $duration->getMinutes());
        $this->assertEquals(20, $duration->getSeconds());
        $this->assertEquals('PT20S', $duration->toString());
    }

    public function testOf()
    {
        $duration = Duration::of(1, 1, 1);

        $this->assertEquals('PT1H1M1S', $duration->toString());
    }

    public function testToString()
    {
        $duration = Duration::of(1, 1, 1);

        $this->assertEquals('PT1H1M1S', (string) $duration);
    }

    public function testDurationEmpty()
    {
        $this->expectException(InvalidFormatException::class);

        Duration::parse('');
    }

    public function testDurationInvalid()
    {
        $this->expectException(InvalidFormatException::class);

        Duration::parse('foo');
    }

    public function testFrom()
    {
        $this->assertEquals('PT19H35M20S', Duration::from(new DateInterval('P2015Y4M25DT19H35M20S'))->toString());
        $this->assertEquals('PT60S', Duration::from(new DateInterval('PT60S'))->toString());
    }
}
