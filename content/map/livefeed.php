<div id="geo-search">
	<div class="container">
  	<a href="#" class="button button-primary pull-left back-to-map">
  		<i class="fa fa-map-marker icon-left"></i> Back to map
  	</a>
		
		<a href="#" class="fa fa-refresh fa-2x pull-right live-feed" data-tooltip data-placement="left" title="Turn off live feed"></a>
		
		<h1 class="middle">Live Feed <span class="beta">beta</span></h1>
	</div>
</div>

<div id="globe"></div>

<div id="location-container" class="container">
	<div class="location-images row">
		<div class="location-center"><i class="fa fa-spin ion-load-c"></i></div>
	</div>
	<div class="clear"></div>
</div>

<script>
  $(document).ready(function() {
  	<?php $_set = isset($_GET['latitude'], $_GET['longitude'], $_GET['zoom']) && is_numeric($_GET['latitude']) && is_numeric($_GET['longitude']) && is_numeric($_GET['zoom']); ?>
		feed.load(<?php echo ($_set ? '{
      lat: ' . $_GET['latitude'] . ',
		  lng: ' . $_GET['longitude'] . ',
		  zoom: ' . $_GET['zoom'] . ',
		  address: ""
    }' : ''); ?>);
  });
</script>