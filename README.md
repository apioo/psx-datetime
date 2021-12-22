
# DateTime

## About

Stricter date time implementations which only accepts RFC3339 date time strings.
Handles date time, date, time and duration formats:

* Date `[full-date] (2015-05-02)`
* Time `[full-time] (10:51:12)`
* DateTime `[full-date "T" full-time] (2015-05-02T10:51:12Z)`
* Duration `[duration] (P1D)`

## Usage

```php
<?php

use PSX\DateTime\Date;
use PSX\DateTime\DateTime;
use PSX\DateTime\Duration;
use PSX\DateTime\Time;

// date time
$dateTime = new DateTime('2016-03-28T23:27:00Z');
$dateTime = DateTime::create(2016, 3, 28, 23, 27, 0);

echo $dateTime->toString(); // 2016-03-28T23:27:00Z

// date
$date = new Date('2016-03-28');
$date = Date::create(2016, 3, 28);

echo $date->toString(); // 2016-03-28

// time
$time = new Time('23:27:00');
$time = Time::create(23, 27, 0);

echo $time->toString(); // 23:27:00

// duration
$duration = new Duration('P1D');
$duration = Duration::create(0, 0, 1, 0, 0, 0);

echo $duration->toString(); // P0Y0M1D

```
