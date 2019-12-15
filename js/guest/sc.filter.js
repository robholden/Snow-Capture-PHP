/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */


var filter = {
	page: 2,
	tempPage: 1,
		
	autoload: {
		detect: function ()
		{
			var docH = parseInt($(document).height());
			var footerH = parseInt($('footer').height()) + 100;
			if (($(window).scrollTop() + $(window).height()) >= (docH - footerH))
			{
				filter.watch(true);
			}
		},
		
		check: function ()
		{
			if (this.can())
				this.detect();
		},
	
		can: function ()
		{
			var yes = false;
	
			if ($('#more-images').length > 0)
				if ($('#more-images').hasClass('autoload') > 0)
					yes = true;
	
			return yes;
		}
	},
	
	calChosenValues: function ()
	{
	  $('.filter-value.is-chosen').each(function(i, element) {
	    var el = $(element),
	        val = $.trim(el.data('value')),
	        text = el.text(),
	        choose = el.parent('.filter-values').parent('.filter-dropdown').next('.filter-choose'),
	        type = choose.data('type');

	    if (val != '')
	    {
	      var cl = 'fa-angle-double-right';
	      switch (type) 
	      {
	        case 'activity':
	          cl = 'fa-rocket';
	          break;
	        	
	        case 'altitude':
	          cl = 'fa-line-chart';
	          break;
	        	
	        case 'location':
	          cl = (el.hasClass('inner-value')) ? 'fa-map-pin' : 'fa-map-marker';
	          break;
	        	
	        case 'tag':
	          cl = 'fa-tag';
	          break;
	        	
	        case 'taken':
	          cl = 'fa-clock-o';
	          break;
	      }
	    
	      var newItem = '<p class="filter-chosen" data-value="' + val + '"><i class="fa ' + cl + ' margin-right-xs"></i>' 
	                    + text +
	                    '<a href="#" class="fa fa-times pull-right filter-chosen-remove"></a></p>';
	      
	      choose.children('.filter-chosen[data-value="' + val + '"]').remove();
	      choose.append(newItem);
	    }
	  });
	},
	
	clear: function ()
	{
	  $('.filter-value').removeClass('is-chosen');
	  $('.filter-chosen').remove();
	  this.watch(false);
	},

	tags: function (el)
	{
		var value = $.trim(el.val()),
				filtervalues = $('#tag-filter-values'),
				prev = el.attr('prev-search');
		
		prev = (typeof prev === 'undefined') ? '' : prev;
		if (value == prev) 
		{
			return;
		}
		
		if (value == '')
		{
			filter.toggleDropdown(el, false);
		}
		
		else
		{	
			ajax.connect ({
				url: '/api/common/tags',
				type: 'GET',
				data: { tag: value },
				dataType: 'json',
				loader: true,
				before: function () { 
					el.next('i').toggle().next('i').toggle(); 
				},
				done: function (data) {
					var html = '';
					
					for (var i = 0; i < data.tags.length; i++)
					{
						var tag = data.tags[i],
								selected = ($('div[data-type="tag"] p[data-value="' + tag + '"]').length > 0) ? 'is-chosen' : '';												
						
						html += '<a href="#" class="filter-value ' + selected + '" data-value="' + tag + '">' + tag + '</a>';
					}
					
					filter.toggleDropdown(el, (html != ''));
					filtervalues.html(html);
				},
				always: function () {
					el.next('i').toggle().next('i').toggle();
				}
			});
		}
		
		el.attr('prev-search', value);
	},
	
	toggleDropdown: function (el, state) 
	{
	  var parent = el.closest('.filter-dropdown'); 

	  if (typeof state === 'undefined')
	  {
	     parent.toggleClass('open');
	  }

	  else if (state) 
	  {
	    parent.addClass('open');
	  }

	  else
	  {
	    parent.removeClass('open');
	  }
	},
	
	values: function (el, keycode) 
	{
	  // Get values container
	  var values = el.parent('.filter-dropdown-toggle').next('.filter-values');
	  
	  // Are they typing or using the arrows?
	  if (keycode == 38 || keycode == 40 || keycode == 13)
	  {      
	    var selEl = values.children('.selected:not(.hidden)'),
	        isNull = selEl.length == 0;
	    
	    // Down
	    if (keycode == 40)
	    {
	      if (isNull) 
	      {
	        values.children('.filter-value:not(.hidden):first-child').addClass('selected');
	      }

	      else
	      {
	        selEl.removeClass('selected').next('.filter-value:not(.hidden)').addClass('selected');
	      }
	    }

	    // Up
	    else if (keycode == 38)
	    {
	      if (isNull) 
	      {  
	        var els = values.children('.filter-value:not(.hidden)').length;
	        values.children('.filter-value:not(.hidden):nth-child(' + els + ')').addClass('selected');
	      }

	      else
	      {
	        selEl.removeClass('selected').prev('.filter-value:not(.hidden)').addClass('selected');
	      } 
	    }

	    else if (!isNull)
	    {
	      selEl.click();
	    }

	    if(keycode == 38 || keycode == 40)
	    {
	      if (! isNull)
	      {
	        var allVals = values.children('.filter-value:not(.hidden)').length,
	            afterVals = selEl.nextAll('.filter-value:not(.hidden)').length,
	            selHeight = selEl.outerHeight(),
	            scroll = ((allVals - afterVals) * selHeight) - selHeight;
	        
	        values.stop().animate({
	          scrollTop: scroll + 'px'
	        }, 250);
	      } 
	    }
	  }

	  else 
	  {

	    var val = el.val(),
					prev = el.attr('prev-search');
	  	
	    prev = (typeof prev === 'undefined') ? '' : prev;
	  	if (val == prev) 
	  	{
	  		return;
	  	};

	    values.children('p').remove();
	    values.children('.filter-value').each(function(i, element) {
	      var curr = $(element);
	      var currVal = curr.attr('data-value').substring(0, val.length);
	      
	      var parentVal = curr.attr('data-parent');
	      parentVal = parentVal ? parentVal.substring(0, val.length) : '';

	      if (currVal.toLowerCase() == val.toLowerCase() || parentVal.toLowerCase() == val.toLowerCase()) 
	      {
	      	curr.removeClass('hidden');
	      }

	      else
	      {
	  			curr.addClass('hidden');
	      }
	      
	    });

	    if (values.children('.filter-value:not(.hidden)').length == 0)
	    {
	      values.append('<p class="padding">Nothing found...</p>');
	    }
	    
	    el.attr('prev-search', val);
	  }  
	},
	
	watch: function (autoload, initial)
	{
		// Ensure new page
	  if (this.page !== this.tempPage || (this.page == this.tempPage && autoload == false))
		{
	  	// Assign temp page to stop duplicate results
	  	this.tempPage = (autoload == false) ? 0 : this.page;  	
	  	
	  	$('#tag').blur();
	  	$('#location').blur();
	  	$('.search-btn').attr('disabled', 'disabled');
	  	
		  // Declare arrays
		  var locations = [];
		  var activities = [];
		  var altitudes = [];
		  var takens = [];
		  var tags = [];
		  var filter_array = [];
		
		  // Declare vars for ajax request
		  var value;
		  var location = '';
		  var tag = '';
		  var activity = '';
		  var altitude = '';
		  var keyword = typeof($('#keyword').val()) === 'undefined' ? '' : $('#keyword').val().trim();
		  var taken = '';
		  var type = $('#type').val().trim();
		  var user = $('#user').val().trim();
		
		  var sort = $('.toggle-sort.active').data('sort');
		
		  // Hide filter nav on mobile
		  if(mobile)
		  {
		    //hideNavs();
		  }
		  
		  // Get values
		  $('.filter-choose').each(function(index, element){
		  	var el = $(element),
		  			type = el.data('type');
		  	
		  	el.children('.filter-chosen').each(function(index, element){
			  	var value = $(element).data('value');
			  	
			  	switch(type.toLowerCase())
			  	{
			  		case 'activity':
			  			activities.push(value);
			  			break;
			  			
			  		case 'altitude':
			  			altitudes.push(value);
			  			break;
			  			
			  		case 'taken':
			  			takens.push(value);
			  			break;
			  			
			  		case 'location':
			  			locations.push(value);
			  			break;
			  			
			  		case 'tag':
			  			tags.push(value);
			  			break;
			  	}
			  });
		  });
		  
		 
		  // Build strings
		  // Locations
		  for (var i = 0; i < locations.length; i++) 
		  {
		    var separater = (i == 0) ? '' : '|';
		    location += separater + locations[i];
		  }
		
		  // Tags
		  for (var i = 0; i < tags.length; i++) 
		  {
		    var separater = (i == 0) ? '' : '|';
		    tag += separater + tags[i];
		  }
		
		  // Activities
		  for (var i = 0; i < activities.length; i++) 
		  {
		    var separater = (i == 0) ? '' : '|';
		    activity += separater + activities[i];
		  }
		
		  // Altitudes
		  for (var i = 0; i < altitudes.length; i++) 
		  {
		    var separater = (i == 0) ? '' : '|';
		    altitude += separater + altitudes[i];
		  }
		
	  	// Date Taken
		  for (var i = 0; i < takens.length; i++) 
		  {
		    var separater = (i == 0) ? '' : '|';
		    taken += separater + takens[i];
		  }
		
		  // Push to ajax data we want to send as string
		  // Keywords
		  if(keyword.length > 0)
		  {
		    filter_array.push(['q', keyword]);
		  }
		
		  // Location
		  if(location.length > 0)
		  {
		    filter_array.push(['location', location]);
		  }
		  
		  // Tag
		  if(tag.length > 0)
		  {
		    filter_array.push(['tag', tag]);
		  }
		
		  // Activity
		  if(activity.length > 0)
		  {
		    filter_array.push(['activity', activity]);
		  }
		
		  // Altitude
		  if(altitude.length > 0)
		  {
		    filter_array.push(['altitude', altitude]);
		  }
		
		  // Taken
		  if(taken.length > 0)
		  {
		    filter_array.push(['taken', taken]);
		  }
		
		  // Type
		  if(type.length > 0)
		  {
		    filter_array.push(['type', type]);
		  }
		  
		  // User
		  if(user.length > 0)
		  {
		    filter_array.push(['user', user]);
		  }
		
		  // Sort 
		  if(sort.length > 0)
		  {
		    filter_array.push(['sort', sort]);
		  }
		  
		  var method_obj = {};
		  if(autoload == true) 
		  {
		  	filter_array.push(['page', this.page]);
		  	method_obj['ajax'] = 'true';
		  } else {
		  	method_obj['dynamic'] = 'true';
		  }
		
		  var filter_string = "";
		  for (var i = 0; i < filter_array.length; i++) 
		  {
		    var separater = (i == 0) ? '?' : '&';
		    filter_string += separater + filter_array[i][0] + '=' + filter_array[i][1];
		  }
		  
		  var url_string = '';
		  for (var i = 0; i < filter_array.length; i++) 
		  {
		  	if(filter_array[i][0] !== 'type')
	  		{
			    var separater = (url_string == '') ? '?' : '&';
			    url_string += separater + filter_array[i][0] + '=' + filter_array[i][1];
	  		}
		  }
		 
		  var randomed = "";
		  if (sort == 'random')
	  	{
		  	$('.image').each(function(i, element){
		  		if (i != 0)
		  			randomed += "|"; 
		  		
		  		randomed += $(element).data('image');
		  	});
		  	
		  	method_obj['randomed'] = randomed;
	  	}
		  
		  ajax.connect ({
				url: "/content/views/image-display" + filter_string,
				type: 'POST',
				data: method_obj,
				dataType: 'json',
				loader: true,
				
				before: function () {
					if(!autoload)
		        $('#image-results').stop().animate({'opacity' : '0.5'}, 150);
				},
				
				always: function () {
					$('#image-results').animate({'opacity' : '1'}, 250);
			    $('.search-btn').removeAttr('disabled');
				},
				
				done: function (data) {						
					// Load images
			    $('#more-images').remove();
			    if(autoload == false || data.html.indexOf('no_images') == -1)
			    {          
			      if(autoload)
			      {
			        $('#image-results .image-holder-for-css').append(data.html);
			      }
			      else
			      {
			        $('#image-results').html(data.html);
			        functions.scrollTo('#image-bookmark', 250);
			      }
			
			      if(vars.mobile === false)
			      {
			        setTimeout(function(){ $('[data-tooltip]').tooltip(); }, 250);
			      }
			    }
			
			    else
			    {
			      $('#image-results').append("<div id='more-images' class='more-images clear'>Looks like that's all we have :(</div>");
			    }	     
			    
			    // Show maps?
		    	var mDiv = $('#map');
			    if (data.maps.show)
		  		{
			    	if (mDiv.length == 0)
			    		$('#filter-form').before('<div id="map"></div>');
			    	
			    	googleMap.filter(data.maps.data);
		  		}
			    
			    else if (mDiv.length > 0)
			    	mDiv.remove();
			  	
			  	// Add search text
			  	if (data.page == 1)
			  	{
			  		$('#number-results').html(data.text);
			  		
			  		// Show clear filter?
				  	if(data.filtered)
				      $('.clear-filters').addClass('opened');
				    else
				      $('.clear-filters').removeClass('opened');
			  	}
			  	
			  	// Fade in images
			  	functions.lazyLoad(500);
			  	
			    // Update paging
			  	filter.page = !parseInt(data.page) ? 1 : parseInt(data.page) + 1;
			  	
			  	// Hide/show filter
			  	if (! data.filtered && data.rows == 0)
			  		$('#filter-form').fadeOut(250);
			  	else
			  		$('#filter-form').fadeIn(250);
			  	
			  	// Update query string
			  	if (typeof data.querystring !== 'undefined' && typeof initial === 'undefined' && !autoload)
			  		ajax.URL(window.location.href.split('?')[0] + data.querystring);
			  	
				}
			});
		}
	}   
}


$(document).ready(function() {

	$(document).on('click', '.next-page', function(e)
	{
		filter.watch(true);

		e.preventDefault();
	});

	if ($('#image-results').is(':visible'))
	{
		filter.autoload.check();
	}
	
	$(document).on('scroll resize', function(e)
	{
		filter.autoload.check();
	});
	
	//Has filter be applied on load - if so reload results
	if($('.toggle-filter .filter-toggle').data('session') == true)
    filter.watch(false, true);
	
	// Scroll down
	if ($('#filter-set').val() == "true")
    setTimeout(function() { functions.scrollTo('#image-bookmark', 250); }, 500);
  
  
  /**
   * FILTER ACTIONS
   */
  
  // Event to reload results with new sort 
  $(document).on('click', '.toggle-sort', function(e){
    $('.toggle-sort').removeClass('active');
    $(this).addClass('active');
    dropdown.closeAll();

    var text = $(this).parent('li').prev('.separator').children('span').text();
    text = text == '' ? $(this).parent('li').prev('li').prev('.separator').children('span').text() : text;
    var sort = $.trim($(this).text());
    $('#sort-text').html(text + ' (' + sort + ')');
    
    filter.watch(false);
    e.preventDefault();
  });

  
  
  // Reload results
  $(document).on('click', '.update-filter', function(e) {
  	hideNavs();
    filter.watch(false);
    e.preventDefault();
  });

  
  
  
  // Reload results
  $(document).on('submit', '.form-filter', function(e) {
  	var val = $.trim($('#keyword').val());
  	if (val == '')
  	{
  		$('#keyword').focus();
  	} 
  	
  	else
  	{
  		filter.watch(false);
  	}
    
    e.preventDefault();
  }); 
  
  
  
  //Event to load results via ajax
  $(document).on('submit', '.ajax-to-search', function(e) {
  	filter.watch(false);  
    e.preventDefault(); 
  });
  
  
  
  // Event to clear filters
  $(document).on('click', '.clear-filters', function(e) {
    filter.clear();
    e.preventDefault();
  });
  
  
  
  
  /**
   * 
   * FILTER MENU
   * 
   */
  filter.calChosenValues();
  
  $(document).on('click', '.filter-toggle', function(e) {
  	$('#filter-holder').slideToggle(250);
  	$(this).toggleClass('opened');
  	$(window).trigger('resize'); 
  	e.preventDefault(); 
  });
  
  $(document).on('click', '.filter-value', function(e) {
    if ($(this).hasClass('is-chosen')) 
    {
    	e.preventDefault();
      return false;      
    }
    
    $(this).parent('.filter-values').prev('.filter-dropdown-toggle').children('input').val('');
    $(this).addClass('is-chosen');
    filter.calChosenValues();
    filter.watch(false);

    e.preventDefault();
  }); 
  
  $(document).on('click', '.filter-chosen-remove', function(e) {
    var p = $(this).parent('p'),
        val = $.trim(p.data('value'));

    $('.filter-value[data-value="' + val + '"]').removeClass('is-chosen');
  	p.fadeOut(250, function() {
      p.remove();
      filter.watch(false);
    });

    e.preventDefault();
  });

  $(document).on('focus', '.filter-input', function(e) {
    filter.toggleDropdown($(this));
    
    e.preventDefault();
  });

  $(document).on('blur', '.filter-input, .filter-input-tag', function(e) {
  	var el = $(this);
    setTimeout(function() {
    	el.removeAttr('prev-search');
    	filter.toggleDropdown(el, false);
  	}, 250);
    
    e.preventDefault();
  });
  
  $(document).on('keyup', '.filter-input', function(e) {
  	filter.values($(this), e.keyCode);
    
    e.preventDefault();
    return false;
  });    
  
  $(document).on('focus keyup', '#tag-input', function(e) {
  	filter.tags($(this));
    e.preventDefault();
  });  
	
});