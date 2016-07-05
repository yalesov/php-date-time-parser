# Yalesov\DateTimeParser

[![Build Status](https://secure.travis-ci.org/yalesov/date-time-parser.png)](http://travis-ci.org/yalesov/date-time-parser)

Parse date and time according to the subset of ISO 8601 date/time format used in Vcard [ISO 6350].

# Installation

[Composer](http://getcomposer.org/):

```json
{
    "require": {
        "yalesov/date-time-parser": "2.*"
    }
}
```

# Usage

## Parse an ISO-8601 date/time

```php
use Yalesov\DateTimeParser\Parser;

$datetime = Parser::parseDateTime('19961022T140000+0800');
// $datetime['year']        = '1996';
// $datetime['month']       = '10';
// $datetime['day']         = '22';
// $datetime['hour']        = '14';
// $datetime['minute']      = '00';
// $datetime['second']      = '00';
// $datetime['timezone']    = '+0800';

$datetime = Parser::parseDateTime('---22T14');
// $datetime['year']        = null;
// $datetime['month']       = null;
// $datetime['day']         = '22';
// $datetime['hour']        = '14';
// $datetime['minute']      = null;
// $datetime['second']      = null;
// $datetime['timezone']    = null;
```

Full function signature:

```php
public static function parseDateTime($datetime)
```

It accepts an ISO-8601 date-and-or-time string and returns an array of datetime units. It will throw an `InvalidArgumentException` if the input is not a valid date/time expression, or if the date/time specified is invalid.

Missing date/time units will be filled with `null`.

Standalone time expressions must be prefixed with `T`.

To parse an ISO-8601 date expression, use:

```php
public static function parseDate($date)
```

To parse an ISO-8601 time expression, use:

```php
public static function parseTime($time)
```

The return array for these two functions are same as `Parser::parseDateTime()`.

## Create an ISO-8601 date/time

```php
use Yalesov\DateTimeParser\Parser;

$expr = Parser::createDateTime('1996', '10', '22', '14', '00', '00', '+0800');
// $expr = '19961022T140000+0800';

$expr = Parser::createDateTime(null, null, '22', '14', null, null, null);
// $expr = '---22T14';
```

These are simply reverses of the `parse-()` functions.

Full function signatures:

```php
public static function createDateTime(
    $year = null, $month = null, $day = null,
    $hour = null, $minute = null, $second = null, $timezone = null)
```

```php
public static function createDate($year = null, $month = null, $day = null)
```

```php
public static function createTime($hour = null, $minute = null, $second = null, $timezone = null)
```

Valid ranges:

- Year: (unlimited)
- Month: 01-12
- Day: 01-31
- Hour: 00-23
- Minute: 00-59
- Second: 00-59

Note that `0`-prefixed numbers are different from `0`-prefixed strings. For example, if you mean August, write `8` or `'08'`, not `08`.

## Create a timestamp from a complete or partial date/time

```php
public static function createTimestamp(
    $year = null, $month = null, $day = null,
    $hour = null, $minute = null, $second = null, $timezone = null)
```

Accepted arguments are same as the `create-()` series.

`Parser::createTimestamp()` will create a timestamp if at least `year`, `month`, `day` are given (and valid). It will assume `'00'` for `hour`, `minute` and `second` if not given; and script timezone if `timezone` is not given.

Return `null` if failed to create a timestamp.
