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

use DateInterval;

/**
 * LocalTimeTrait
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://phpsx.org
 * @see     http://tools.ietf.org/html/rfc3339#section-5.6
 */
trait LocalTimeTrait
{
    public function getHour(): int
    {
        return (int) $this->internal->format('G');
    }

    public function getMinute(): int
    {
        return (int) $this->internal->format('i');
    }

    public function getNano(): int
    {
        return (int) $this->internal->format('u');
    }

    public function getSecond(): int
    {
        return (int) $this->internal->format('s');
    }

    public function minusHours(int $hoursToSubtract): self
    {
        return new self($this->internal->sub(new DateInterval('PT' . $hoursToSubtract . 'H')));
    }

    public function minusMinutes(int $minutesToSubtract): self
    {
        return new self($this->internal->sub(new DateInterval('PT' . $minutesToSubtract . 'M')));
    }

    public function minusSeconds(int $secondsToSubtract): self
    {
        return new self($this->internal->sub(new DateInterval('PT' . $secondsToSubtract . 'S')));
    }

    public function plusHours(int $hoursToAdd): self
    {
        return new self($this->internal->add(new DateInterval('PT' . $hoursToAdd . 'H')));
    }

    public function plusMinutes(int $minutesToAdd): self
    {
        return new self($this->internal->add(new DateInterval('PT' . $minutesToAdd . 'M')));
    }

    public function plusSeconds(int $secondsToAdd): self
    {
        return new self($this->internal->add(new DateInterval('PT' . $secondsToAdd . 'S')));
    }

    public function withHour(int $hour): self
    {
        return new self($this->internal->setTime($hour, $this->getMinute(), $this->getSecond()));
    }

    public function withMinute(int $minute): self
    {
        return new self($this->internal->setTime($this->getHour(), $minute, $this->getSecond()));
    }

    public function withSecond(int $second): self
    {
        return new self($this->internal->setTime($this->getHour(), $this->getMinute(), $second));
    }
}
