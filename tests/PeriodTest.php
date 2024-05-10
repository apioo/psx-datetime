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
        $period = Period::parse('P2015Y4M25DT19H35M20S');

        $this->assertEquals(2015, $period->getYears());
        $this->assertEquals(4, $period->getMonths());
        $this->assertEquals(25, $period->getDays());
        $this->assertEquals('P2015Y4M25D', $period->toString());
        $this->assertEquals('"P2015Y4M25D"', \json_encode($period));
    }

    public function testDurationYear()
    {
        $period = Period::parse('P2015Y');

        $this->assertEquals(2015, $period->getYears());
        $this->assertEquals(0, $period->getMonths());
        $this->assertEquals(0, $period->getDays());
        $this->assertEquals('P2015Y', $period->toString());
    }

    public function testDurationMonth()
    {
        $period = Period::parse('P4M');

        $this->assertEquals(0, $period->getYears());
        $this->assertEquals(4, $period->getMonths());
        $this->assertEquals(0, $period->getDays());
        $this->assertEquals('P4M', $period->toString());
    }

    public function testDurationDay()
    {
        $period = Period::parse('P25D');

        $this->assertEquals(0, $period->getYears());
        $this->assertEquals(0, $period->getMonths());
        $this->assertEquals(25, $period->getDays());
        $this->assertEquals('P25D', $period->toString());
    }

    public function testDurationHour()
    {
        $period = Period::parse('PT19H');

        $this->assertEquals(0, $period->getYears());
        $this->assertEquals(0, $period->getMonths());
        $this->assertEquals(0, $period->getDays());
        $this->assertEquals('P', $period->toString());
    }

    public function testDurationMinute()
    {
        $period = Period::parse('PT35M');

        $this->assertEquals(0, $period->getYears());
        $this->assertEquals(0, $period->getMonths());
        $this->assertEquals(0, $period->getDays());
        $this->assertEquals('P', $period->toString());
    }

    public function testDurationSecond()
    {
        $period = Period::parse('PT20S');

        $this->assertEquals(0, $period->getYears());
        $this->assertEquals(0, $period->getMonths());
        $this->assertEquals(0, $period->getDays());
        $this->assertEquals('P', $period->toString());
    }

    public function testDurationMinusYears()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->minusYears(1);

        $this->assertEquals('P1M1D', $period->toString());
    }

    public function testDurationMinusMonths()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->minusMonths(1);

        $this->assertEquals('P1Y1D', $period->toString());
    }

    public function testDurationMinusDays()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->minusDays(1);

        $this->assertEquals('P1Y1M', $period->toString());
    }

    public function testDurationPlusYears()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->plusYears(1);

        $this->assertEquals('P2Y1M1D', $period->toString());
    }

    public function testDurationPlusMonths()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->plusMonths(1);

        $this->assertEquals('P1Y2M1D', $period->toString());
    }

    public function testDurationPlusDays()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->plusDays(1);

        $this->assertEquals('P1Y1M2D', $period->toString());
    }

    public function testDurationWithYears()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->withYears(1);

        $this->assertEquals('P1Y1M1D', $period->toString());
    }

    public function testDurationWithMonths()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->withMonths(1);

        $this->assertEquals('P1Y1M1D', $period->toString());
    }

    public function testDurationWithDays()
    {
        $period = Period::parse('P1Y1M1D');
        $period = $period->withDays(1);

        $this->assertEquals('P1Y1M1D', $period->toString());
    }

    public function testOf()
    {
        $period = Period::of(1, 1, 1);

        $this->assertEquals('P1Y1M1D', $period->toString());
    }

    public function testToString()
    {
        $period = Period::of(1, 1, 1);

        $this->assertEquals('P1Y1M1D', (string) $period);
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
