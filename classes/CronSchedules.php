<?php

namespace BuzzTargetLive;

use DateTime;
use DateTimeZone;
use DateInterval;

class CronSchedules
{
    protected $text;

    public function __construct(Text $text)
    {
        $this->text = $text;

        add_filter('cron_schedules', array($this, 'cronSchedules'), 1);
    }

    /**
     * Calculates the interval between fetching listings depending on the fetch
     * schedule options.
     *
     * @access public
     *
     * @since 1.1.0
     *
     * @param array $cronSchedules Array of cron schedules.
     *
     * @return array Modified cron schedules.
     */
    public function cronSchedules(array $cronSchedules)
    {
        if (($fetchScheduleOptions = get_option('buzztarget_fetch_schedule_options')) === false)
        {
            return $cronSchedules;
        }

        $interval = $fetchScheduleOptions['interval'];

        // Chosen hour and cycle (AM/PM)
        $hour  = $fetchScheduleOptions['at'];
        $cycle = strtoupper($fetchScheduleOptions['at_cycle']);

        // Current year, month and day
        $year  = $fetchScheduleOptions['current_year'];
        $month = $fetchScheduleOptions['current_month'];
        $day   = $fetchScheduleOptions['current_day'];

        // Timezone 
        $timezoneString = get_option('timezone_string');
        if (empty($timezoneString))
            $timezoneString = 'EST';

        // Get timezone
        $timezone = new DateTimeZone($timezoneString);

        // Todays date
        $currentDate = new DateTime(null, $timezone);

        if ($interval === 'daily')
        {
            $currentHour = $currentDate->format('h');

            // Run on current day if that hour hasn't passed.
            if ($hour <= $currentHour)
                $day++;
        }
        elseif ($interval === 'weekly')
        {
            $currentDayAlpha = $fetchScheduleOptions['current_day_alpha'];

            $currentDay      = $fetchScheduleOptions['current_day'];

            $chosenWeekDay   = $fetchScheduleOptions['every_week_day'];

            if ($chosenWeekDay === $currentDayAlpha)
            {
                $day = $fetchScheduleOptions['day'];
            }
            else
            {
                $weekDays = array(
                    'Monday'    => 'Monday',
                    'Tuesday'   => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday'  => 'Thursday',
                    'Friday'    => 'Friday',
                    'Saturday'  => 'Saturday',
                    'Sunday'    => 'Sunday',
                );

                $currentDayIdx = $weekDays[$currentDayAlpha];

                $chosenDayIdx = $weekDays[ucfirst($chosenWeekDay)];

                $diff = absint($currentDayIdx - $chosenDayIdx);

                $day = $currentDay + $diff;
            }
        }
        elseif ($interval === 'monthly')
        {
            // Chosen day to fetch listings on each month
            $day = $fetchScheduleOptions['every_month_day'];
        }

        // Current month's total days
        $currentMonthTotalDays = $this->getMonthTotalDays($month);

        if ($day > $currentMonthTotalDays)
        {
            $day = 1;

            $month++;

            if ($month > 12)
            {
                $month = 1;
                $year++;
            }
        }

        // e.g. 2013-12-04 10:00:00AM
        $when = "{$year}-{$month}-{$day} {$hour}:00:00{$cycle}";

        $timezone = new DateTimeZone($timezoneString);

        $futureDate = new DateTime($when, $timezone);

        $currentDate = new DateTime(null, $timezone);

        $interval = $currentDate->diff($futureDate);

        $secs = $this->getSecondsFromInterval($month, $interval);

        error_log($secs);
            
        $cronSchedules['buzztarget_fetch_schedule'] = array(
            'interval' => $secs,
            'display' => $this->text->__('BUZZTARGET_FETCH_SCHEDULE'),
        );

        return $cronSchedules;
    }

    protected function getMonthTotalDays($month)
    {
        if ($month === 9 || $month === 4 || $month === 6 || $month === 11)
        {
            $totalDays = 30;
        }
        elseif ($month === 2)
        {
            $totalDays = 28;
        }
        else
        {
            $totalDays = 31;
        }

        return $totalDays;
    }

     /**
     * Returns the difference between two dates as seconds
     *
     * @param int           $month     The current month as an integer.
     *                                 Required to determine the accurate
     *                                 amount of days in a given month.
     * @param DateInterval  $interval  DateInterval instance representing
     *                                 the diff between two dates.
     *
     * @return int The amount of seconds.
     */
    protected function getSecondsFromInterval($month, DateInterval $interval)
    {
        serialize($interval);
        $currentMonthTotalDays = $this->getMonthTotalDays($month);
        $seconds = (isset($interval->m) && is_int($interval->m)) ? $interval->m * $currentMonthTotalDays * 24 * 60 * 60 : 0;
        $seconds += (isset($interval->d) && is_int($interval->d)) ? $interval->d * 24 * 60 * 60 : 0;
        $seconds += (isset($interval->h) && is_int($interval->h)) ? $interval->h * 60 * 60 : 0;
        $seconds += (isset($interval->i) && is_int($interval->i)) ? $interval->i * 60 : 0;
        $seconds += (isset($interval->s) && is_int($interval->s)) ? $interval->s : 0;
        return $seconds;
    }
}