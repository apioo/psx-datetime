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

namespace PSX\DateTime;

use PSX\DateTime\Exception\InvalidFormatException;

/**
 * A time without a time-zone in the ISO-8601 calendar system, such as 10:15:30
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     https://docs.oracle.com/javase/8/docs/api/java/time/LocalTime.html
 */
class LocalTime implements \JsonSerializable, \Stringable
{
    use LocalTimeTrait;
    use ComparisonTrait;

    private \DateTimeImmutable $internal;

    private function __construct(\DateTimeImmutable $now)
    {
        $this->internal = $now;
    }

    public function toString(): string
    {
        return $this->internal->format('H:i:s');
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return $this->internal;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public static function from(\DateTimeInterface $time): self
    {
        return new self(\DateTimeImmutable::createFromInterface($time));
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable('1970-01-01 ' . date('H:i:s')));
    }

    public static function of(int $hour, int $minute, int $second): self
    {
        return new self(new \DateTimeImmutable('@' . gmmktime($hour, $minute, $second, 1, 1, 1970)));
    }

    public static function parse(string $date): self
    {
        $result = preg_match('/^' . self::getPattern() . '$/', $date);
        if (!$result) {
            throw new InvalidFormatException('Must be valid time format');
        }

        return new self(new \DateTimeImmutable('1970-01-01 ' . $date));
    }

    /**
     * @see http://www.w3.org/TR/2012/REC-xmlschema11-2-20120405/datatypes.html#time-lexical-mapping
     */
    public static function getPattern(): string
    {
        return '(([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])(\.([0-9]+))?|(24:00:00(\.0+)?))(Z|(\+|-)((0[0-9]|1[0-3]):([0-5][0-9]|14:00)))?';
    }
}
