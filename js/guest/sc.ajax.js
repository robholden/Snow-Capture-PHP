/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var ajax = {
	callbacks: function () {
		return { 
			before: function () {},
			done: function (data) {},
			fail: function () {},
			always: function () {}
		};
	},
	
	connect: function (obj, stop) {
		
		if (stop)
		{
			console.log(obj);
			return;
		}
		
		$.ajax({
	    type: obj.type,
	    url: obj.url,
	    beforeSend: function () {
	    	if (obj.before)
	    		obj.before();
	    	
	    	if (obj.loader)
	    		functions.loading(true); 
	    },
	    data: obj.data,
	    dataType: obj.dataType
		})
		
		.done(function(data){
	    if (obj.done)
	    	obj.done(data);
		})
		
		.always(function(){
			if (obj.always)
				obj.always();
    	if (obj.loader)
    		functions.loading(false); 
		})
		
		.fail (function(jqXHR) {
			if (obj.fail) 
				obj.fail();
			if (vars.debug) 
				console.log(jqXHR.responseText);
		});
	},
		
	URL: function(url)
	{
		window.history.pushState('', $(document).find("title").text(), $.trim(url));
	}
}