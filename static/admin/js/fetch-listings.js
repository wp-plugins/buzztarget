jQuery(document).ready(function($)
{
	//$('form#fetchListings').submit(function (e)
	$('input[id=fetchListingsSubmit]').click(function (e)
	{
		// Remove success/error msg if one is present
		if ($('#setting-error-settings_updated').length)
		{
			$('#setting-error-settings_updated').remove();
		}

		$('input#fetchListingsSubmit').val('Please Wait...').addClass('loading');
		$('div#loading').show();

		var interval = $('select[id=fetch-schedule-interval]').val();
		var time = $('select[id=fetch-schedule-time]').val();
		var cycle = $('select[id=fetch-schedule-cycle]').val();
		var weekday = $('select[id=fetch-schedule-weekday]').val();
		var monthDay = $('select[id=fetch-schedule-month-day]').val();

		var listingTypes = [];
		$("input[name='listing_types[]']:checked").each(function ()
		{
			listingTypes.push($(this).val());
		});

		var listingStatuses = [];
		$("input[name='listing_statuses[]']:checked").each(function ()
		{
			listingStatuses.push($(this).val());
		});

		$.post('#',
		{
			fetchListings: 'true',
			fetch_schedule_interval : interval,
			fetch_schedule_time : time,
			fetch_schedule_cycle : cycle,
			fetch_schedule_every_weekday : weekday,
			fetch_schedule_every_month_day : monthDay,
			listing_types : listingTypes,
			listing_statuses : listingStatuses
		},
		function()
		{

		})
		.done(function(data)
		{
			var result = $(data).find('#setting-error-settings_updated');
			$($(result).clone()).insertAfter('.wrap > h2');
			$('div#loading').hide();
			$('input#fetchListingsSubmit').val('Fetch Listings').removeClass('loading');
		});
		return false;
	});
});