(function( $ ) {
	'use strict';

	// Add a function to use FullCalendar if the calendar div with the id calendar exists and the array events is not empty then initialize the calendar and pass the events array to it
	$(document).ready(function() {
		if ($('#calendar').length && events.length) {
			var calendarEl = document.getElementById('calendar');
			var todayDate = moment().startOf('day');
			var TODAY = todayDate.format('YYYY-MM-DD');
			var calendar = new FullCalendar.Calendar(calendarEl, {
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
				},
				height: 800,
				contentHeight: 780,
				aspectRatio: 3,

				nowIndicator: true,
				now : TODAY,

				views: {
					dayGridMonth: { buttonText: 'month' },
					timeGridWeek: { buttonText: 'week' },
					timeGridDay: { buttonText: 'day' },
					listWeek: { buttonText: 'list' }
				},

				initialView: 'dayGridMonth',
				initialDate: TODAY,

				editalble: false,
				navLinks: true,

				dayMaxEvents: true,
				events: events,
			});
			calendar.render();
		}
	});

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
