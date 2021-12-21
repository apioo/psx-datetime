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
use PSX\DateTime\Date;

/**
 * DateTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class DateTest extends TestCase
{
    public function testDate()
    {
        $date = new Date('2015-04-25');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(4, $date->getMonth());
        $this->assertEquals(25, $date->getDay());
        $this->assertEquals(0, $date->getOffset());
        $this->assertInstanceOf(\DateTimeZone::class, $date->getTimeZone());
        $this->assertEquals('2015-04-25', $date->toString());
        $this->assertEquals('"2015-04-25"', \json_encode($date));
    }

    public function testDateOffset()
    {
        $date = new Date('2015-04-25+01:00');

        $this->assertEquals(2015, $date->getYear());
        $this->assertEquals(4, $date->getMonth());
        $this->assertEquals(25, $date->getDay());
        $this->assertEquals(3600, $date->getOffset());
        $this->assertInstanceOf(\DateTimeZone::class, $date->getTimeZone());
        $this->assertEquals('2015-04-25+01:00', $date->toString());
    }

    public function testConstructorFull()
    {
        $date = Date::create(2014, 1, 1);

        $this->assertEquals('2014-01-01', $date->toString());
    }

    public function testToString()
    {
        $date = Date::create(2014, 1, 1);

        $this->assertEquals('2014-01-01', (string) $date);
    }

    public function testDateEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Date('');
    }

    public function testDateInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Date('foo');
    }

    public function testDateInvalidOffset()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Date('2015-04-25+50:00');
    }

    public function testFromDateTime()
    {
        $date = Date::fromDateTime(new \DateTime('2015-04-25T19:35:20'));

        $this->assertEquals('2015-04-25', $date->toString());
    }
}
