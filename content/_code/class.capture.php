<?php

class Capture
{
  /**
   * Global SnowCapture class
   * 
   * @var SnowCapture
   */
  private $sc;
  
  /**
   * The image capturing
   * 
   * @var Image
   */
  private $image;
  
  /**
   * HTMLRender class
   * 
   * @var HTMLRender
   */
  private $render;
  
  /**
   * The logged in user (if any)
   * 
   * @var User
   */
  private $user;
  
  /**
   * List of images nearby
   * 
   * @var array
   */
  private $imagesNearby = array();




  /**
   *
   * @param Image $image          
   */
  public function __construct($image, $user = false)
  {
    global $sc;
    $this->sc = $sc;
    $this->image = $image;
    $this->user = $user;
    $this->render = new HTMLRender();
  }




  public function canView()
  {
    $_img = $this->image;
    $_usr = $this->user;
    
    if (! $_img)
    {
      return false;
    }
    
    if (! $_img->exists())
    {
      return false;
    }
    
    if ($_img->status != IMAGE_PUBLISHED && ! $_usr)
    {
      return false;
    }
    
    if ($_img->status != IMAGE_PUBLISHED && ! $_usr->isAdmin() && $_usr->id != $_img->user->id)
    {
      return false;
    }
    
    return true;
  }




  public function showImage()
  {
    if ($this->sc->common->isMobile)
    {
      echo '
      
        <div id="capture-image">
          <img src="' . $this->image->filePath . '"/>
        </div>
          
      ';
    } 

    else
    {
      echo '
      
        <div id="capture-image" ' . ($this->image->showCover ? 'class="cover"' : '') . '>
          <div style="background-image: url(' . $this->image->filePath . ')"></div>
        </div>
          
      ';
    }
  }




  public function showStats()
  {
    $_rating = ($this->image->rating % 2) == 0 ? round($this->image->rating) : round($this->image->rating) . ' 	&frac12;';
    $_likes = $this->image->likes;
    
    echo '
      
      <div class="image-stats">
        <p class="likes image-stat text-center margin-right">
          <span class="stat-title">Likes</span>
          <span class="stat-figure capture-like-count">' . $_likes . '</span>
        </p>
             
        <div class="image-stat">
          <span class="stat-title">Rating</span>
          ' . $this->render->imageRating($this->image->id, $this->image->rating) . '
        </div>
      </div>
        
    ';
  }




  public function showReport()
  {
    if (! $this->user)
    {
      return false;
    }
    
    if ($this->user->id == $this->image->user->id)
    {
      return false;
    }
    
    $_user = $this->user;
    $_image = $this->image;
    $_reported = $_user->hasReportedImage($_image->id);
    
    echo '<div class="image-stats margin-right">';
    if ($_reported)
    {
      echo '
          <a href="#" class="image-icon green unreport-image" title="Un-Report" data-tooltip data-placement="top" data-id="' . $this->image->displayID . '">
            <i class="fa fa-check"></i>
          </a>
      ';
    } 

    else
    {
      echo '
        <a href="#" class="red image-icon" title="Report" data-tooltip data-toggle="modal" data-placement="top" data-toggle="modal" data-target="#ReportImageModal">
          <i class="fa fa-ban"></i>
        </a> 
      ';
    }
    echo '</div>';
  }




  public function showLike()
  {
    if (! $this->user)
    {
      return false;
    }
    
    $_image = $this->image;
    $_liked = $_image->isLike($this->user);
    
    echo '
      <div class="image-stats middle">
        <a  href="#" 
            class="image-icon tooltip-75 bordered like-image ' . ($_liked ? 'liked' : '') . '"
            data-image="' . $_image->displayID . '"
            title="' . ($_liked ? 'Un-Like' : 'Like') . '" data-tooltip data-placement="top"
        >
          <i class="fa fa-heart"></i>
          <i class="fa fa-heart-o"></i>
          <i class="fa fa-times"></i>
        </a>
      </div>
    ';
  }




  public function showRate()
  {
    if (! $this->user || $this->user->id == $this->image->user->id)
    {
      return false;
    }
    
    $_image = $this->image;
    $_rating = $this->user->getImageRating($this->image);
    $_rated = ($_rating > 0);
    
    echo '
      <div class="image-stats middle rate-stat margin-left">
        <a href="#" class="image-icon bordered rate-image off" data-rating="1" data-image="' . $_image->displayID . '">
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
        </a>
            
        <a href="#" class="image-icon bordered rate-image off" data-rating="2" data-image="' . $_image->displayID . '">
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
        </a>
            
        <a href="#" class="image-icon bordered rate-image off" data-rating="3" data-image="' . $_image->displayID . '">
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
        </a>
            
        <a href="#" class="image-icon bordered rate-image off" data-rating="4" data-image="' . $_image->displayID . '">
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
        </a>
            
        <a href="#" class="image-icon bordered rate-image off" data-rating="5" data-image="' . $_image->displayID . '">
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
        </a>
            
        <a  href="#" 
            class="image-icon tooltip-75 bordered image-rate ' . ($_rated ? 'rated' : '') . '" 
            data-image="' . $_image->displayID . '"
            title="' . ($_rated ? 'Remove' : 'Rate') . '" data-tooltip data-placement="top"
        >
          <i class="fa fa-star"></i>
          <i class="fa fa-star-o"></i>
          <i class="fa fa-times"></i>
        </a>
      </div>
    ';
  }




  public function showSpotlight()
  {
    if (! $this->user)
    {
      return false;
    }
    
    if (! $this->user->isAdmin())
    {
      return false;
    }
    
    $_imgs = array();
    foreach ($this->image->getTop(6) as $_img)
    {
      array_push($_imgs, $_img->id);
    }
    
    echo '<div class="image-stats margin-right">';
    
    if (in_array($this->image->id, $_imgs))
    {
      echo '<p class="image-icon"><i class="fa fa-star blue"></i></p>';
    } 

    else
    {
      echo '
          <a href="#" class="add-to-spotlight image-icon" data-image="' . $this->image->displayID . '">
            <i class="fa fa-star-o blue"></i>
          </a>
      ';
    }
    
    echo '
      <a href="#" class="delete-image image-icon" data-image="' . $this->image->displayID . '">
        <i class="fa fa-trash red"></i>
      </a>    
    ';
    
    echo '</div>';
  }




  public function showSharing()
  {
    if ($this->image->status != IMAGE_PUBLISHED)
    {
      return false;
    }
    
    $_url = $this->sc->common->siteURL() . '/capture/' . $this->image->displayID;
    
    echo '
      <div class="image-stats square-share">
        <a class="shared-media fa fa-twitter-square" title="Twitter" target="_blank" href="https://twitter.com/intent/tweet?text=' . $this->image->title . '&url=' . $_url . '&via=TheSnowCapture" data-tooltip data-placement="top">
        </a>
        <a class="shared-media fa fa-facebook-square" title="Facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $_url . '" data-tooltip data-placement="top">
        </a>
        <a class="shared-media fa fa-google-plus-square" title="Google+" target="_blank" href="https://plus.google.com/share?url=' . $_url . '" data-tooltip data-placement="top">
        </a>
      </div>
    ';
  }




  public function showAuthor()
  {
    echo '
        
      <div class="capture-author">
        <a href="/' . $this->image->user->username . '" class="cover" style="background-image: url(' . $this->image->user->pictureCover . ')"></a>
        <a href="/' . $this->image->user->username . '" class="meta">
          <i style="background-image: url(' . $this->image->user->picture . ')"></i> 
          <span>
            <h5 itemprop="author">' . $this->image->user->name . '</h5>
            <h6>/' . $this->image->user->displayName . '</h6>
          </span>
        </a>
        <!--<p class="stat">
          <span>Published</span>
          <i>' . $this->image->user->countUploads() . '</i>
        </p>-->
        <div class="clear"></div>
      </div><!-- .capture-author -->
        
    ';
  }




  public function showLocation()
  {
    // Build links
    $_resort = ! empty($this->image->resort) ? '<a href="/search?location=' . $this->image->resort . '" class="block-tag">' . $this->image->resort . '</a>' : '';
    $_country = ! empty($this->image->country) ? '<a href="/search?location=' . $this->image->country . '" class="block-tag">' . $this->image->country . '</a>' : '';
    $_unkown = empty($_resort) && empty($_country) ? '<p class="block-tag unkown">Unkown</p>' : '';
    
    echo '
      <div class="capture-block">
    		<div class="block-heading">
          <h3><i class="fa fa-map-marker icon-left"></i> Location</h3>
        </div>
        
        <div class="clear">
          ' . $_resort . $_country . $_unkown . '
        </div>
      </div><!-- .capture-block -->
    ';
  }




  public function showDates()
  {
    $_date = ($this->image->status == IMAGE_PUBLISHED) ? $this->image->datePublished : $this->image->dateCreated;
    $_time = $this->sc->common->timeAgo($_date) ? 'class="dynamic-time" title="' . $_date . '"' : '';
    
    echo '
      <div class="capture-block">
      	<div class="block-heading">
      		<h3><i class="fa fa-calendar icon-left"></i> Dates</h3>
    		</div>
        
    		<div class="clear">
    			<p class="block-tag">Taken: <strong>' . date("d.m.Y", strtotime($this->image->dateTaken)) . '</strong></p>
    			<p class="block-tag">' . ($this->image->status == IMAGE_PUBLISHED ? 'Published' : 'Uploaded') . ': <strong><span itemprop="datePublished" content="' . $_date . '" ' . $_time . '>' . $this->sc->common->dateToTime($_date) . '</span></strong></p>
    		</div>
    	</div><!-- .capture-block -->
    ';
  }




  public function showActivity()
  {
    $_text = ! empty($this->image->activity) ? '<a href="/search?activity=' . $this->image->activity . '" class="block-tag">' . $this->image->activity . '</a>' : '<p class="block-tag unkown">Unkown</p>';
    
    echo '
      <div class="capture-block">
      	<div class="block-heading">
      		<h3><i class="fa fa-rocket icon-left"></i> Activity</h3>
    		</div>
    		<div class="clear">
      		' . $_text . '
    		</div>
    	</div><!-- .capture-block -->
    ';
  }




  public function showAltitude()
  {
    $_altitude = str_replace(' ', '', $this->image->altitude);
    $_altitude = str_replace('m', '', $_altitude);
    
    $_text = ! empty($this->image->activity) ? '<a href="/search?altitude=' . $_altitude . '" class="block-tag">' . $this->image->altitude . '</a>' : '<p class="block-tag unkown">Unkown</p>';
    
    echo '
      <div class="capture-block">
      	<div class="block-heading">
      		<h3><i class="fa fa-line-chart icon-left"></i> Altitude</h3>
    		</div>
    		<div class="clear">
      		' . $_text . '
    		</div>
    	</div><!-- .capture-block -->
    ';
  }




  public function showTags()
  {
    // Get tags
    $this->image->getTags();
    $_tags = '';
    foreach ($this->image->tags as $tag)
    {
      $_tags .= '<a href="/search?tag=' . ucwords($tag) . '" class="block-tag">' . $tag . '</a>';
    }
    
    $_tags = empty($_tags) ? '<p class="block-tag unkown">None</p>' : $_tags;
    
    echo '
      <div id="image-tags" class="bg-white capture-padding clear">
        <div class="capture-block">
        	<div class="block-heading">
        		<h3><i class="fa fa-tag icon-left"></i> Tags</h3>
      		</div>
      		<div class="clear">
          	' . $_tags . '
  				</div>
      	</div><!-- .capture-block -->
  	    <div class="clear"></div>
	    </div><!-- .capture-padding -->
    ';
  }




  public function showDescription()
  {
    if (empty($this->image->description))
    {
      return false;
    }
    
    echo '
      
      <div class="bg-white capture-padding clear">
        <div class="capture-block">
        	<div class="block-heading margin-top-none">
        		<h3><i class="fa fa-edit icon-left"></i> Description</h3>
        	</div>     
      		<div class="clear">
        		<div itemprop="articleBody">' . $this->sc->common->convertToMarkdown($this->image->description) . '</div> 
      		</div>
      	</div><!-- .capture-block -->
  	    <div class="clear"></div>
	    </div><!-- .capture-padding -->
        
    ';
  }




  public function showNearby()
  {
    // Get nearby images
    $_no_to_get = $this->image->hasRealGeo ? 10 : 50;
    $_images_nearby = (new Image())->getNearby($this->image->latitude, $this->image->longitude, $_no_to_get);
    
    if (! $_images_nearby)
    {
      return false;
    }
    
    shuffle($_images_nearby);
    $_images_nearby = array_slice($_images_nearby, 0, 6);
    $_no_images = sizeof($_images_nearby);
    $_padding = ($_no_images > 1) ? ($_no_images * 15) : 0;
    
    $_images = '';
    foreach ($_images_nearby as $_img)
    {
      $_images .= '<a href="/capture/' . $_img->displayID . '" class="featured-image lazy-container">';
      if ($this->sc->common->isMobile)
      {
        $_images .= '<img src="' . $_img->thumbnails['custom'] . '" alt="' . $_img->title . '" />';
      } 

      else
      {
        $_images .= '<img class="lazy" src="/template/images/placeholder.jpg" data-src="' . $_img->thumbnails['custom'] . '" alt="' . $_img->title . '" />';
      }
      $_images .= '</a>';
      
      $this->imagesNearby = $_images_nearby;
    }
    
    echo '
      
      <div class="bg-white capture-padding clear">
        <div class="capture-block">
        	<div class="block-heading margin-top-none">
        		<h3><i class="fa fa-map-marker icon-left"></i> Images Nearby</h3>
        	</div>     
        
        	<div class="featured-images clear">
        		<div class="featured-inner smaller" ' . ($this->sc->common->isMobile ? 'style="width: ' . ((175 * $_no_images) + $_padding) . 'px"' : '') . '> 
              ' . $_images . '
        		</div>
        	</div>
        </div><!-- #capture-details -->
  	    <div class="clear"></div>
	    </div><!-- .capture-padding -->
        
    ';
  }




  public function showMap()
  {
    if (! $this->image->hasGeo || ! $this->image->showMap)
    {
      return false;
    }
    
    // Get stored nearby images
    $_images = $this->imagesNearby;
    
    // At this image to them
    array_push($_images, $this->image);
    
    echo '
      
      <div id="map" class="capture" style="height: ' . ($this->sc->common->isMobile ? '250px' : '500px') . '"></div><!-- #map -->
      <script type="text/javascript">
        $(document).ready(function(){
    	  	googleMap.filter(' . json_encode($_images) . ', true);  
        });
        
      </script>  
        
    ';
  }




  public function showDeleted()
  {
    echo '
      
      <section class="posh start end white">
        <div class="container">
          <h1>Image Rejected</h1>
        	<p>' . $this->image->deletedReason() . '</p>
          
          <span class="break-dot"></span>
          <span class="break-dot"></span>
          <span class="break-dot"></span>
          
          <br />
          
          <a href="/" class="button button-primary">
            Go Home <i class="fa fa-home icon-right"></i>
          </a>
        </div><!-- .container -->
      </section><!-- .posh -->
        
    ';
  }
}