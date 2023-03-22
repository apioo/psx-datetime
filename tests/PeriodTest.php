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
use PSX\DateTime\Exception\InvalidFormatException;
use PSX\DateTime\Period;

/**
 * PeriodTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class PeriodTest extends TestCase
{
    public function testDuration()
    {
        $duration = Period::parse('P2015Y4M25DT19H35M20S');

        $this->assertEquals(2015, $duration->getYears());
        $this->assertEquals(4, $duration->getMonths());
        $this->assertEquals(25, $duration->getDays());
        $this->assertEquals('P2015Y4M25D', $duration->toString());
        $this->assertEquals('"P2015Y4M25D"', \json_encode($duration));
    }

    public function testDurationYear()
    {
        $duration = Period::parse('P2015Y');

        $this->assertEquals(2015, $duration->getYears());
        $this->assertEquals(0, $duration->getMonths());
        $this->assertEquals(0, $duration->getDays());
        $this->assertEquals('P2015Y', $duration->toString());
    }

    public function testDurationMonth()
    {
        $duration = Period::parse('P4M');

        $this->assertEquals(0, $duration->getYears());
        $this->assertEquals(4, $duration->getMonths());
        $this->assertEquals(0, $duration->getDays());
        $this->assertEquals('P4M', $duration->toString());
    }

    public function testDurationDay()
    {
        $duration = Period::parse('P25D');

        $this->assertEquals(0, $duration->getYears());
        $this->assertEquals(0, $duration->getMonths());
        $this->assertEquals(25, $duration->getDays());
        $this->assertEquals('P25D', $duration->toString());
    }

    public function testDurationHour()
    {
        $duration = Period::parse('PT19H');

        $this->assertEquals(0, $duration->getYears());
        $this->assertEquals(0, $duration->getMonths());
        $this->assertEquals(0, $duration->getDays());
        $this->assertEquals('P', $duration->toString());
    }

    public function testDurationMinute()
    {
        $duration = Period::parse('PT35M');

        $this->assertEquals(0, $duration->getYears());
        $this->assertEquals(0, $duration->getMonths());
        $this->assertEquals(0, $duration->getDays());
        $this->assertEquals('P', $duration->toString());
    }

    public function testDurationSecond()
    {
        $duration = Period::parse('PT20S');

        $this->assertEquals(0, $duration->getYears());
        $this->assertEquals(0, $duration->getMonths());
        $this->assertEquals(0, $duration->getDays());
        $this->assertEquals('P', $duration->toString());
    }

    public function testOf()
    {
        $duration = Period::of(1, 1, 1);

        $this->assertEquals('P1Y1M1D', $duration->toString());
    }

    public function testToString()
    {
        $duration = Period::of(1, 1, 1);

        $this->assertEquals('P1Y1M1D', (string) $duration);
    }

    public function testDurationEmpty()
    {
        $this->expectException(InvalidFormatException::class);

        Period::parse('');
    }

    public function testDurationInvalid()
    {
        $this->expectException(InvalidFormatException::class);

        Period::parse('foo');
    }

    public function testFrom()
    {
        $this->assertEquals('P2015Y4M25D', Period::from(new DateInterval('P2015Y4M25DT19H35M20S'))->toString());
        $this->assertEquals('P', Period::from(new DateInterval('PT60S'))->toString());
    }
}
