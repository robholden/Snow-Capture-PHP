<?php

  require_once('../api/site.init.php');

  // Must be logged in to access
  $sc->session->privacyCheck(1);

  // Make sure url isset
  if(!$sc->common->image())
  {
    $sc->common->goNoWhere();
  }    

  // Verify image
  $image = $sc->common->image();
  if(!$image->exists() || ($sc->user->username != $image->user->username))
  {
    $sc->common->goNoWhere();
  }       

  // Is it processing / draft?
  $draft = $image->status == IMAGE_DRAFT ? true : false;
  $processing = $image->status == IMAGE_PROCESSING ? true : false;
  $private = $image->status == IMAGE_PRIVATE ? true : false;

  $nextDraftUrl = '/' . $sc->user->username . '/drafts';
  if ($draft)
  {
    $nextDraft = $image->getDraftForUser($sc->user);
    $nextDraftUrl = ! $nextDraft ? $nextDraftUrl : '/capture/' . $nextDraft->displayID . '/edit';
  }
 
  // Count likes
  $number_of_likes = $image->likes;

  // Have they liked it?
  $liked = $image->isLike($sc->user) ? true : false;

  // Get tags
  $image->getTags();

?>
<?php require_once('helpers/immediate.php'); ?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  
  <?php include('helpers/meta.php'); ?>

  <title>Edit Image | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>

<?php include('helpers/header.php'); ?>

<?php if($draft || $private || $processing): ?>
<section id="processing-message" <?php echo $draft || $private ? 'class="draft"' : ''; ?>>
  <div class="container">
    <p><?php echo $draft ? 'Draft' : ($private ? 'Private' : 'Processing'); ?></p>
  </div><!-- .container -->
</section>
<?php endif; ?>

<section id="top-bar" >
  <div class="container">
    <?php if(!$draft): ?>
      <a href="javascript:history.back()" class="button pull-left" tabindex="6">Cancel</a>
    
    <?php else: ?>
  	<?php if (! $sc->common->isMobile): ?>
      <a href="/<?php echo $sc->user->username ?>/drafts" class="button margin-right pull-left">
        <i class="fa fa-chevron-left icon-left"></i> Drafts
      </a>
      <?php endif; ?>
      <a href="#" class="delete-image button button-danger pull-left" data-url="<?php echo $nextDraftUrl; ?>" data-image="<?php echo $image->displayID; ?>" tabindex="6">
        Remove
      </a>
    <?php endif; ?>
    
    <button id="image-submit" 
    				data-url="<?php echo $processing ? '/' . $sc->user->username . '/processing' : ($private ? '/' . $sc->user->username . '/privates' : ($draft ? $nextDraftUrl : ($sc->common->hasPreviousPage() ? '' : '/capture/' . $image->displayID))); ?>" 
    				data-image="<?php echo $image->displayID; ?>"
            <?php echo $draft ? 'data-draft="true"' : ''; ?> 
    				type="submit" 
						class="button button-primary update-image margin-left pull-right btn-group"
    				form="edit-form">
    <?php if($draft): ?>
      Publish
    <?php else: ?>
      Finish
    <?php endif; ?>
      <i class="fa fa-check icon-right"></i>
    </button>
  
    <?php if($draft): ?>
      <button id="image-save" 
            data-image="<?php echo $image->displayID; ?>" 
            type="submit" class="button button-default update-image save margin-left pull-right btn-group"
            form="edit-form">
            Save <i class="fa fa-save icon-right"></i>
      </button>
    <?php else: ?>
      <a href="#" class="delete-image button button-danger pull-right" data-url="/<?php echo $sc->user->username; echo $processing ? '/processing"' : ($private ? '/privates"' : ''); ?>" data-image="<?php echo $image->displayID; ?>" tabindex="6" title="Remove Image" data-tooltip data-placement="left">
        <i class="fa fa-trash"></i>
      </a>
    <?php endif; ?>
  </div><!-- .container -->
</section><!-- #top-bar --> 
 
<main>
  <form id="edit-form" class="edit-image-form form" data-id="<?php echo $image->displayID; ?>" action="#" method="POST">
    <section>
      <div class="container text-center">        
          
        <div id="capture-img-container" class='edit lazy-container'>
        	<div id="img-select">
          	<img id="capture-img" class="edit lazy" src="/template/images/placeholder.jpg" data-src="<?php echo $image->filePath; ?>" data-tooltip data-placement="top" title="Drag over image to select a thumbnail" alt="<?php echo $image->title; ?>"/>
          </div>
          
        <?php if(!$sc->common->isMobile): ?>
        	<div id="thumbnail-container">
            <div id="preview">
              <img src="<?php echo $image->thumbnails['custom'] == '' ? $image->filePath : $image->thumbnails['custom'] . '?' . md5(time()); ?>" />
            </div>
            <div class="crop-options">
              <a href="#" class="cancel-crop crop-option fa fa-times"></a>
              <a href="#" class="ok-crop crop-option fa fa-check margin-left-xs"></a>
            </div><!-- .crop-options -->
          </div>
      	<?php endif; ?>
        </div><!-- .capture-img-container -->

      <?php if (!$sc->common->isMobile): ?>
        <div class="margin-top-xl">
          <div class="crop-options">
            <h4>Please confirm your thumbnail</h4>
            <a href="#" class="cancel-crop crop-option fa fa-times"></a>
            <a href="#" class="ok-crop crop-option fa fa-check margin-left-xs"></a>
          </div><!-- .crop-options -->
        </div>
      <?php endif; ?>
      
        <div class="text-center <?php echo $sc->common->isMobile ? 'margin-top' : ''; ?>">
          <h4 class="margin-bottom-sm">
  					Fullscreen <span class="badge <?php echo $sc->common->isMobile ? 'hidden' : ''; ?>" data-tooltip data-tooltip data-placement="right" title="If checked, your image will span the width of the screen">?</span>
  				</h4>
          <input type="checkbox" name="show_cover" class="toggle normal" id="show-cover" <?php echo $image->showCover ? 'checked' : ''; ?> />
  				<label for="show-cover"></label>
  			</div>
      </div><!-- .container -->
    </section>

    <section class="white padding-top padding-bottom">    
      <div class="container">        
        <div class="form-group">
          <label for="image-name">Title</label> <br />
          <input id="image-name" name="name" class="image-title edit" type="text" value="<?php  echo $image->title; ?>" maxlength="255" />
        </div>                
        
        <div class="capture-secondary-info padding-top">
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <label for="image-country">Country</label> <br />
              <div class="capture-tag edit-location form-group <?php echo $image->hasRealGeo ? 'disabled' : ''; ?>">
                <i class="fa fa-map-marker icon-left"></i>
                <select id="image-country" name="country" <?php echo $image->hasRealGeo ? 'disabled="disabled"' : ''; ?>>
                  <option value="0">Not Known</option>
                <?php

                  foreach ((new Country)->getAll() as $key => $this_country) 
                  {
                    $selected = ($this_country->id == $image->countryID) ? ' selected' : '';
                    echo '<option value="' . $this_country->id . '"' . $selected . '>' . utf8_decode($this_country->name) . '</option>';
                  }

                ?>
                </select>
                
                <!-- <input id="image-location" name="location" class="location-auto" placeholder="Location name" data-resort="<?php echo !empty($image->resort) ? $image->resortID : ''; ?>" data-country="<?php echo empty($image->resort) && !empty($image->country) ? $image->countryID : ''; ?>" value="<?php echo !empty($image->resort) ? $image->resort : $image->country; ?>" type="text" /> -->
                <!-- <div id="location-results" class="location-results edit"></div><!-- #location-results --> 
              </div><!-- .capture-tag form-group -->
            </div><!-- .col -->
						
						<?php 
						
						  $resorts = (new Resort)->getByCountry($image->countryID);
						
						?>
            <div id="resort-holder" class="<?php echo sizeof($resorts) == 0 ? 'hidden' : ''; ?> col-sm-6 col-md-3">
              <label for="image-resort">Town/Resort</label> <br />
              <div class="capture-tag edit-location form-group <?php echo $image->hasRealGeo ? 'disabled' : ''; ?>">
                <i class="fa fa-map-marker icon-left"></i>
                <select id="image-resort" name="resort" data-resort="<?php echo $image->resortID; ?>" <?php echo $image->hasRealGeo ? 'disabled="disabled"' : ''; ?>>
                  <option value="0">Not Known</option>
                  <?php

                  foreach ($resorts as $key => $this_resort) 
                  {
                    $selected = ($this_resort->id == $image->resortID) ? ' selected' : '';
                    echo '<option value="' . $this_resort->id . '"' . $selected . '>' . utf8_decode($this_resort->name) . '</option>';
                  }

                ?>
                </select>
              </div><!-- .capture-tag form-group -->
            </div><!-- .col -->
          	
          	<?php if ($image->hasRealGeo): ?>
          	<div class="col-sm-12 col-md-6 remove-geo-parent">
          		<div class="alert alert-info">
          			<p class='margin-top-xs pull-left'>
          				<i class="fa fa-map-marker icon-left"></i> 
          				Location taken from embedded geodata
        				</p> 
          			<a href="#" class="button button-small pull-right remove-geo" data-image="<?php echo $image->displayID; ?>">Remove Geolocation</a>
          			<div class="clear"></div>
          		</div>
         		</div>
         		<?php endif; ?>
          </div><!-- .row -->

          <div class="row">
            <div class="col-sm-6 col-md-3">
              <label for="image-date">Date Taken</label> <br />
              <div class="capture-tag form-group">
                <i class="fa fa-calendar-o icon-left"></i>
                <input id="image-date" name="date" class="pikaday" placeholder="Click to enter a date" value="<?php echo date("d.m.Y", strtotime($image->dateTaken)); ?>" type="text" maxlength="20" />
              </div><!-- .capture-tag form-group -->
            </div><!-- .col -->
          </div><!-- .row -->

          <div class="row">
            <div class="col-sm-6 col-md-3">
              <label for="image-altitude">Altitude</label> <br />
              <div class="capture-tag form-group">
                <i class="fa fa-line-chart icon-left"></i>
                <select id="image-altitude" name="altitude">
                  <option value="0">Not Known</option>
                  <?php

                    foreach ((new Altitude)->getAll() as $key => $this_altitude) 
                    {
                      $selected = ($this_altitude->id == $image->altitudeID) ? ' selected' : '';
                      echo '<option value="' . $this_altitude->id . '"' . $selected . '>' . $this_altitude->displayHeight . '</option>';
                    }

                  ?>
                </select>
              </div><!-- .capture-tag form-group -->
            </div><!-- .col -->

            <div class="col-sm-6 col-md-3">
              <label for="image-activity">Activity</label> <br />
              <div class="capture-tag form-group">
                <i class="fa fa-rocket icon-left"></i>
                <select id="image-activity">
                  <option value="0">Not Known</option>
                  <?php

                    foreach ((new Activity)->getAll() as $key => $this_activity) 
                    {
                      $selected = ($this_activity->id == $image->activityID) ? ' selected' : '';
                      echo '<option value="' . $this_activity->id . '"' . $selected . '>' . $this_activity->type . '</option>';
                    }

                  ?>
                </select>
              </div><!-- .capture-tag form-group -->
            </div><!-- .col -->
          </div><!-- .row -->
          
          <hr />  
          
          <div id="tags" class="row">
            <div class="col-sm-6 col-md-3 margin-top">
              <label for="image-tag">Add Tags <br /> <span class="small green"> Max. 10 tags - separate with &#9166; or comma.</span></label> <br />
            </div>
            <div id="edit-tag" class="col-sm-6 col-md-3 clear">
              <div class="capture-tag a-tag form-group">
                <i class="fa fa-tag icon-left"></i>
                <input id="image-tag" name="tag" placeholder="..." type="text" maxlength="50" />
              </div><!-- .capture-tag form-group -->
            </div><!-- .col -->
						
          <?php foreach ($image->tags as $tag): ?>
            <div class="col-sm-6 col-md-3 generated-tag">
              <p class="capture-tag a-tag form-group">
                <i class="fa fa-tag icon-left"></i>
                <span class="new-tag"><?php echo $tag; ?></span>
                <a href="#" class="fa fa-times remove-tag icon-right"></a>
              </p>
            </div>
          <?php endforeach; ?>            
          </div><!-- #tags -->
          
          
          <div class="tags clear">
          	<h5 class="margin-bottom-sm">Popular Tags</h5>
				<?php 
                    	
          foreach ((new Tag)->getTop(10) as $_tag):
            $_has_tag = in_array($_tag, $image->tags) ? 'style="display: none"' : '';
          
        ?>
            <a href="#" data-value="<?php echo $_tag; ?>" <?php echo $_has_tag; ?> class="button button-primary button-small edit-top-tag display quick-tag margin-bottom">
              <span>
                <?php echo ucwords($_tag); ?> 
              </span>
            </a>
       	<?php 
            
          endforeach;
       	
       	?>	
       		</div><!-- #tags -->
      	
          <hr />
  					
          <div class="row">
            <div class="col-xs-12 margin-bottom margin-top normal-content">
              <label for="image-description">Description</label> <br />
              <textarea id="image-description" class="form-control input-lg markdown" rows="5"  maxlength="5000"><?php echo $image->description; ?></textarea>
            </div>
          </div><!-- .row -->
            
          <?php if($image->hasGeo): ?>
          <hr />
          
          <div id="map-container">
            <div id="geo-data" class="margin-top-xl">
        			<?php if($image->hasRealGeo): ?>
        			<div class="pull-left margin-right-xl">
                <table class="table table-bordered table-striped">
                  <tr>
                    <th>Latitude</th>
                    <th>Longitude</th>
                  </tr>
                  <tr>
                    <td>
                      <code><?php echo $image->latitude; ?></code>
                    </td>
                    <td>
                      <code><?php echo $image->longitude; ?></code>
                    </td>
                  </tr>
                </table>
              </div>
              <a href="#" class="remove-geo button button-danger pull-left margin-top-lg" data-image="<?php echo $image->displayID; ?>">Remove Geolocation</a>         
      				<?php endif; ?>
      				
            	<div class="pull-right text-right">
                <h4 class="margin-bottom-sm">
    							Display Map
    						</h4>
                <input type="checkbox" name="show_map" class="toggle normal" id="show-map" <?php echo $image->showMap ? 'checked' : ''; ?> />
    						<label for="show-map"></label>
  						</div>
            </div>
            
            <div class="clear"></div>
            
						<div id="map" class="margin-top" style="height: <?php echo $sc->common->isMobile ? '250px' : '500px'; ?>;<?php echo !$image->showMap ? ' display: none;' : ''; ?>" class="margin-top margin-bottom-xl"></div><!-- #map -->                       
          </div><!-- #map-container -->
          <?php endif; ?>
  
        	<div class="clear"></div>
        </div><!-- .capture-secondary-info -->   
        
        <hr />                     
			
        <div class="user-capture-menu">
          <?php if(!$draft): ?>
            <a href="/capture/<?php echo $image->displayID; ?>" class="button pull-left" tabindex="6">Cancel</a>
          <?php else: ?>
            <a href="#" class="delete-image button button-danger pull-left" data-url="<?php echo $nextDraftUrl; ?>" data-image="<?php echo $image->displayID; ?>" tabindex="6">
              Remove
            </a>          
          <?php endif; ?>
  
          <button id="image-submit" 
      				data-url="<?php echo $processing ? '/' . $sc->user->username . '/processing' : ($private ? '/' . $sc->user->username . '/privates' : ($draft ? $nextDraftUrl : ($sc->common->hasPreviousPage() ? '' : '/capture/' . $image->displayID))); ?>" 
      				data-image="<?php echo $image->displayID; ?>" 
      				<?php echo $draft ? 'data-draft="true"' : ''; ?>	
      				type="submit" 
      				class="button button-primary update-image margin-left pull-right btn-group"
      				form="edit-form">
          <?php if($draft): ?>
            Publish
          <?php else: ?>
            Finish
          <?php endif; ?>
            <i class="fa fa-check icon-right"></i>
          </button>
  
          <?php if($draft): ?>
            <button id="image-save" 
                  data-image="<?php echo $image->displayID; ?>" 
                  type="submit" class="button button-default update-image save margin-left pull-right btn-group"
                  form="edit-form">
                  Save <i class="fa fa-save icon-right"></i>
            </button>
          <?php else: ?>
            <a href="#" class="delete-image button button-danger pull-right" data-url="/<?php echo $sc->user->username; echo $processing ? '/processing"' : ($private ? '/privates"' : ''); ?>" data-image="<?php echo $image->displayID; ?>" tabindex="6">
              <i class="fa fa-trash"></i>
            </a>
          <?php endif; ?>
        </div><!-- .user-capture-menu -->
      </div><!-- .container -->
    </section>  
  </form>
</main>






<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

<?php if(!$sc->common->isMobile): ?>
<script>
  function preview(img, selection) {
  	if (!selection.width || !selection.height) return;
  
  	var mainImg = $('#capture-img');
  	var prev = $('#preview');
  	var prevImg = $('#preview img');
  
  	if (prev.hasClass('edited') == false)
  	{
  		prevImg.attr('data-src', prevImg.attr('src'));
  		prevImg.attr('src', mainImg.attr('src'));
  	}
       
  	var scaleX = prev.innerWidth() / selection.width;
  	var scaleY = prev.innerHeight() / selection.height;
  
  	prevImg.css({
  		width : Math.round(scaleX * mainImg.innerWidth()) + "px",
  		height : Math.round(scaleY * mainImg.innerHeight()) + "px",
  		marginLeft : -Math.round(scaleX * selection.x1),
  		marginTop : -Math.round(scaleY * selection.y1)
  	});
  
  	prev.attr('data-x1', selection.x1);
  	prev.attr('data-y1', selection.y1);
  	prev.attr('data-x2', selection.x2);
  	prev.attr('data-y2', selection.y2);
  
  	prev.attr('data-width', mainImg.innerWidth());
  	prev.attr('data-height', mainImg.innerHeight());
  
  	prev.addClass('edited');
  }
  
  var ias;
  var img = $('<img id="capture-img" class="edit" src="<?php echo $image->filePath; ?>" data-tooltip data-placement="top" title="Drag over image to select a thumbnail" alt="<?php echo $image->title; ?>"/>');
  img.load(function() {
  	ias = $('#capture-img').imgAreaSelect({
  		instance : true
  	});  
  	ias.setOptions({
  		parent : '#img-select',
  		aspectRatio : '4:3',
  		handles : true,
  		onSelectEnd : preview
  	});
  	ias.update();
  });
  $('#img-select').html(img)
  
  $(document).ready(function(e) {
  	$(document).on('load', '#', function() {
      
    });
    
  	$(document).on('click', '.cancel-crop', function(e) {
  		var prevImg = $('#preview img');
  		prevImg.attr('src', prevImg.data('src'));
  		prevImg.attr('style', '');
  
  		e.preventDefault();
  	});
  
  	$(document).on('click', '.crop-option', function(e) {
  		$('#preview').removeClass('edited');
  		ias.setOptions({
  			hide : true
  		});
  		ias.update();
  
  		if ($(this).hasClass('fa-check'))
  		{
  			$('#preview').addClass('generate');
  		}
  
  		e.preventDefault();
  	});
  });
</script>
<?php endif; ?>



<?php if($image->hasGeo): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_BROWSER_KEY; ?>" type="text/javascript"></script>
<script>
  var lat = <?php echo $image->latitude; ?>;
  var lon = <?php echo $image->longitude; ?>;
  var zoom = <?php echo $image->hasRealGeo ? '13' : '9'; ?>;
  var path = '<?php echo $image->thumbnails['custom']; ?>';
  
  $(document).ready(function(){
    googleMap.initialise(lat, lon, path, zoom);
  });
</script>
<?php endif; ?>  

<script>
	$(document).ready(function() {
		var h = 60;
		$('#top-bar').sticky({
			stickOn: h,
			offset: h,
			className: 'stuck', 
			emptyClass: false
		});
	});
</script>

</body>
</html>
