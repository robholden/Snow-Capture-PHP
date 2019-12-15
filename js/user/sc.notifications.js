/**
 * 
 * Author: Robert Holden
 * Project: Snow Capture
 * 
 */
var Notifier = {
		total: 0,
		unread: 0,
		inactive: 0,
		connected: false,
		
		init: function () {
			this.fetch();
			this.loadEvents();
		},
		
		fetch: function () {
			if (this.connected) return;
			
			var me = this;
			ajax.connect ({
				url: "/api/notification/get",
				type: 'POST',
				data: { read: me.viewing() },
				dataType: 'json',
				loader: false,
				
				before: function () {
					if (! me.viewing()) 
		    		$('#notification-dashboard').html("<div class='first center'> <i style='margin-top: 105px;' class='fa ion-load-c fa-spin fa-2x' /></div>");
		    	
		    	me.connected = true; 
				},
				
				always: function () {
					me.connected = false;
				},
				
				done: function (data) {
					if (typeof data === 'undefined') return;
					
					var no_new = data.new_notifications;
					var html = data.html;
					var wait = (me.inactive < 1) ? data.wait : (data.wait * me.inactive);
					
					$('#notification-dashboard').html(data.html);
					
					me.updateNewCount(no_new);
					setTimeout(function() { me.fetch(); }, (wait * 1000));
				}
			});
		},
		
		updateNewCount: function(count) {
			$('#notification-count').text(count);
			if (count > 0) $('#open-notifications').addClass('unseen');
			else $('#open-notifications').removeClass('unseen');
		},
		
		viewing: function() {
			return $('#open-notifications').hasClass('enabled');
		},
		
		loadEvents: function() {
			var me = this;
			
			$(document).on('click', '#open-notifications', function (e){
				if ($(this).hasClass('enabled')) { me.updateNewCount(0); me.fetch(); } 
			});
			
			$(window).scroll(function(e) {    
				me.inactive = 0;
		  }); 
		  $(window).mousemove(function(e) {   
		  	me.inactive = 0;
		  }); 
			
			setInterval(function () {
				me.inactive = me.inactive + 1;
			}, 60000); 
		}
}

$(document).ready(function (){
	Notifier.init();
});