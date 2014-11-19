<?php

global $listings;

add_filter('cron_schedules', 'buzztarget_cron_schedules');
add_action('init', 'buzztarget_schedule_fetch_listings_event');
add_action('buzztarget_fetch_listings_event', 'buzztarget_fetch_listings');

function buzztarget_cron_schedules(array $cronSchedules)
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
    $currentMonthTotalDays = buzztarget_get_month_total_days($month);

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

    $futureDate  = new DateTime($when, $timezone);

    $currentDate = new DateTime(null, $timezone);

    $interval    = $currentDate->diff($futureDate);

    $interval    = buzztarget_get_secs_from_interval($month, $interval);
        
    $cronSchedules['buzztarget_fetch_schedule'] = array(
        'interval' => $interval,
        'display' => __('BuzzTarget Fetch Schedule', 'buzztarget'),
    );

    return $cronSchedules;
}

function buzztarget_get_month_total_days($month)
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


function buzztarget_get_secs_from_interval($month, $interval)
{
    $currentMonthTotalDays = buzztarget_get_month_total_days($month);
    $seconds = (isset($interval->m) && is_int($interval->m)) ? $interval->m * $currentMonthTotalDays * 24 * 60 * 60 : 0;
    $seconds += (isset($interval->d) && is_int($interval->d)) ? $interval->d * 24 * 60 * 60 : 0;
    $seconds += (isset($interval->h) && is_int($interval->h)) ? $interval->h * 60 * 60 : 0;
    $seconds += (isset($interval->i) && is_int($interval->i)) ? $interval->i * 60 : 0;
    $seconds += (isset($interval->s) && is_int($interval->s)) ? $interval->s : 0;
    return $seconds;
}

/**
 * Schedules the fetch listings event.
 *
 * @since 1.1.0
 */
function buzztarget_schedule_fetch_listings_event()
{
    if (!wp_next_scheduled('buzztarget_fetch_listings_event'))
    {
        wp_schedule_event(time(), 'buzztarget_fetch_schedule', 'buzztarget_fetch_listings_event');
    }
}

/**
 * Fetches listings when the scheduled time has passed.
 *
 * @since 1.1.0
 *
 * @global Listings $listings Listings instance.
 */
function buzztarget_fetch_listings()
{
    global $listings;
    $listings->getListings();
}

buzztarget_fetch_listings();