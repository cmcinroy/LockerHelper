var main = {
	count: 0,
	components: null, // array of UI data components
	defaultInterval: 5000, // 30 seconds
	defaultTimeFormat: 'HH:mm', // default format: 24hr clock
	defaultDateFormat: 'LL', // default format: long date
	dateLocation: '.date',
	timeLocation: '#time',
	config: null, // configuration info
	intervalId: undefined,
	startTime: null,
	gitHash: undefined,
	reload: false,
};

const DAY_MILLIS = 1000 * 60 * 60 * 24;

jQuery.fn.updateWithText = function(text, speed)
{
	var dummy = $('<div/>').html(text);

	if ($(this).html() != dummy.html())
	{
		$(this).fadeOut(speed/2, function() {
			$(this).html(text);
			$(this).fadeIn(speed/2, function() {
				//done
			});
		});
	}
}
							
$(document).ready(function(){

	// initialize variables
	main.components = new Array();
	// get the list of data components on the page
	$("div[class*='data']").each(function() {
		main.components.push(this.id);
	})
	main.startTime = new Date().getTime();

	main.updateData();
});

/**
 * Updates the various page components
 */
main.updateData = function () {
	// determine list of components to be included in request
	var intervalList = this.getComponentsByInterval();
	// determine polling interval
	var delay = this.getConfig('checkInterval');
	console.log(delay, intervalList.length);

	if (delay > 0) {
		// if there are any components to be updated
		if ( intervalList.length > 0 ) {
			console.log(new Date().getTime() - this.startTime,': At count ', this.count, ' updating ', intervalList.toString());
			// get data from server
			this.doAjax(intervalList);
		}
		// set callback function to perform next update
		this.intervalId = setTimeout(this.updateData.bind(this), delay);
	} else {
		// interval of <= 0 means turn off refresh
		if (this.intervalId)  {
			// console.log(new Date().getTime() - this.startTime,': At count ', this.count, ' stopped updating');
			clearInterval(this.intervalId);
			this.intervalId = undefined;
		}
	}
	// increment or reset count
	if ( (new Date().getTime() - this.startTime) / DAY_MILLIS <= 1 ) {
		this.count++;
	} else {
		// reset the count if we have been running for more than a day
		this.count = 0;
		this.startTime = new Date().getTime();
	}

	// Reload the application if the local git repository has been updated
	if ( main.reload ) {
		console.log('***RELOAD***');
		window.location.reload();
		window.location.href = window.location.href;
	}
}
		
/**
 * Determine list of components
 */
main.getComponentsByInterval = function () {
	// 1st iteration: all data component should be requested
	// subsequent: determined by component interval alignment with count
	if ( this.count == 0 ) {
		return this.components;
	} else {
		var refList = new Array();
		// loop through configuration items
		for (var prop in this.config) {
			// if config item is an interval AND
			// the corresponding component exists on the page AND
			// the interval value is greater than zero AND
			// the refresh count is a multiple of the interval value
			if ((prop == null) || (prop === undefined)) {
				console.log('prop: ' + prop);
			}
			if ( prop.endsWith('.interval') &&
				 ($.inArray(prop.split('.')[0], this.components) !== -1) &&
				 this.config[prop] > 0 &&
				 this.count % this.config[prop] === 0 ) {
				// add the component to the refresh list
				refList.push(prop.split('.')[0]);
			}
		}
		return refList;
	}
}
		
/**
 * Obtain the data to be sent to the server and intiate Ajax
 * Borrowed from ajax/php tutorial here:
 * https://www.html5andbeyond.com/jquery-ajax-json-php/
 */
main.doAjax = function ( arr ) {
	var componentList, request;
				
	// convert list of components to a JSON array
	componentList = JSON.stringify( arr );
				
	// Pass the values to the AJAX request and specify callback function arg for 'done'
	request = this.theAjax(componentList);
	request.done(this.processData);

	request.fail(function( jqXHR, textStatus, errorThrown) {
		// Output error information
		console.log("Request failed: " + textStatus );
		$('#weather #day_summary').updateWithText('Internet connection unavailable',500);
	});

	// update time and date
	main.updateTime();
}

/**
 * Returns the jqXHR object
 */
main.theAjax = function ( arr ) {
		return $.ajax({
					url : 'data.php',
					type: 'POST',
					data:{ js_array: arr },
					dataType: 'json'
				});
}
		
/**
 * Determine what is done with the data when it is returned by the server
 */
main.processData = function (response /*textStatus, jqXHR*/) {

		console.log(response);
		// store the latest config info
		if ( typeof response.config != 'undefined' ) {
			main.config = response.config;
		}
		// Set reload flag if the local git repository has been updated
		if ( typeof main.gitHash == 'undefined' ) {
			main.gitHash = main.getConfig('gitHash');
		}
		if ( typeof main.gitHash != 'undefined' && 
			typeof main.getConfig('gitHash') != 'undefined' && 
			main.gitHash !== main.getConfig('gitHash') ) {
			main.gitHash = main.getConfig('gitHash');
			main.reload = true;
		}

		//TODO CLEAN-UP
		// - iterate on response
		// - all updates could use reflection??

		// process weather data (if it is in the response)
		if ( typeof response.weather != 'undefined' ) {
			main.updateWeather(response.weather);
		}

		// process quotes data (if it is in the response)
		if ( typeof response.quotes != 'undefined' ) {
			main.updateQuote(response.quotes, Math.floor((Math.random() * 4)));
		}

		// process agenda data (if it is in the response)
		if ( typeof response.agenda != 'undefined' ) {
			main.updateAgenda(response.agenda);
		}

		// process announcement data (if it is in the response)
		if ( typeof response.announcements != 'undefined' ) {
			main.updateAnnouncements(response.announcements);
		}

		// process announcement data (if it is in the response)
		if ( typeof response.events != 'undefined' ) {
			main.updateEvents(response.events);
		}

		// process twitter data (if it is in the response)
		if ( typeof response.tweets != 'undefined' ) {
			main.updateTweets(response.tweets);
		}
		// $( "div" ).each(function( index, element ) {
		//     // element == this
		// 	console.log(this);
		//     // $( element ).css( "backgroundColor", "yellow" );
		//     // if ( $( this ).is( "#stop" ) ) {
		//     // 	$( "span" ).text( "Stopped at div index #" + index );
		//     // 	return false;
	 //    	// }
		// });			
}

/**
 * Updates the time that is shown on the page
 */
main.updateTime = function () {
	var _now = moment();
	var _date = _now.format(this.getConfig('dateFormat'));

	$(this.dateLocation).updateWithText(_date, 1000);

	seconds = 60 - (new Date()).getSeconds();
	setTimeout(function () {
		this.updateTime();
	}.bind(this), seconds*1000);
	$(this.timeLocation).html(_now.format(this.getConfig('timeFormat')));
}

/**
 * Updates the weather component
 */
main.updateWeather = function ( obj ) {
	$('#weather #current_temp').empty().append(obj.current_temp);
	$('#weather #img_current_icon').empty().append($('<img>', { 
	    src : "img/" + obj.img_current_icon + "_sm.png", 
	    alt : "img_current_icon", 
	    title : obj.img_current_icon
	}));
	$('#weather #day_summary').updateWithText(obj.day_summary,500);
	$('#weather #img_precip_icon').empty().append($('<img>', { 
	    src : "img/" + obj.img_precip_icon + "_sm.png", 
	    alt : "img_precip_icon", 
	}));
	$('#weather #day_precip').empty().append(obj.day_precip);
}

/**
 * Updates the quote component
 */
main.updateQuote = function ( obj, i ) {
	//TODO randomize one of the four available recent daily quotes
	// $('#quotes').empty().append('<span class="fa ' + this.getConfig('quotes.symbol') + 
	// 	'">&nbsp;</span>' + obj[0].description + '<br/>&ndash;' + obj[0].title);
	var content = '<span class="fa ' + this.getConfig('quotes.symbol') + '">&nbsp;</span>';
	content += obj[i].description + '<br/>&ndash;' + obj[i].title;

	$('#quotes').updateWithText(content, 2000);
}

/**
 * Updates the agenda component
 */
main.updateAgenda = function ( obj ) {
	var span = '<span class="fa ' + this.getConfig('agenda.symbol') + '">&nbsp;</span>';
	var num = 8;
	var content = '';

	for (i = 0; i < num; i++) {
		if (i) {
			content += '<br/>';
		}
		content += span + obj[i].time + '&nbsp;' + obj[i].subject;
	}
	$('#agenda').updateWithText(content, 3000);
}

/**
 * Updates the announcements component
 */
main.updateAnnouncements = function ( obj ) {
	var span = '<span class="fa ' + this.getConfig('announcements.symbol') + '">&nbsp;</span>';
	var num = 3;
	var content = '';

	for (i = 0; i < num; i++) {
		if (i) {
			content += '<br/>';
		}
		content += span + obj[i].text;
	}
	$('#announcements').updateWithText(content, 4000);
}

/**
 * Updates the events component
 */
main.updateEvents = function ( obj ) {
	var span = '<span class="fa ' + this.getConfig('events.symbol') + '">&nbsp;</span>';
	var content = '';

	if (Array.isArray(obj)) {
		for (var i in obj) {
			if (content != '') {
				content += '<br/>';
			}
			content += span + obj[i].date + '&nbsp;' + obj[i].title;
		}

	}
	$('#events').updateWithText(content, 3000);
}

/**
 * Updates the tweets component
 */
main.updateTweets = function ( obj ) {
	var span = '<span class="fa ' + this.getConfig('tweets.symbol') + '">&nbsp;</span>';
	var num = 4;
	var content = '';

	for (i = 0; i < num; i++) {
		if (i) {
			content += '<br/>';
		}
		content += span + obj[i].text;
	}
	$('#tweets').updateWithText(content, 4000);
}

/**
 * Returns the specified configuration item
 */
main.getConfig = function ( key ) {
	if ( this.config != null && this.config[key] != null ) {
		return this.config[key];
	} else {
		// return defaults if no other value is available
		switch ( key ) {
			case 'dateFormat':
				return this.defaultDateFormat;
				break;
			case 'timeFormat':
				return this.defaultTimeFormat;
				break;
			case 'checkInterval':
				return this.defaultInterval;
				break;
			default:
				return null;
		}
	}
}
