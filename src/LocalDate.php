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

namespace PSX\DateTime;

use PSX\DateTime\Exception\InvalidFormatException;

/**
 * LocalDate
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     http://tools.ietf.org/html/rfc3339#section-5.6
 */
class LocalDate implements \JsonSerializable
{
    use LocalDateTrait;
    use ComparisonTrait;

    private \DateTimeImmutable $internal;

    private function __construct(\DateTimeImmutable $now)
    {
        $this->internal = $now;
    }

    public function toString(): string
    {
        return $this->internal->format('Y-m-d');
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public static function from(\DateTimeInterface $date): self
    {
        return new self(\DateTimeImmutable::createFromInterface($date));
    }

    public static function now(?\DateTimeZone $timezone = null): self
    {
        return new self(new \DateTimeImmutable('now', $timezone));
    }

    public static function of(int $year, Month|int $month, int $day): self
    {
        return new self(new \DateTimeImmutable('@' . gmmktime(0, 0, 0, $month, $day, $year)));
    }

    public static function parse(string $date): self
    {
        $result = preg_match('/^' . self::getPattern() . '$/', $date);
        if (!$result) {
            throw new InvalidFormatException('Must be valid date format');
        }

        return new self(new \DateTimeImmutable($date));
    }

    /**
     * @see http://www.w3.org/TR/2012/REC-xmlschema11-2-20120405/datatypes.html#date-lexical-mapping
     */
    public static function getPattern(): string
    {
        return '-?([1-9][0-9]{3,}|0[0-9]{3})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])(Z|(\+|-)((0[0-9]|1[0-3]):([0-5][0-9]|14:00)))?';
    }
}
