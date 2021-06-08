(function ($) {
	"use strict";

	// CountDown Js
	function time_remaining(endtime) {
		var t = Date.parse(endtime) - Date.parse(new Date());
		if (t < 0) {
			t = 0;
		}
		var seconds = Math.floor((t / 1000) % 60);
		var minutes = Math.floor((t / 1000 / 60) % 60);
		var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
		var days = Math.floor(t / (1000 * 60 * 60 * 24));
		return {
			'total': t,
			'days': days,
			'hours': hours,
			'minutes': minutes,
			'seconds': seconds
		};
	}

	function run_clock(id) {
		var clock = document.getElementById(id);
		if (clock == null) {
			return;
		}

		var start = $(clock).data("start");
		var end = $(clock).data("end");
		var endtime = start;

		// get spans where our clock numbers are held
		var days_span = clock.querySelector('.days');
		var hours_span = clock.querySelector('.hours');
		var minutes_span = clock.querySelector('.minutes');
		var seconds_span = clock.querySelector('.seconds');

		function update_clock() {
			if (start && Date.parse(start) >= Date.parse(new Date())) {
				$('#clock_status').html('<span style="color:yellow;">Start In</span>');
				endtime = start;
			} else if (end && Date.parse(end) >= Date.parse(new Date())) {
				$('#clock_status').html('<span style="color:orange;">End In</span>');
				endtime = end;
			} else {
				$('#clock_status').html('<span style="color:greenyellow;">Ended Successfully</span>');
				$('#round_balance').html("");
				endtime = new Date();
			}

			var t = time_remaining(endtime);
			// console.log(t);

			if (t.total <= 0) {
				// clearInterval(timeinterval);
				t = {
					'total': 0,
					'days': 0,
					'hours': 0,
					'minutes': 0,
					'seconds': 0
				};
			}

			// update the numbers in each part of the clock
			days_span.innerHTML = t.days;
			hours_span.innerHTML = ('0' + t.hours).slice(-2);
			minutes_span.innerHTML = ('0' + t.minutes).slice(-2);
			seconds_span.innerHTML = ('0' + t.seconds).slice(-2);


		}
		update_clock();
		var timeinterval = setInterval(update_clock, 1000);
	}
	run_clock('clockdiv');
})(jQuery);

function search_block(url_path) {
	var input_search_block = $('#input_search_block').val();
	if (!input_search_block) {
		return;
	}
	// console.log(url_path);
	location.href = url_path + input_search_block;
}

function get_nextdiff(url_path) {
	if (!url_path) {
		return;
	}
	$.ajax({
		url: url_path,
		type: 'POST',
		dataType: 'json',
		data: {
			action: "get_nextdiff"
		},
		success: function (data) {
			// console.log(data);
			if (data.s) {
				$('#nextdiff').text(data.d);
				$('.counter').counterUp({
					delay: 10,
					time: 1000
				});
			} else {
				// console.log(data);
				$('#nextdiff').text(data.e);
			};
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			console.log(XMLHttpRequest);
			console.log(textStatus);
			console.log(errorThrown);
			$('#nextdiff').text(textStatus);
		},
		complete: function (XMLHttpRequest, textStatus) {
			//console.log(XMLHttpRequest);
			//console.log(textStatus);
		}
	});
}