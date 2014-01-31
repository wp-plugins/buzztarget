jQuery(document).ready(function($)
{
    // Initially check the interval value to hide or display only
    // the neccessary fields for this interval.
    var interval = $('select[id=fetch-schedule-interval]').val();
    handleFieldsForInterval(interval);

    /*
        When the fetch schedule interval's value changes
    */
    $('select[id=fetch-schedule-interval]').change(function()
    {
        var interval = this.value;

        handleFieldsForInterval(interval);
    });

    /*
        Handles showing/hiding various fields
        depending on the interval given (daily, weekly or monthly).
    */
    function handleFieldsForInterval(interval)
    {
        // Daily
        if (interval === 'daily')
        {
            toggleWeekDayTr('hide');
            toggleMonthDayTr('hide');
        }
        // Weekly
        else if (interval === 'weekly')
        {
            toggleWeekDayTr('show');
            toggleMonthDayTr('hide');
        }
        // Monthly
        else if (interval === 'monthly')
        {
            toggleWeekDayTr('hide');
            toggleMonthDayTr('show');
        }
    }

    /*
        Shows or hides the week day table row.
    */
    function toggleWeekDayTr(state)
    {
        var element = 'tr[id=bt-fetch-schedule-every-weekday-tr]';

        if (state === 'show')
        {
            if ($(element).is(':hidden'))
            {
                $(element).show();
            }
        }
        else if (state === 'hide')
        {
            if ($(element).is(':visible'))
            {
                $(element).hide();
            }
        }
    }

    function toggleMonthDayTr(state)
    {
        var element = 'tr[id=bt-fetch-schedule-every-month-day-tr]';

        if (state === 'show')
        {
            if ($(element).is(':hidden'))
            {
                $(element).show();
            }
        }
        else if (state === 'hide')
        {
            if ($(element).is(':visible'))
            {
                $(element).hide();
            }
        }
    }
});