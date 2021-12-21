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

use DateInterval;
use PSX\DateTime\Exception\InvalidFormatException;

/**
 * Date
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     http://tools.ietf.org/html/rfc3339#section-5.6
 */
class Duration extends DateInterval implements \JsonSerializable
{
    /**
     * @throws InvalidFormatException
     */
    public function __construct(string|\Stringable $duration)
    {
        $value = $this->validate((string) $duration);

        try {
            parent::__construct($value);
        } catch (\Exception $e) {
            throw new InvalidFormatException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function getYear(): int
    {
        return $this->y;
    }

    public function getMonth(): int
    {
        return $this->m;
    }

    public function getDay(): int
    {
        return $this->d;
    }

    public function getHour(): int
    {
        return $this->h;
    }

    public function getMinute(): int
    {
        return $this->i;
    }

    public function getSecond(): int
    {
        return $this->s;
    }

    public function toString(): string
    {
        $duration = 'P';

        if ($this->y > 0) {
            $duration.= $this->y . 'Y';
        }

        if ($this->m > 0) {
            $duration.= $this->m . 'M';
        }

        if ($this->d > 0) {
            $duration.= $this->d . 'D';
        }

        if ($this->h > 0 || $this->i > 0 || $this->s > 0) {
            $duration.= 'T';

            if ($this->h > 0) {
                $duration.= $this->h . 'H';
            }

            if ($this->i > 0) {
                $duration.= $this->i . 'M';
            }

            if ($this->s > 0) {
                $duration.= $this->s . 'S';
            }
        }

        return $duration;
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
    protected function validate(string $duration): string
    {
        $result = preg_match('/^' . self::getPattern() . '$/', $duration);
        if (!$result) {
            throw new InvalidFormatException('Must be duration format');
        }

        return $duration;
    }

    /**
     * @throws InvalidFormatException
     */
    public static function fromDateInterval(\DateInterval $interval): self
    {
        try {
            return new self(sprintf('P%sY%sM%sDT%sH%sM%sS', $interval->y, $interval->m, $interval->d, $interval->h, $interval->i, $interval->s));
        } catch (\Exception $e) {
            throw new InvalidFormatException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws InvalidFormatException
     */
    public static function create(int $year, int $month, int $day, int $hour, int $minute, int $second): self
    {
        try {
            return new self(sprintf('P%sY%sM%sDT%sH%sM%sS', $year, $month, $day, $hour, $minute, $second));
        } catch (\Exception $e) {
            throw new InvalidFormatException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Returns the seconds of an DateInterval recalculating years, months etc.
     */
    public static function getSecondsFromInterval(DateInterval $interval): int
    {
        $keys   = [31536000, 2592000, 86400, 3600, 60, 1];
        $values = explode('.', $interval->format('%y.%m.%d.%h.%i.%s'));
        $result = array_combine($keys, $values);

        $value = 0;
        foreach ($result as $key => $val) {
            $value+= intval($val) * $key;
        }

        return $value;
    }

    /**
     * @see http://www.w3.org/TR/2012/REC-xmlschema11-2-20120405/datatypes.html#duration-lexical-space
     */
    public static function getPattern(): string
    {
        return '-?P((([0-9]+Y([0-9]+M)?([0-9]+D)?|([0-9]+M)([0-9]+D)?|([0-9]+D))(T(([0-9]+H)([0-9]+M)?([0-9]+(\.[0-9]+)?S)?|([0-9]+M)([0-9]+(\.[0-9]+)?S)?|([0-9]+(\.[0-9]+)?S)))?)|(T(([0-9]+H)([0-9]+M)?([0-9]+(\.[0-9]+)?S)?|([0-9]+M)([0-9]+(\.[0-9]+)?S)?|([0-9]+(\.[0-9]+)?S))))';
    }
}
