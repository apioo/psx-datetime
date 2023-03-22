
# DateTime

## About

PHP port of the Java JSR 310 time API. This package provides the following classes:

| Class         | Description                                                                                          |
|---------------|------------------------------------------------------------------------------------------------------|
| Duration      | A time-based amount of time, such as '34.5 seconds'                                                  |
| LocalDate     | A date without a time-zone in the ISO-8601 calendar system, such as 2007-12-03                       |
| LocalDateTime | A date-time without a time-zone in the ISO-8601 calendar system, such as 2007-12-03T10:15:30         |
| LocalTime     | A time without a time-zone in the ISO-8601 calendar system, such as 10:15:30                         |
| Period        | A date-based amount of time in the ISO-8601 calendar system, such as '2 years, 3 months and 4 days'  |
| DayOfWeek     | A day-of-week, such as 'Tuesday'                                                                     |
| Month         | A month-of-year, such as 'July'                                                                      |

All classes are immutable, this means that every modification to the object returns a new instance
containing the modification.

## Usage

The following snippet shows some examples how you can use the API.

```php
<?php

use PSX\DateTime\Duration;
use PSX\DateTime\LocalDate;
use PSX\DateTime\LocalDateTime;
use PSX\DateTime\LocalTime;
use PSX\DateTime\Period;

// date time
$dateTime = LocalDateTime::parse('2023-03-22T22:56:00Z');
$dateTime = LocalDateTime::of(2023, 3, 22, 22, 56, 0);

$dateTime->getYear(); // 2023
$dateTime->getMonth(); // Month::MARCH
$dateTime->getMonthValue(); // 3
$dateTime->getDayOfMonth(); // 22
$dateTime->getDayOfWeek(); // 3
$dateTime->getHour(); // 22
$dateTime->getMinute(); // 56
$dateTime->getSecond(); // 0

$dateTime->plusDays(1);
$dateTime->minusDays(1);
$dateTime->withDayOfMonth(1);

echo $dateTime->toString(); // 2016-03-28T23:27:00Z

// date
$date = LocalDate::parse('2023-03-22');
$date = LocalDate::of(2023, 3, 22);

echo $date->toString(); // 2023-03-22

// time
$time = LocalTime::parse('23:27:00');
$time = LocalTime::of(23, 27, 0);

echo $time->toString(); // 23:27:00

// period
$period = Period::parse('P1D');
$period = Period::of(1, 0, 0);

echo $period->toString(); // P1D

// duration
$duration = Duration::parse('P1H');
$duration = Duration::of(1, 0, 0);

echo $duration->toString(); // PT1H

```
