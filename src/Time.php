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

use InvalidArgumentException;

/**
 * Time
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     http://tools.ietf.org/html/rfc3339#section-5.6
 */
class Time extends \DateTime implements \JsonSerializable
{
    public function __construct(int|string|\Stringable|null $hour, ?int $minute = null, ?int $second = null)
    {
        if (is_string($hour) || $hour instanceof \Stringable) {
            parent::__construct($this->validate((string) $hour));
        } elseif ($hour !== null && $minute !== null && $second !== null) {
            parent::__construct('@' . gmmktime($hour, $minute, $second, 1, 1, 1970));
        } else {
            parent::__construct();
        }
    }

    public function getHour(): int
    {
        return (int) $this->format('H');
    }

    public function getMinute(): int
    {
        return (int) $this->format('i');
    }

    public function getSecond(): int
    {
        return (int) $this->format('s');
    }

    public function getMicroSecond(): int
    {
        return (int) $this->format('u');
    }

    public function toString(): string
    {
        $time   = $this->format('H:i:s');
        $ms     = $this->getMicroSecond();
        $offset = $this->getOffset();

        if ($ms > 0) {
            $time.= '.' . $ms;
        }

        if ($offset != 0) {
            $time.= DateTime::getOffsetBySeconds($offset);
        }

        return $time;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize()
    {
        return $this->toString();
    }

    protected function validate(mixed $time): string
    {
        if ($time === null) {
            return 'now';
        }

        $time   = (string) $time;
        $result = preg_match('/^' . self::getPattern() . '$/', $time);

        if (!$result) {
            throw new InvalidArgumentException('Must be valid time format');
        }

        return $time;
    }

    public static function fromDateTime(\DateTimeInterface $date): self
    {
        return new self(
            (int) $date->format('H'),
            (int) $date->format('i'),
            (int) $date->format('s')
        );
    }

    /**
     * @see http://www.w3.org/TR/2012/REC-xmlschema11-2-20120405/datatypes.html#time-lexical-mapping
     */
    public static function getPattern(): string
    {
        return '(([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])(\.([0-9]+))?|(24:00:00(\.0+)?))(Z|(\+|-)((0[0-9]|1[0-3]):([0-5][0-9]|14:00)))?';
    }
}
