var admin = {
	banIps: function () {
		var ips = $('#ip-textarea').val();
		
		ajax.connect ({
			url: '/api/admin/ip_ban',
			type: 'POST',
			data: { ip: ips },
			dataType: 'json',
			loader: true,
			done: function (data) {
		    if(typeof data.error === 'undefined')
		    {
		      functions.toast(data.success, true);
		    }
		    else
		    {
		      functions.toast(data.error, false);
		    }
			}
		});
	},
	
	moderateImage: function (id, state) {
		var comment = $('#reject-reason').val();
		
		ajax.connect ({
			url: '/api/admin/image_moderate',
			type: 'POST',
	    data: {'id' : id, 'state' : state, 'comment' : comment},
			dataType: 'json',
			loader: true,
			done: function (data) {
				if(typeof data.error === 'undefined')
		    {
		      functions.toast(data.success, true);
		      $('.moderate-image[data-image="' + id + '"]').slideUp('slow');
		    	$('#reject-reason').val('');
		    	$('#RejectImageModal').modal('hide');
		    	
		    	var curr = parseInt($('#mod-count').text())
		    	$('#mod-count').text(curr - 1);
		    	
		    	$('.moderate-container').animate({ 'opacity' : 0 }, 100, function() {
		    		admin.loadImage();
		    	})
		    }
		    else
		    {
		      functions.toast(data.error, false);
		    }
			}
		});
	},
	
	spotlight: function (id) {
		ajax.connect ({
			url: '/api/admin/image_spotlight',
			type: 'POST',
			data: { id: id },
			dataType: 'json',
			loader: true,
			done: function (data) {
				if(typeof data.error === 'undefined')
		    {
		      functions.toast(data.success, true, function() {
		      	location.reload();
		      });
		    }
		    else
		    {
		    	functions.toast(data.error, false);
		    }
			}
		});
	},
	
	loadImage: function () {
		ajax.connect ({
			url: '/hq/controls/moderation',
			type: 'POST',
	    data: { ajax: true },
			dataType: 'html',
			loader: true,
			done: function (data) {
				$('.moderate-container').html(data).animate({ 'opacity' : 1 }, 100);
			}
		});
	},
	
	deleteReport: function (id) {
		ajax.connect ({
			url: '/api/admin/user_delete_report',
			type: 'POST',
			data: {'id' : id},
			dataType: 'json',
			loader: true,
			done: function (data) {
				if (typeof data.error === 'undefined') {
					functions.toast(data.success, true);	
		      $('.user-report[data-report="' + id + '"]').slideUp('slow');
				}

				else {
					functions.toast(data.error, false);
				}
			}
		});
	},
	
	resort: {
		deleteRequest: function (id) {
			ajax.connect ({
				url: '/api/admin/resort_request_delete',
				type: 'POST',
		    data: { 'id': id },
				dataType: 'json',
				loader: true,
				done: function (data) {
					if(typeof data.error === 'undefined')
			    {
			      $('.resort-request[data-request="' + id + '"]').slideUp('slow');
			      functions.toast(data.success, true);
			    }
			    else
			    {
			      functions.toast(data.error, false);
			    }
				}
			});
		},
		
		add: function () {
			var resort = $('#resort-name').val();
		  var country = $('#country-id').val();
		  var latitude = $('#latitude').val();
		  var longitude = $('#longitude').val();
		  var conf = confirm('Are you sure?');

		  if(! conf)
		  	return;
			 
			ajax.connect ({
				url: '/api/admin/resort_add',
				type: 'POST',
	      data: {'resort' : resort, 'country_id' : country, 'latitude' : latitude, 'longitude' : longitude},
				dataType: 'json',
				loader: true,
				before: function () { $('#resort-alert').addClass('hidden'); },
				done: function (data) {
					if(typeof data.error === 'undefined')
		      {
		        functions.toast(data.success, true);
		        $('#AddResortModal').modal('hide');
		        $('#resort-alert').addClass('hidden');
		        
		        $('#resort-name').val('');
		        $('#country-id').val('');
		        $('#latitude').val('');
		        $('#longitude').val('');
		        $('#temp-location').html('');
		      }
		      else
		      {
		        $('#resort-alert').removeClass('hidden');
		        $('#resort-alert p').html(data.error);
		      }
				}
			});
		}
	},
	
	sitemap: function () {
		ajax.connect ({
			url: '/api/admin/sitemap',
			type: 'POST',
			data: {},
			dataType: 'json',
			loader: true,
			done: function (data) {
				functions.toast("Well we reached the success stage...", true);	
			}
		});
	},
	
	user: {
		enable: function (id) {
			ajax.connect ({
				url: '/api/admin/user_enable_disable',
				type: 'POST',
		    data: { id: id },
				dataType: 'json',
				loader: true,
				done: function (data) {
					if(typeof data.error === 'undefined')
			    {
			      functions.toast(data.success, true);
			      var el = $('.enable-user[data-id="' + id + '"]');  
			    	el.addClass('hidden');    
			    	el.prev('a').removeClass('hidden');  
			    }
			    else
			    {
			      functions.toast(data.error, false);
			    }
				}
			});
		},
		
		disable: function (id, comment) {
			if(comment.length == 0) { alert('Please enter a message!'); return; }
			
			ajax.connect ({
				url: '/api/admin/user_enable_disable',
				type: 'POST',
		    data: { id: id, comment: comment },
				dataType: 'json',
				loader: true,
				done: function (data) {
					if(typeof data.error === 'undefined')
			    {
			      functions.toast(data.success, true);
			      var el = $('.disable-user[data-id="' + id + '"]');  
			    	el.addClass('hidden');    
			    	el.next('a').removeClass('hidden');  
			    	$('#disable-comment').val('');
			    	$('#DisableUserModal').modal('hide');
			    }
			    else
			    {
			      functions.toast(data.error, false);
			    }
				}
			});			
		}
	},
	
	lookup: function () {
		var location = $('#resort-name').val();

		ajax.connect ({
      url: "https://maps.googleapis.com/maps/api/geocode/json",
			type: 'GET',
      data: { address: location },
			dataType: 'json',
			loader: true,
			done: function (data) {
				if(data.status == 'OK')
	      {
	        if(data.results.length > 0) 
	        {
	          var lat = data.results[0].geometry.location.lat;
	          var lng = data.results[0].geometry.location.lng;
	          var name = data.results[0].address_components[0].long_name;
	          var country;

	          var leng = data.results[0].address_components.length;

	          for(var i=0; i<data.results[0].address_components.length; i++) 
	          {
	            for(var b=0;b<data.results[0].address_components[i].types.length;b++) 
	            {
	              if(data.results[0].address_components[i].types[b] == "country") {
	                country = data.results[0].address_components[i];  
	                break;
	              }
	            }
	          }
	          
	          var code = country.short_name;

	          $('#resort-name').val(name);
	          $('#latitude').val(lat);
	          $('#longitude').val(lng);
	          $('#country-id').val(code);
	          $('#temp-location').html(' ' + data.results[0].formatted_address);
	        }

	        else
	        {
	          functions.toast('No Results', false);
	        }

	      }
			},
			fail: function () {
	      functions.toast('Invalid Location', false);
			}
		});
	}
}

$(document).ready(function(e){
  $(document).on('click', '.approve-image', function(e){
    var id = $(this).data('image');
    admin.moderateImage(id, true);
    e.preventDefault();
  });

  $(document).on('click', '.reject-image', function(e){
    var id = $(this).data('image');
    $('#reject-image').attr('data-image', id);
    e.preventDefault();
  });
  
  $(document).on('submit', '#reject-image', function(e){  	
  	var id = $(this).attr('data-image');  	
  	admin.moderateImage(id, false);
    e.preventDefault();
  });
  
  $(document).on('click', '.delete-report', function(e){
    var id = $(this).data('report');
    admin.deleteReport(id);
    e.preventDefault();
  });
  
  $(document).on('click', '.add-to-spotlight', function(e){
  	var c = confirm('Are you sure?');
  	if (c) {
	    var id = $(this).data('image');
	    admin.spotlight(id);	    
  	}
    e.preventDefault();
  });
  
  $(document).on('click', '.enable-user', function(e){
    var id = $(this).data('id');
    admin.user.enable(id);
    e.preventDefault();
  });
  
  $(document).on('click', '.disable-user', function(e){
    var id = $(this).data('id');
    $('#disable-user').attr('data-id', id);
    e.preventDefault();
  });
  
  $(document).on('click', '#generate-sitemap', function(e){
  	admin.sitemap();
    e.preventDefault();
  });
  
  $(document).on('change', '.reason-box', function(e){
		$('#reject-reason').val($(this).val());
	});
  
  $(document).on('submit', '#disable-user', function(e){  	
  	var id = $(this).attr('data-id');  	
  	var comment = $('#disable-comment').val();
  	admin.user.disable(id, comment);
    e.preventDefault();
  });

  $(document).on('submit', '#ip-form', function(e){
    admin.banIps();
    e.preventDefault();
  });

  

  $(document).on('click', '.delete-request', function(e){
    var id = $(this).data('request');
    admin.resort.deleteRequest(id);
    e.preventDefault();
  });

  $('#resort-form').on('submit', function(e){
    var validated = form.validate($(this));

    if(validated)
    {
      admin.resort.add();
    }

    e.preventDefault();
  });


  $(document).on('click', '#geo-lookup', function(e){
    admin.lookup();

    e.preventDefault();
  });
});
