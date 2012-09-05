<?php
namespace Heartsentwined\DateTimeParser;

use Heartsentwined\DateTimeParser\Exception;

/**
 * date and time parser
 * supports the subset of ISO 8601 date/time format used in Vcard [ISO 6350]
 */
class Parser
{
    /**
     * parse a date-and-or-time string
     * standalone time must be prefixed with 'T'
     *
     * @param string $datetime
     * @return array
     *  'year'      => string|null
     *  'month'     => string|null 01-12
     *  'day'       => string|null 01-31
     *  'hour'      => string|null 00-23
     *  'minute'    => string|null 00-59
     *  'second'    => string|null 00-59
     *  'timezone'  => string|null e.g. [+-]0500
     */
    public static function parseDateTime($datetime)
    {
        list($year, $month, $day, $hour, $minute, $second, $timezone) =
            array_fill(0, 7, null);

        if (strpos($datetime, 'T') !== 0) {
            $datetime .= 'T';
        }

        list($date, $time) = explode('T', $datetime);

        if (preg_match(
            '/^---(?<day>[\d]{2})$/',
            $date, $match)) {
            $day        = $match['day'];
        } elseif (preg_match(
            '/^--(?<month>[\d]{2})(?<day>[\d]{2})$/',
            $date, $match)) {
            $month      = $match['month'];
            $day        = $match['day'];
        } elseif (preg_match(
            '/^(?<year>[\d]{4})-(?<month>[\d]{2})$/',
            $date, $match)) {
            $year       = $match['year'];
            $month      = $match['month'];
        } elseif (preg_match(
            '/^(?<year>[\d]{4})(?<month>[\d]{2})(?<day>[\d]{2})$/',
            $date, $match)) {
            $year       = $match['year'];
            $month      = $match['month'];
            $day        = $match['day'];
        } elseif (preg_match(
            '/^(?<year>[\d]{4})$/',
            $date, $match)) {
            $year       = $match['year'];
        } elseif (!empty($date)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'invalid date expression: %s given',
                $date
            ));
        }

        $tzRegex = '(?<timezone>(?:|Z|[+-](?:[\d]{2}|[\d]{4})))';

        if (preg_match(
            "/^--(?<second>[\d]{2})$tzRegex$/",
            $time, $match)) {
            $second     = $match['second'];
            $timezone   = $match['timezone'];
        } elseif (preg_match(
            "/^-(?<minute>[\d]{2})(?<second>[\d]{2})$tzRegex$/",
            $time, $match)) {
            $minute     = $match['minute'];
            $second     = $match['second'];
            $timezone   = $match['timezone'];
        } elseif (preg_match(
            "/^(?<hour>[\d]{2})(?<minute>[\d]{2})?(?<second>[\d]{2})?$tzRegex$/",
            $time, $match)) {
            $hour       = $match['hour'];
            $minute     = $match['minute'];
            $second     = $match['second'];
            $timezone   = $match['timezone'];
        } elseif (!empty($time)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'invalid time expression: %s given',
                $time
            ));
        }

        if ($timezone === 'Z') {
            $timezone = '+0000';
        }
        if (preg_match('/^[+-][\d]{2}$/', $timezone)) {
            $timezone .= '00';
        }
        if ($timezone === '') {
            $timezone = null;
        }

        self::validateDateTime($year, $month, $day, $hour, $minute, $second);

        if ($year === '') $year = null;
        if ($month === '') $month = null;
        if ($day === '') $day = null;
        if ($hour === '') $hour = null;
        if ($minute === '') $minute = null;
        if ($second === '') $second = null;

        return array(
            'year'      => $year,
            'month'     => $month,
            'day'       => $day,
            'hour'      => $hour,
            'minute'    => $minute,
            'second'    => $second,
            'timezone'  => $timezone,
        );
    }

    /**
     * parse a date
     *
     * @param string $date
     * @return @see self::parseDateTime
     */
    public static function parseDate($date)
    {
        return self::parseDateTime($date);
    }

    /**
     * parse a time
     *
     * @param string $time
     * @return @see self::parseDateTime
     */
    public static function parseTime($time)
    {
        if (strpos($time, 'T') === false) {
            $time = "T$time";
        }
        return self::parseDateTime($time);
    }

    /*
     * create ISO 8601 date-and-or-time from partial / complete datetime
     * reverse of self::parseDateTime
     *
     * @param string|null $year
     * @param string|null $month
     * @param string|null $day
     * @param string|null $hour
     * @param string|null $minute
     * @param string|null $second
     * @param string|null $timezone
     * @return string
     */
    public static function createDateTime(
        $year = null, $month = null, $day = null,
        $hour = null, $minute = null, $second = null, $timezone = null)
    {
        foreach (array(
            'year', 'month', 'day', 'hour', 'minute', 'second', 'timezone'
        ) as $var) {
            if ($$var === '') {
                $$var = null;
            }
        }

        self::validateDateTime($year, $month, $day, $hour, $minute, $second);

        $dtString = '';

        if (!is_null($year) && !is_null($month) && !is_null($day)) {
            $dtString .= sprintf('%04d', $year)
                       . sprintf('%02d', $month)
                       . sprintf('%02d', $day);
        } elseif (!is_null($year) && !is_null($month)) {
            $dtString .= sprintf('%04d', $year)
                       . '-' . sprintf('%02d', $month);
        } elseif (!is_null($year)) {
            $dtString .= sprintf('%04d', $year);
        } elseif (!is_null($month) && !is_null($day)) {
            $dtString .= '--'
                       . sprintf('%02d', $month)
                       . sprintf('%02d', $day);
        } elseif (!is_null($day)) {
            $dtString .= '--'
                       . '-'
                       . sprintf('%02d', $day);
        }

        if (!is_null($hour) || !is_null($minute) || !is_null($second)) {
            $dtString .= 'T';
        }

        if (!is_null($hour) && !is_null($minute) && !is_null($second)) {
            $dtString .= sprintf('%02d', $hour)
                       . sprintf('%02d', $minute)
                       . sprintf('%02d', $second);
        } elseif (!is_null($minute) && !is_null($second)) {
            $dtString .= '-'
                       . sprintf('%02d', $minute)
                       . sprintf('%02d', $second);
        } elseif (!is_null($second)) {
            $dtString .= '-'
                       . '-'
                       . sprintf('%02d', $second);
        } elseif (!is_null($hour)) {
            $dtString .= sprintf('%02d', $hour);
            if (!is_null($minute)) {
                $dtString .= sprintf('%02d', $minute);
            }
        }

        if (!is_null($timezone)) {
            if ($timezone[0] !== '+' && $timezone[0] !== '-') {
                $dtString .= '+';
            }
            if (preg_match('/^[+-](?:0{2}|0{4})$/', $timezone)) {
                $timezone = 'Z';
            }
            $dtString .= $timezone;
        }

        return $dtString;
    }

    /**
     * create ISO 8601 date from partial / complete date
     * reverse of self::parseDate
     *
     * @param string|null $year
     * @param string|null $month
     * @param string|null $day
     * @return @see self::createDateTime
     */
    public static function createDate(
        $year = null, $month = null, $day = null)
    {
        return self::createDateTime($year, $month, $day);
    }

    /**
     * create ISO 8601 time from partial / complete time
     * reverse of self::parseTime
     *
     * @param string|null $hour
     * @param string|null $minute
     * @param string|null $second
     * @param string|null $timezone
     * @return @see self::createDateTime
     */
    public static function createTime(
        $hour = null, $minute = null, $second = null, $timezone = null)
    {
        return self::createDateTime(null, null, null,
            $hour, $minute, $second, $timezone);
    }

    /**
     * try to create a timestamp from partial or complete date. possible if
     *  - at least year, month and day present
     * assumptions:
     *  - hour, minute, second: assume '00' if not given
     *  - timezone: assume script timezone if not given
     * otherwise:
     *  return null
     *
     * @param string|null $year
     * @param string|null $month
     * @param string|null $day
     * @param string|null $hour
     * @param string|null $minute
     * @param string|null $second
     * @param string|null $timezone
     * @return int|null
     */
    public static function createTimestamp(
        $year = null, $month = null, $day = null,
        $hour = null, $minute = null, $second = null, $timezone = null)
    {
        if (is_null($year) || is_null($month) || is_null($day)) {
            return null;
        }

        $format = 'Y-m-d';
        $timeString = "$year-$month-$day";
        if (!is_null($hour)) {
            $format .= ' H:i:s';
            $timeString .= " $hour";
            $timeString .= !is_null($minute) ? ":$minute" : ':00';
            $timeString .= !is_null($second) ? ":$second" : ':00';

            if (!is_null($timezone)) {
                $format .= 'T';
                $timeString .= $timezone;
            }
        }

        $dateTime = \DateTime::createFromFormat($format, $timeString);
        return $dateTime->getTimestamp();
    }

    /*
     * validates a partial / complete datetime
     *
     * @param string|null $year
     * @param string|null $month
     * @param string|null $day
     * @param string|null $hour
     * @param string|null $minute
     * @param string|null $second
     * @throws Exception\InvalidArgumentException
     * @return true
     */
    public static function validateDateTime(
        $year = null, $month = null, $day = null,
        $hour = null, $minute = null, $second = null)
    {
        $daysMap = array(
            1   => 31,
            2   => 29,
            3   => 31,
            4   => 30,
            5   => 31,
            6   => 30,
            7   => 31,
            8   => 31,
            9   => 30,
            10  => 31,
            11  => 30,
            12  => 31,
        );
        if (   ((!is_null($year) && !is_null($month) && !is_null($day))
                && (!checkdate($month, $day, $year)))
            || ((!is_null($year) && !is_null($month))
                && ($month < 1 || $month > 12))
            || ((!is_null($month) && !is_null($day))
                && (($month < 1 || $month > 12)
                    || $day > $daysMap[(int)$month]))
            || ((!is_null($day))
                && ($day < 1 || $day > 31))) {
            throw new Exception\InvalidArgumentException(sprintf(
                'invalid date: %s-%s-%s given',
                $year, $month, $day
            ));
        }

        if ((!is_null($hour) && ($hour < 0 || $hour > 23))
            || (!is_null($minute) && ($minute < 0 || $minute > 59))
            || (!is_null($second) && ($second < 0 || $second > 59))) {
            throw new Exception\InvalidArgumentException(sprintf(
                'invalid time: %s:%s:%s given',
                $hour, $minute, $second
            ));
        }

        return true;
    }
}
