jQuery(document).ready(function() {

	foyer_display_setup_channel_scheduler();

});

function foyer_display_setup_channel_scheduler() {

	$start_datetime = jQuery('#foyer_channel_editor_scheduled_channel_start');
	$end_datetime = jQuery('#foyer_channel_editor_scheduled_channel_end');

	if (jQuery($start_datetime).length || jQuery($end_datetime).length) {

		// Only continue when datetime picker fields are present, datetimepicker will work with empty jQuery objects
		jQuery.foyer_datetimepicker.setLocale(foyer_channel_scheduler_defaults.locale);

		$start_datetime.foyer_datetimepicker({
			format: foyer_channel_scheduler_defaults.datetime_format,
			dayOfWeekStart : foyer_channel_scheduler_defaults.start_of_week,
			step: 15,
			onChangeDateTime: function(start) {
				if (start) {
					if (!$end_datetime.val() || new Date($end_datetime.val()) < start) {
						var new_end = new Date(start.getTime() + foyer_channel_scheduler_defaults.duration * 1000)
						$end_datetime.val(new_end.dateFormat(foyer_channel_scheduler_defaults.datetime_format));
					}
				}
			}
		});

		$end_datetime.foyer_datetimepicker({
			format: foyer_channel_scheduler_defaults.datetime_format,
			dayOfWeekStart : foyer_channel_scheduler_defaults.start_of_week,
			step: 15
		});

	}
}
