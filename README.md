
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

## Usage

```php
<?php

use PSX\DateTime\Duration;
use PSX\DateTime\LocalDate;
use PSX\DateTime\LocalDateTime;
use PSX\DateTime\LocalTime;
use PSX\DateTime\Period;

// date time
$dateTime = LocalDateTime::parse('2016-03-28T23:27:00Z');
$dateTime = LocalDateTime::of(2016, 3, 28, 23, 27, 0);

echo $dateTime->toString(); // 2016-03-28T23:27:00Z

// date
$date = LocalDate::parse('2016-03-28');
$date = LocalDate::of(2016, 3, 28);

echo $date->toString(); // 2016-03-28

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
