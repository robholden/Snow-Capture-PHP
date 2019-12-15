<?php $_set = isset($_GET['latitude'], $_GET['longitude'], $_GET['zoom']) && is_numeric($_GET['latitude']) && is_numeric($_GET['longitude']) && is_numeric($_GET['zoom']); ?>

<div id="geo-search">
	<div class="container">
		<h1>The Map <span class="beta">beta</span></h1>
  	<form id="geo-search" class="search-bar">
  		<input name="coords" id="coords" type="text" placeholder="Enter a location" autocomplete="off" />
  		<label for="coords"><i class="fa fa-map-marker"></i></label>
		</form>
	</div>
</div>

<div id="globe"></div>
 
<?php if (! $_set): ?>
<div id="globe-popup">
	<h4>Pick a location</h4>
	<p class="pull-left">Click anywhere on the map to place a marker</p>
	<a href="#" class="button button-small margin-left pull-right dismiss-globe-message">
		Got It
		<i class="fa fa-check icon-right"></i>
	</a>
	<div class="clear"></div>
</div>
<?php endif; ?>

<script>
	$(document).ready(function() {
		var geoH = $('#geo-search').height();
		var h = $(window).height() - (geoH + 60);
		$('#globe').height(h);

		<?php if (! $_set): ?>
		$('#globe-popup').addClass('opened');
		$(document).on('click', '.dismiss-globe-message', function(e) {
			$('#globe-popup').removeClass('opened');
			setTimeout(function(){
				$('#globe-popup').hide();
			}, 300);

			e.preventDefault();
		});
		<?php endif; ?>
				
		globe.load(<?php echo ($_set ? '{
      lat: ' . $_GET['latitude'] . ',
		  lng: ' . $_GET['longitude'] . ',
		  zoom: ' . $_GET['zoom'] . ',
		  address: ""
    }' : 'false'); ?>, true);
	});
</script>