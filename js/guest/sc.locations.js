var globe = {
		map: null,
		searching: false,
		reset: true,
		initialised: false,
		marker: null,
		coords: {},
		location: function () { return $('#coords').val(); },
		
		load: function(opts, withEvents) {
			
			// Set opts
			this.reset = ! opts;
			this.coords = opts ? opts : { lat: 36.235412, lng: 0, zoom: 3, address: '' }
			
			$('#coords').val(this.coords.address);
			
			// Create map
			this.map = new google.maps.Map(document.getElementById('globe'), {
				zoom : this.coords.zoom,
				center : new google.maps.LatLng(this.coords.lat, this.coords.lng),
				streetViewControl: false
			});
			
			// Add event to map click
			if (withEvents)
			{
				var me = this;
				google.maps.event.addListener(this.map, 'click', function(e) {
					me.coords.lat = e.latLng.lat();
					me.coords.lng = e.latLng.lng();
					me.coords.zoom = me.map.getZoom();
			    me.addMarker(true, true);
				});
			}
			
			if (! this.reset)
			{
				this.addMarker(false, withEvents);
			}

			if (! this.initialised)
			{
				this.loadEvents();
				this.initialised = true;
			}
		},	
		
		addMarker: function (animate, popup) {
			if (this.marker)
				this.marker.setMap(null);
			
			var latlng = new google.maps.LatLng(this.coords.lat, this.coords.lng);
			this.marker = new google.maps.Marker({
	        position: latlng, 
	        map: this.map,
	        animation: (animate ? google.maps.Animation.DROP : false),
	        icon: '/template/images/map-marker.png'
	    });		    
			
			// Add info window
			if (popup)
			{
		    var me = this;
				var content = "";
				content += "<div id='location-popup'>";
				content += "<h4><strong>Pictures</strong>: <span class='count'><i class='fa ion-load-c fa-spin'></i></span></h4>";
				content += "</ul>";
				content += "<a class='button button-small block' href='/map/" + '@' + this.coords.lat + ',' + this.coords.lng + ',' + this.coords.zoom + "/live'>Live Feed</a>";
				content += "</div>";
				
			  var infowindow = new google.maps.InfoWindow();
		    infowindow.setContent(content);
		    setTimeout(function() { infowindow.open(me.map, me.marker); me.loadPopup(); }, (! this.initialised ? 250 : 0));
		    
		    google.maps.event.addListener(this.marker, 'click', (function(marker, content) {
		      return function() {
		        infowindow.open(me.map, marker);
		        me.loadPopup();
		      }
		    })(this.marker, content));
		    
	    	ajax.URL((this.reset ? '/map/' : '') + '@' + this.coords.lat + ',' + this.coords.lng + ',' + this.coords.zoom);
			}
			
			this.map.panTo(latlng);
		},
		
		lookup: function () {
			var me = this;
			
			if (me.looking)
				return;
			
		  if (me.location() == '')
		  {
		  	me.load();
		  	return;
		  }
		  
		  ajax.connect ({
				url: "/api/common/geocoder",
				type: 'POST',
				data: { 'location' : me.location() },
				dataType: 'json',
				loader: false,
				
				before: function () {
					me.looking = true;
		      $('#coords').addClass('loading');
				},
				
				always: function () {
					me.looking = false;
			  	$('#coords').removeClass('loading');
				},
				
				fail: function () {
					functions.toast('Invalid Location', false);
				},
				
				done: function (data) {
					if(data.result)
			    {
			      me.load({
			      	lat: data.result.lat, 
			      	lng: data.result.lng, 
			      	zoom: data.result.zoom, 
			      	address: data.result.address
			      }, true)
			    }
			    else
			    {
			      functions.toast('No Results', false);
			    }
				}
			});
		},
		
		loadPopup: function () {
			var me = this;
			ajax.connect ({
				url: "/api/image/by_geo?count=true",
				type: 'POST',
				data: me.coords,
				dataType: 'json',
				loader: false,
				
				fail: function () {
					functions.toast('Invalid Location', false);
				},
				
				always: function () {
					me.looking = false;
			  	$('#coords').removeClass('loading');
				},
				
				done: function (data) {
					if(data)
			    	setTimeout(function() { $('#location-popup .count').html(data.count) }, 50);
				}
			});
		},
		
		loadEvents: function () {
			var me = this;
			
			$(document).on('submit', '#geo-search', function(e){
				me.lookup();
				e.preventDefault();
			});
		}
};

var feed = {
	initialised: false,
	coords: {},
	timestamp: '',
	livefeed: true,

	load: function (opts) {
		if (! opts)
			return;
		
		this.coords = opts ? opts : { lat: 36.235412, lng: 0, zoom: 3, address: '' };
		this.fetch();
		
		globe.load(opts, false);
		
		if (! this.initialised)
			this.loadEvents();
	},
		
	fetch: function (autoload, latest) {
		// Stop if livefeed is off
		if (latest && ! this.livefeed)
			return;
		
		// Store holder and curr page
		var me = this;
		var holder = $('.location-images');
		var page = $('.location-more').attr('data-page');
		page = ! page ? 1 : parseInt(page);
		
		// If we're getting the latest images add to post data
		if (latest) 
			me.coords.latest = me.timestamp;

		// Lookup images at this region
		ajax.connect ({
			url: "/api/image/by_geo" + (! latest ? "?page=" + page : ""),
			type: 'POST',
			data: me.coords,
			dataType: 'json',
			loader: false,
			
			before: function () {
				if (latest)
	    		$('.live-feed').addClass('fa-spin');
			},
			
			always: function () {
				if (latest)
	    		$('.live-feed').removeClass('fa-spin');
			},
			
			done: function (data) {
				if (data.html == '' && ! autoload)
					data.html = '<div class="location-center">Cannot find any pictures :(</div>';
		  	
				if (autoload) 
				{
					if (latest)
						holder.prepend(data.html);
					else
						holder.append(data.html);
				}
				
				else
				{
					holder.fadeOut(250, function() { holder.html(data.html); holder.fadeIn(250); });
				}
			  
				if (! latest)
				{
			  	$('#more-images').remove();
				  if (data.more) 
				  {
				  	var btn = '<div id="more-images" class="more-images clear">' +
				  							'<a href="#" class="button spinner-load location-more" data-page="' + (page + 1) + '">' + 
				  								'Load more <i class="fa ion-load-c hidden icon-right"></i>' +
				  							'</a>' +
			  							'</div>';
				  	holder.append(btn);
				  }
				}
		  	
				// Wait for dom
				setTimeout(function() { $('.image').addClass('loaded') }, 750);
			  functions.lazyLoad(750);
			  
				me.timestamp = data.timestamp;
				setTimeout(function() {
					me.fetch(true, true);
				}, 5000);
			}
		});
	},
	
	loadEvents: function() {
		var me = this;
		
		$('.back-to-map').attr('href', window.location.href.replace("/live", ""));
		
		$(document).on('click', '.location-more', function (e) {
			me.fetch(true);
			
			e.preventDefault(); 
		});
		
		$(document).on('click', '.live-feed', function (e) {
			me.livefeed = ! me.livefeed;
			$(this).toggleClass('off');
			$(this).attr('data-original-title', (me.livefeed ? 'Turn off live feed' : 'Turn on live feed'));
			$(this).tooltip('hide');
			
			if (me.livefeed)
				me.fetch(true, true);
			
			e.preventDefault(); 
		});
	}
}