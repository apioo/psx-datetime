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
 * A time-based amount of time, such as '34.5 seconds'
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     https://docs.oracle.com/javase/8/docs/api/java/time/Duration.html
 */
class Duration implements \JsonSerializable, \Stringable
{
    private \DateInterval $internal;

    private function __construct(\DateInterval $interval)
    {
        $this->internal = $interval;
    }

    /**
     * Gets the number of hours in this duration
     */
    public function getHours(): int
    {
        return $this->internal->h;
    }

    /**
     * Gets the number of hours in this duration
     */
    public function getMinutes(): int
    {
        return $this->internal->i;
    }

    /**
     * Gets the number of seconds in this duration
     */
    public function getSeconds(): int
    {
        return $this->internal->s;
    }

    /**
     * Checks if this duration is negative, excluding zero
     */
    public function isNegative(): bool
    {
        return $this->internal->invert === 1;
    }

    /**
     * Checks if this duration is zero length
     */
    public function isZero(): bool
    {
        return $this->internal->h === 0 && $this->internal->i === 0 && $this->internal->s === 0;
    }

    /**
     * Returns a copy of this duration with the specified duration in hours subtracted
     */
    public function minusHours(int $hoursToSubtract): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h - $hoursToSubtract, $this->internal->i, $this->internal->s)));
    }

    /**
     * Returns a copy of this duration with the specified duration in hours subtracted
     */
    public function minusMinutes(int $minutesToSubtract): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h, $this->internal->i - $minutesToSubtract, $this->internal->s)));
    }

    /**
     * Returns a copy of this duration with the specified duration in hours subtracted
     */
    public function minusSeconds(int $secondsToSubtract): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h, $this->internal->i, $this->internal->s - $secondsToSubtract)));
    }

    /**
     * Returns a copy of this duration with the length negated
     */
    public function negated(): self
    {
        $internal = clone $this->internal;
        $internal->invert = 1;
        return new self($internal);
    }

    /**
     * Returns a copy of this duration with the specified duration in hours added
     */
    public function plusHours(int $hoursToAdd): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h + $hoursToAdd, $this->internal->i, $this->internal->s)));
    }

    /**
     * Returns a copy of this duration with the specified duration in minutes added
     */
    public function plusMinutes(int $minutesToAdd): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h, $this->internal->i + $minutesToAdd, $this->internal->s)));
    }

    /**
     * Returns a copy of this duration with the specified duration in seconds added
     */
    public function plusSeconds(int $secondsToAdd): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h, $this->internal->i, $this->internal->s + $secondsToAdd)));
    }

    /**
     * Returns a copy of this period with the specified amount of hours
     */
    public function withHours(int $hours): self
    {
        return new self(new \DateInterval($this->buildString($hours, $this->internal->i, $this->internal->s)));
    }

    /**
     * Returns a copy of this period with the specified amount of minutes
     */
    public function withMinutes(int $minutes): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h, $minutes, $this->internal->s)));
    }

    /**
     * Returns a copy of this period with the specified amount of seconds
     */
    public function withSeconds(int $seconds): self
    {
        return new self(new \DateInterval($this->buildString($this->internal->h, $this->internal->i, $seconds)));
    }

    public function toInterval(): \DateInterval
    {
        return clone $this->internal;
    }

    public function toString(): string
    {
        return $this->buildString($this->internal->h, $this->internal->i, $this->internal->s);
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    private function buildString(int $h, int $i, int $s): string
    {
        $duration = 'P';
        $duration.= 'T';

        if ($h > 0) {
            $duration.= $h . 'H';
        }

        if ($i > 0) {
            $duration.= $i . 'M';
        }

        if ($s > 0) {
            $duration.= $s . 'S';
        }

        return $duration;
    }

    public static function from(\DateInterval $interval): self
    {
        return new self($interval);
    }

    public static function of(int $hours, int $minutes, int $seconds): self
    {
        return new self(new \DateInterval('PT' . $hours . 'H' . $minutes . 'M' . $seconds . 'S'));
    }

    public static function ofHours(int $hours): self
    {
        return new self(new \DateInterval('PT' . $hours . 'H'));
    }

    public static function ofMinutes(int $minutes): self
    {
        return new self(new \DateInterval('PT' . $minutes . 'M'));
    }

    public static function ofSeconds(int $seconds): self
    {
        return new self(new \DateInterval('PT' . $seconds . 'S'));
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
