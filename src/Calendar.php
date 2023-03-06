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

use Countable;
use DateInterval;
use DateTimeZone;
use Iterator;

/**
 * Calendar
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 */
class Calendar implements Iterator, Countable
{
    private LocalDate $date;
    private \DateTime $itDate;

    public function __construct(\DateTimeInterface $date = null, DateTimeZone $timezone = null)
    {
        $this->date = LocalDate::fromDateTime($date ?? new \DateTime());

        if ($timezone !== null) {
            $this->setTimezone($timezone);
        }

        $this->itDate = DateTime::create($this->getYear(), $this->getMonth(), 1, 0, 0, 0);
    }

    /**
     * Sets the underlying datetime object and removes the timepart of the datetime object
     */
    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = LocalDate::fromDateTime($date);
    }

    public function getDate(): LocalDate
    {
        return $this->date;
    }

    public function setTimezone(DateTimeZone $timezone): void
    {
        $this->date->setTimezone($timezone);
    }

    public function getTimezone(): DateTimeZone
    {
        return $this->date->getTimezone();
    }

    /**
     * Return the days of the current month and year
     */
    public function getDays(): int
    {
        return cal_days_in_month(
            CAL_GREGORIAN,
            (int) $this->date->format('n'),
            (int) $this->date->format('Y')
        );
    }

    /**
     * Returns the easter date for the current year
     */
    public function getEasterDate(): DateTime
    {
        $easter = DateTime::create($this->getYear(), 3, 21, 0, 0, 0);
        $days   = easter_days($this->getYear());

        return $easter->add(new DateInterval('P' . $days . 'D'));
    }

    public function getWeekNumber(): int
    {
        return (int) $this->date->format('W');
    }

    public function getDay(): int
    {
        return (int) $this->date->format('j');
    }

    public function getMonth(): int
    {
        return (int) $this->date->format('n');
    }

    public function getYear(): int
    {
        return (int) $this->date->format('Y');
    }

    public function getMonthName(): string
    {
        return $this->date->format('F');
    }

    public function add(DateInterval $interval): static
    {
        $this->date->add($interval);
        return $this;
    }

    public function sub(DateInterval $interval): static
    {
        $this->date->sub($interval);
        return $this;
    }

    public function nextDay(): static
    {
        return $this->add(new DateInterval('P1D'));
    }

    public function prevDay(): static
    {
        return $this->sub(new DateInterval('P1D'));
    }

    public function nextMonth(): static
    {
        return $this->add(new DateInterval('P1M'));
    }

    public function prevMonth(): static
    {
        return $this->sub(new DateInterval('P1M'));
    }

    public function nextYear(): static
    {
        return $this->add(new DateInterval('P1Y'));
    }

    public function prevYear(): static
    {
        return $this->sub(new DateInterval('P1Y'));
    }

    // countable
    public function count(): int
    {
        return $this->getDays();
    }

    // iterator
    public function current()
    {
        return $this->itDate;
    }

    public function key()
    {
        return $this->itDate->format('j');
    }

    public function next()
    {
        $this->itDate->add(new DateInterval('P1D'))->setTime(0, 0, 0);
    }

    public function rewind()
    {
        $this->itDate = DateTime::create($this->getYear(), $this->getMonth(), 1, 0, 0, 0);
    }

    public function valid()
    {
        return $this->date->format('n') == $this->itDate->format('n');
    }
}
