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

/**
 * LocalDateTrait
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     http://tools.ietf.org/html/rfc3339#section-5.6
 */
trait LocalDateTrait
{
    public function getDayOfMonth(): int
    {
        return (int) $this->internal->format('j');
    }

    public function getDayOfWeek(): DayOfWeek
    {
        return DayOfWeek::from((int) $this->internal->format('N'));
    }

    public function getDayOfYear(): int
    {
        return (int) $this->internal->format('z');
    }

    public function getMonth(): Month
    {
        return Month::from((int) $this->internal->format('n'));
    }

    public function getMonthValue(): int
    {
        return (int) $this->internal->format('n');
    }

    public function getYear(): int
    {
        return (int) $this->internal->format('Y');
    }

    public function isLeapYear(): bool
    {

    }

    public function minus(int $amountToSubtract, ChronoUnit $unit): self
    {
    }

    public function minusDays(int $daysToSubtract): self
    {
        return new self($this->internal->sub(new \DateInterval('P' . $daysToSubtract . 'D')));
    }

    public function minusMonths(int $monthsToSubtract): self
    {
        return new self($this->internal->sub(new \DateInterval('P' . $monthsToSubtract . 'M')));
    }

    public function minusWeeks(int $weeksToSubtract): self
    {
        return new self($this->internal->sub(new \DateInterval('P' . ($weeksToSubtract * 7) . 'D')));
    }

    public function minusYears(int $yearsToSubtract): self
    {
        return new self($this->internal->sub(new \DateInterval('P' . $yearsToSubtract . 'Y')));
    }

    public function plus(int $amountToAdd, ChronoUnit $unit): self
    {

    }

    public function plusDays(int $daysToAdd): self
    {
        return new self($this->internal->add(new \DateInterval('P' . $daysToAdd . 'D')));
    }

    public function plusMonths(int $monthsToAdd): self
    {
        return new self($this->internal->add(new \DateInterval('P' . $monthsToAdd . 'M')));
    }

    public function plusWeeks(int $weeksToAdd): self
    {
        return new self($this->internal->add(new \DateInterval('P' . ($weeksToAdd * 7) . 'D')));
    }

    public function plusYears(int $yearsToAdd): self
    {
        return new self($this->internal->add(new \DateInterval('P' . $yearsToAdd . 'Y')));
    }

    public function withDayOfMonth(int $dayOfMonth): self
    {

    }

    public function withDayOfYear(int $dayOfYear): self
    {

    }

    public function withMonth(int $month): self
    {

    }

    public function withYear(int $year): self
    {

    }
}
