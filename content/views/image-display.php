<?php
/**
 * This page is used in any pages that display images
 * It relies on GET variables via the browser to generate which images to
 * display
 * It retrieved using AJAX too.
 */

// Load in scripts if using ajax
$_ajax = false;

if (isset($_POST['ajax']) || isset($_POST['dynamic']))
{
  $_ajax = true;
  require_once ('../../api/site.init.php');
}

// Let global var know we are using a filter on this page
$sc->vars->hasFilter = true;

// Prepare vars
$_html = '';
$_found = '';
$_page = $sc->common->page();
$_pageType = $sc->vars->pageType;
$_choosing = (($_pageType == 'choosing' || $_pageType == 'choosing_cover') && $sc->session->exists());

// Get images
$_filter = (new Image)->buildFilterFromURL();
$_filter = (new Image)->search($_filter);

$_images = empty($_filter->results) ? false : $_filter->results;
$_maps = empty($_filter->maps) ? false : $_filter->maps;

// Show maps?
$_show_map = ($_page == 1 && $_maps && ! $_choosing && ($sc->common->filterSet() || $sc->common->location()));

// Choosing with no images
if ($_choosing && ! $_images)
{
  $_html .= (new HTMLRender)->imgChoosing(($_pageType == 'choosing_cover') ? 'true' : 'false');
}

// HasNoImages
elseif (! $_images)
{
  // Display nice no images found if not via AJAX
  // Display code message for AJAX response
  if (! empty($_POST['ajax']))
  {
    $_html .= 'no_images';
  } 

  else
  {
    $_html .= (new HTMLRender)->imgNotFound();
  }
} 

// Has Images
else
{
  // Keep track with counter
  $_count = 0;
  
  // Loop through images
  foreach ($_images as $image)
  {
    // Increment counter
    $_count ++;
    
    // Set variables we need
    $draft = $image->status == IMAGE_DRAFT ? true : false;
    $processing = $image->status == IMAGE_PROCESSING ? true : false;
    $private = $image->status == IMAGE_PRIVATE ? true : false;
    $src = empty($image->thumbnails['custom']) ? '/template/images/placeholder.jpg' : $image->thumbnails['custom'];
    $filetype = strtolower($image->fileType);     
    
    /**
     * STARTING IMAGE HTML
     */
    
    // Load html for first item
    if ($_count == 1 && $_page == 1)
    {
      $_html .= '<div class="row image-holder-for-css">';
      
      // Show profile default image, if choosing
      if ($_choosing)
      {
        $_html .= (new HTMLRender)->imgChoosing(($_pageType == 'choosing_cover'));
      }
    }
    
    // Load actual image template
    // HTML is stored in the object to neaten things up!
    $_html .= (new HTMLRender)->imgGalleryImage($image);
    
    // Close wrapper
    if ($_count == sizeof($_images) && $_page == 1)
    {
      $_html .= '</div> <!-- for-css -->';
    }
  } // foreach
    
  // Show load more?
  if (($_filter->total - ($_page * ROW_LIMIT)) > 0)
  {
    // Are we on the first page?
    $_html .= (new HTMLRender)->imgAutoloadButton($_page);
  }
} // if/else has images
  
// Update number of results counter if not requesting new page
// Filter must be set
if (($sc->common->filterSet() || ! empty($sc->common->keyword())) && $_page == 1)
{
  (new HTMLRender)->imgResultsFound($_filter->total);
}

/**
 * RETURNING RESULTS
 */
// If we're using ajax return object of results, else return the html.
if ($_ajax)
{
  $obj = array(
      'html' => $_html,
      'maps' => array(
          'show' => $_show_map,
          'data' => $_maps
      ),
      'page' => $_page,
      'text' => $_found,
      'filtered' => $sc->common->filterSet(),
      'querystring' => $sc->common->filterString(),
      'rows' => $_filter->total
  );
  
  echo json_encode($obj);
} 

else
{
  // Bookmarked used by jquery
  echo '<div id="image-bookmark"></div>';
  
  if($sc->session->exists())
  {
    echo '
      <div id="choose-alert" class="alert alert-info text-center hidden" role="alert">
        <p>Please select a new picture <a href="#" class="profile-picture choose-pic inline margin-left-xs"><code><i class="fa fa-times"></i> Cancel</code></a></p>
      </div>
    ';
  }
  
  echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_BROWSER_KEY . '" type="text/javascript"></script>';
  
  if ($_show_map && $_filter->total > 0 && ! $_ajax)
  {
    echo '
      <div id="map"></div><!-- #map -->
      <script>
    		$(document).ready(function(e){
    			googleMap.filter(' . json_encode($_maps) . ');
    		});
    	</script>
    ';
  }
  
  // Show view based on mobile or desktop
  // Show filter if there are images
  $platform = $sc->common->isMobile ? 'mobile.php' : 'desktop.php';
  include(WEB_ROOT . 'content/filter/' . $platform);
  
  echo '
    <div class="clear glory posh">
      <div id="image-results" class="container">
        ' . $_html . '
      </div><!-- #image-results -->
      <div class="clear"></div> 
    </div><!-- .posh -->    
  ';
}

?>
