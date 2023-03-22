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
use PSX\DateTime\LocalDate;
use PSX\DateTime\Month;

/**
 * DateTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class LocalDateTest extends TestCase
{
    public function testDate()
    {
        $date = LocalDate::parse('2015-04-25');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(Month::APRIL, $date->getMonth());
        $this->assertEquals(4, $date->getMonthValue());
        $this->assertEquals(25, $date->getDayOfMonth());
        $this->assertEquals('2015-04-25', $date->toString());
        $this->assertEquals('"2015-04-25"', \json_encode($date));
    }

    public function testDateOffset()
    {
        $date = LocalDate::parse('2015-04-25+01:00');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(Month::APRIL, $date->getMonth());
        $this->assertEquals(4, $date->getMonthValue());
        $this->assertEquals(25, $date->getDayOfMonth());
        $this->assertEquals('2015-04-25', $date->toString());
    }

    public function testConstructorFull()
    {
        $date = LocalDate::of(2014, 1, 1);

        $this->assertEquals('2014-01-01', $date->toString());
    }

    public function testToString()
    {
        $date = LocalDate::of(2014, 1, 1);

        $this->assertEquals('2014-01-01', (string) $date);
    }

    public function testDateEmpty()
    {
        $this->expectException(InvalidFormatException::class);

        LocalDate::parse('');
    }

    public function testDateInvalid()
    {
        $this->expectException(InvalidFormatException::class);

        LocalDate::parse('foo');
    }

    public function testDateInvalidOffset()
    {
        $this->expectException(InvalidFormatException::class);

        LocalDate::parse('2015-04-25+50:00');
    }

    public function testFromDateTime()
    {
        $date = LocalDate::from(new \DateTime('2015-04-25T19:35:20'));

        $this->assertEquals('2015-04-25', $date->toString());
    }
}
