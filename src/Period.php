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
 * A date-based amount of time in the ISO-8601 calendar system, such as '2 years, 3 months and 4 days'
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     https://docs.oracle.com/javase/8/docs/api/java/time/Period.html
 */
class Period implements \JsonSerializable, \Stringable
{
    private \DateInterval $internal;

    private function __construct(\DateInterval $interval)
    {
        $this->internal = $interval;
    }

    /**
     * Gets the amount of days of this period
     */
    public function getDays(): int
    {
        return $this->internal->d;
    }

    /**
     * Gets the amount of months of this period
     */
    public function getMonths(): int
    {
        return $this->internal->m;
    }

    /**
     * Gets the amount of years of this period
     */
    public function getYears(): int
    {
        return $this->internal->y;
    }

    /**
     * Checks if any of the three units of this period are negative
     */
    public function isNegative(): bool
    {
        return $this->internal->invert === 1;
    }

    /**
     * Checks if all three units of this period are zero
     */
    public function isZero(): bool
    {
        return $this->internal->y === 0 && $this->internal->m === 0 && $this->internal->d === 0;
    }

    /**
     * Returns a copy of this period with the specified days subtracted
     */
    public function minusDays(int $daysToSubtract): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y, $this->internal->m, $this->internal->d - $daysToSubtract)));
    }

    /**
     * Returns a copy of this period with the specified months subtracted
     */
    public function minusMonths(int $monthsToSubtract): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y, $this->internal->m - $monthsToSubtract, $this->internal->d)));
    }

    /**
     * Returns a copy of this period with the specified years subtracted
     */
    public function minusYears(int $yearsToSubtract): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y - $yearsToSubtract, $this->internal->m, $this->internal->d)));
    }

    /**
     * Returns a copy of this period with the specified years subtracted
     */
    public function negated(): self
    {
        $internal = clone $this->internal;
        $internal->invert = 1;
        return new self($internal);
    }

    /**
     * Returns a copy of this period with the specified days added
     */
    public function plusDays(int $daysToAdd): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y, $this->internal->m, $this->internal->d + $daysToAdd)));
    }

    /**
     * Returns a copy of this period with the specified months added
     */
    public function plusMonths(int $monthsToAdd): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y, $this->internal->m + $monthsToAdd, $this->internal->d)));
    }

    /**
     * Returns a copy of this period with the specified years added
     */
    public function plusYears(int $yearsToAdd): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y + $yearsToAdd, $this->internal->m, $this->internal->d)));
    }

    /**
     * Returns a copy of this period with the specified amount of days
     */
    public function withDays(int $days): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y, $this->internal->m, $days)));
    }

    /**
     * Returns a copy of this period with the specified amount of months
     */
    public function withMonths(int $months): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->y, $months, $this->internal->d)));
    }

    /**
     * Returns a copy of this period with the specified amount of years
     */
    public function withYears(int $years): self
    {
        return new self(new \DateInterval($this->buildString($years, $this->internal->m, $this->internal->d)));
    }

    public function toInterval(): \DateInterval
    {
        return clone $this->internal;
    }

    public function toString(): string
    {
        return $this->buildString($this->internal->y, $this->internal->m, $this->internal->d);
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    private function buildString(int $y, int $m, int $d): string
    {
        $duration = 'P';

        if ($y > 0) {
            $duration.= $y . 'Y';
        }

        if ($m > 0) {
            $duration.= $m . 'M';
        }

        if ($d > 0) {
            $duration.= $d . 'D';
        }

        return $duration;
    }

    public static function from(\DateInterval $interval): self
    {
        return new self($interval);
    }

    public static function of(int $years, int $months, int $days): self
    {
        return new self(new \DateInterval('P' . $years . 'Y' . $months . 'M' . $days . 'D'));
    }

    public static function ofDays(int $days): self
    {
        return new self(new \DateInterval('P' . $days . 'D'));
    }

    public static function ofMonths(int $months): self
    {
        return new self(new \DateInterval('P' . $months . 'M'));
    }

    public static function ofWeeks(int $weeks): self
    {
        return new self(new \DateInterval('P' . $weeks . 'W'));
    }

    public static function ofYears(int $years): self
    {
        return new self(new \DateInterval('P' . $years . 'Y'));
    }

    /**
     * @throws InvalidFormatException
     */
    public static function parse(string $date): self
    {
        $result = preg_match('/^' . self::getPattern() . '$/', $date);
        if (!$result) {
            throw new InvalidFormatException('Must be valid interval format');
        }

        return new self(new \DateInterval($date));
    }

    /**
     * @see http://www.w3.org/TR/2012/REC-xmlschema11-2-20120405/datatypes.html#duration-lexical-space
     */
    public static function getPattern(): string
    {
        return '-?P((([0-9]+Y([0-9]+M)?([0-9]+D)?|([0-9]+M)([0-9]+D)?|([0-9]+D))(T(([0-9]+H)([0-9]+M)?([0-9]+(\.[0-9]+)?S)?|([0-9]+M)([0-9]+(\.[0-9]+)?S)?|([0-9]+(\.[0-9]+)?S)))?)|(T(([0-9]+H)([0-9]+M)?([0-9]+(\.[0-9]+)?S)?|([0-9]+M)([0-9]+(\.[0-9]+)?S)?|([0-9]+(\.[0-9]+)?S))))';
    }
}
