/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var googleMap = {
	filter: function (images, nearby)
	{	
		var lastImage = (images.length == 0) ? images[0] : images[images.length - 1];
	  var thislatlon = [lastImage.latitude, lastImage.longitude];
	  var mapOptions = { center: new google.maps.LatLng(thislatlon[0], thislatlon[1]), scrollwheel: false, zoom: (nearby ? 15 : 12) };

	  var map = new google.maps.Map(document.getElementById('map'), mapOptions);  	
	  var bounds = new google.maps.LatLngBounds();

	  var infowindow = new google.maps.InfoWindow(); 
	  
	  jQuery.get('/content/views/popup.html', function(data) {
		  for (var i = 0; i < images.length; i++) 
		  {     
		  	// Calc thumbnail w/h
		  	var thumbwidth = (nearby ? (i == (images.length - 1) ? 75 : 25) : 50); 
		    var thumbheight = (thumbwidth / 4) * 3; 
		  	
		  	// Get template from file
		  	var content = data;
		  	var currImage = images[i];
		  	content = content.replace(/##CAPTURE_LINK##/g, '/capture/' + currImage.displayID);
		  	content = content.replace(/##CAPTURE_NAME##/g, currImage.title);
		  	content = content.replace(/##CAPTURE_USER##/g, currImage.username);
		  	content = content.replace(/##CAPTURE_LOCATION##/g, currImage.location);
		  	
		  	// Dates
		  	var dates = [['##CAPTURE_TAKEN##', new Date(currImage.dateTaken)], 
	  	             	['##CAPTURE_UPLOADED##', uploaded = new Date((currImage.status == 4 ? currImage.datePublished : currImage.dateCreated))]];
		  	for (var d = 0; d < 2; d++)
		  	{
		  		var rgx 	= dates[d][0];
		  		var date 	= dates[d][1];
		  		var day 	= date.getDate().toString();
		  		var month = (date.getMonth() + 1).toString();
		  		var year 	= date.getFullYear().toString();
		  		
		  		// Prefix 0 to day/month?
		  		day = (day.length == 1) ? "0" + day : day;
		  		month = (month.length == 1) ? "0" + month : month;
		  		
		  		content = content.replace(rgx, day + '.' + month + '.' + year);
		  	}
		  	
		    var icon = new google.maps.MarkerImage(
	    		images[i].thumbnails['custom'],
	    		new google.maps.Size(thumbwidth, thumbheight),
		      new google.maps.Point(0, 0), 
		      new google.maps.Point(thumbwidth / 2, thumbheight / 2), 
		      new google.maps.Size(thumbwidth, thumbheight) 
		    );

		    var latlon = [images[i].latitude, images[i].longitude]; 
		    var myLatLng = new google.maps.LatLng(latlon[0], latlon[1]);
		    var imageMarker = new google.maps.Marker({
		        position: myLatLng,
		        map: map,
		        icon: icon
		    });	   
		    bounds.extend(myLatLng);             
		    
		    // Add info window
		    google.maps.event.addListener(imageMarker, 'click', (function(marker, content) {
		      return function() {
		        infowindow.setContent(content);
		        infowindow.open(map, marker);
		      }
		    })(imageMarker, content));
		  
		  }
		  
		  if (images.length > 1) {
	  		map.fitBounds(bounds);
		  }     
	  });
	},
	initialise: function (lat, lon, path, zoom) {
		var thumbwidth = 100;
		var thumbheight = (thumbwidth / 4) * 3;
		var mapOptions = {
			zoom : zoom,
			center : new google.maps.LatLng(lat, lon),
			scrollwheel : false
		};

		var map = new google.maps.Map(document.getElementById('map'), mapOptions);
		
		if (path) 
		{
			var icon = new google.maps.MarkerImage(path, new google.maps.Size(thumbwidth,
					thumbheight), new google.maps.Point(0, 0), new google.maps.Point(
					thumbwidth / 2, thumbheight / 2), new google.maps.Size(thumbwidth,
					thumbheight));
		
			var myLatLng = new google.maps.LatLng(lat, lon);
			var imageMarker = new google.maps.Marker({
				position : myLatLng,
				map : map,
				icon : icon
			});
		}
	}
}

$(document).ready(function() {

});