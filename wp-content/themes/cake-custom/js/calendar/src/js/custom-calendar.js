jQuery(function($) {
		$('#wrapper .version strong').text('v' + pignoseCalendar.VERSION);
		function onClickHandler(date, obj) {
			/**
			 * @date is an array which be included dates(clicked date at first index)
			 * @obj is an object which stored calendar interal data.
			 * @obj.calendar is an element reference.
			 * @obj.storage.activeDates is all toggled data, If you use toggle type calendar.
			 */

			var $calendar = obj.calendar;
			var $box = $calendar.parent().siblings('.box').show();
			var text = 'You choose date ';
			var chooseDate = '';

			if(date[0] !== null) {
				chooseDate = date[0].format('YYYY-MM-DD');
				text += date[0].format('YYYY-MM-DD');
			}

			if(date[0] !== null && date[1] !== null) {
				text += ' ~ ';
			} else if(date[0] === null && date[1] == null) {
				text += 'nothing';
			}

			if(date[1] !== null) {
				chooseDate = date[1].format('YYYY-MM-DD');
				text += date[1].format('YYYY-MM-DD');
			}

			var currentDate = moment().format('YYYY-MM-DD');
			if (!chooseDate)
			{
				$('.pignose-calendar-unit-date[data-date="'+currentDate+'"]').click();
			}
			else {
				$('#custom_order_pickup_date').val(chooseDate);
				
				if ($('[data-rangeslider]').length)
				{
					if (chooseDate == currentDate)
					{
						var currentHour = parseInt(moment().format("H"))+1;
						
						if(currentHour < 9){
							currentHour=9;
						}
						
						$('[data-rangeslider]').attr('min', currentHour);
						if (parseInt($('[data-rangeslider]').val()) <= currentHour)
						{
							
							$('[data-rangeslider]').val(currentHour);
							$('[data-rangeslider]').change()
						}
					}
					else {
						$('[data-rangeslider]').attr('min', 9);
					}
				}
				

				$('[data-rangeslider]').rangeslider('update', true);
			}

			
			$box.text(text);
		}

		// Default Calendar
		$('.calendar').pignoseCalendar({
			select: onClickHandler,
			lang: 'jp',
			date: moment().add(3, 'days'),
			initialize: true,
			disabledRanges: [
				['1011-10-05', moment().add(2, 'days').format('YYYY-MM-DD')],
			]
		});

		// Input Calendar
		$('.input-calendar').pignoseCalendar({
			buttons: true, // It means you can give bottom button controller to the modal which be opened when you click.
		});

		// Calendar modal
		var $btn = $('.btn-calendar').pignoseCalendar({
			modal: true, // It means modal will be showed when you click the target button.
			buttons: true,
			apply: function(date) {
				$btn.next().show().text('You applied date ' + date + '.');
			}
		});

		// Color theme type Calendar
		$('.calendar-dark').pignoseCalendar({
			theme: 'dark', // light, dark
			select: onClickHandler
		});

		// Multiple picker type Calendar
		$('.multi-select-calendar').pignoseCalendar({
			multiple: true,
			select: onClickHandler
		});

		// Toggle type Calendar
		$('.toggle-calendar').pignoseCalendar({
			toggle: true,
			select: function(date, obj) {
				var $target = obj.calendar.parent().next().show().html('You selected ' + 
				(date[0] === null? 'null':date[0].format('YYYY-MM-DD')) + 
				'.' +
				'<br /><br />' +
				'<strong>Active dates</strong><br /><br />' +
				'<div class="active-dates"></div>');

				for(var idx in obj.storage.activeDates) {
					var date = obj.storage.activeDates[idx];
					if(typeof date !== 'string') {
						continue;
					}
					$target.find('.active-dates').append('<span class="ui label default">' + date + '</span>');
				}
			}
		});

		// Disabled date settings.
		!(function() {
			// IIFE Closure
			var times = 30;
			var disabledDates = [];
			for(var i=0; i<times; /* Do not increase index */) {
				var year = moment().year();
				var month = 0;
				var day = parseInt(Math.random() * 364 + 1);
				var date = moment().year(year).month(month).date(day).format('YYYY-MM-DD');
				if($.inArray(date, disabledDates) === -1) {
					disabledDates.push(date);
					i++;
				}
			}

			disabledDates.sort();

			var $dates = $('.disabled-dates-calendar').siblings('.guide').find('.guide-dates');
			for (var idx in disabledDates) {
				$dates.append('<span>' + disabledDates[idx] + '</span>');
			}

			$('.disabled-dates-calendar').pignoseCalendar({
				select: onClickHandler,
				disabledDates: disabledDates
			});
		} ());

		// Disabled Weekdays Calendar.
		$('.disabled-weekdays-calendar').pignoseCalendar({
			select: onClickHandler,
			disabledWeekdays: [0, 6]
		});

		// Disabled Range Calendar.
		var minDate = moment().set('dates', Math.min(moment().day(), 2 + 1)).format('YYYY-MM-DD');
		var maxDate = moment().set('dates', Math.max(moment().day(), 24 + 1)).format('YYYY-MM-DD');
		$('.disabled-range-calendar').pignoseCalendar({
			select: onClickHandler,
			minDate: minDate,
			maxDate: maxDate
		});

		// Multiple Week Select
		$('.pick-weeks-calendar').pignoseCalendar({
			pickWeeks: true,
			multiple: true,
			select: onClickHandler
		});

		// Disabled Ranges Calendar.
		$('.disabled-ranges-calendar').pignoseCalendar({
			select: onClickHandler,
			disabledRanges: [
				['2016-10-05', '2016-10-21'],
				['2016-11-01', '2016-11-07'],
				['2016-11-19', '2016-11-21'],
				['2016-12-05', '2016-12-08'],
				['2016-12-17', '2016-12-18'],
				['2016-12-29', '2016-12-30'],
				['2017-01-10', '2017-01-20'],
				['2017-02-10', '2017-04-11'],
				['2017-07-04', '2017-07-09'],
				['2017-12-01', '2017-12-25'],
				['2018-02-10', '2018-02-26'],
				['2018-05-10', '2018-09-17'],
			]
		});

		// I18N Calendar
		$('.language-calendar').each(function() {
			var $this = $(this);
			var lang = $this.data('lang');
			$this.pignoseCalendar({
				lang: lang
			});
		});

		// This use for DEMO page tab component.
		$('.menu .item').tab();
	    //$('.timepick').timeslider();
	});
	//]]>