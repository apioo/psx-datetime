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
 * Date
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     http://tools.ietf.org/html/rfc3339#section-5.6
 */
class Date extends \DateTime implements \JsonSerializable
{
    /**
     * @throws InvalidFormatException
     */
    public function __construct(string|\Stringable|null $date = null)
    {
        if ($date !== null) {
            $value = $this->validate((string) $date);
        } else {
            $value = date(\DateTimeInterface::RFC3339, gmmktime(0, 0, 0));
        }

        try {
            parent::__construct($value);
        } catch (\Exception $e) {
            throw new InvalidFormatException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getYear(): int
    {
        return (int) $this->format('Y');
    }

    public function getMonth(): int
    {
        return (int) $this->format('m');
    }

    public function getDay(): int
    {
        return (int) $this->format('d');
    }

    public function toString(): string
    {
        $date   = $this->format('Y-m-d');
        $offset = $this->getOffset();

        if ($offset != 0) {
            $date.= DateTime::getOffsetBySeconds($offset);
        }

        return $date;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize()
    {
        return $this->toString();
    }

    /**
     * @throws InvalidFormatException
     */
    protected function validate(string $date): string
    {
        $result = preg_match('/^' . self::getPattern() . '$/', $date);
        if (!$result) {
            throw new InvalidFormatException('Must be valid date format');
        }

        return $date;
    }

    /**
     * @throws InvalidFormatException
     */
    public static function fromDateTime(\DateTimeInterface $date): self
    {
        try {
            return new self($date->format('Y-m-d'));
        } catch (\Exception $e) {
            throw new InvalidFormatException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws InvalidFormatException
     */
    public static function create(int $year, int $month, int $day): self
    {
        try {
            return new self(date('Y-m-d', gmmktime(0, 0, 0, $month, $day, $year)));
        } catch (\Exception $e) {
            throw new InvalidFormatException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @see http://www.w3.org/TR/2012/REC-xmlschema11-2-20120405/datatypes.html#date-lexical-mapping
     */
    public static function getPattern(): string
    {
        return '-?([1-9][0-9]{3,}|0[0-9]{3})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])(Z|(\+|-)((0[0-9]|1[0-3]):([0-5][0-9]|14:00)))?';
    }
}
