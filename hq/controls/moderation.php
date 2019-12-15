<?php

  // Get images for moderation
  if(isset($_POST['ajax']))
  {
    require_once('../../api/site.init.php');
  }
    
  $moderated_image = $sc->user->getNextProcessingImage();
  if($moderated_image !== false):  

?>
			
      <div class="moderate-item" data-image="<?php echo $moderated_image->displayID; ?>">
        <a href="/capture/<?php echo $moderated_image->displayID; ?>">
          <img src="<?php echo $moderated_image->thumbnails['custom']; ?>" />
        </a>

        <h3><?php echo $moderated_image->username; ?></h3>
      </div><!-- .moderate-item -->
      
      <a href="#" class="fa fa-times moderate-button reject-image" data-image="<?php echo $moderated_image->displayID; ?>" data-toggle="modal" data-target="#RejectImageModal"></a>
      <a href="#" class="fa fa-check moderate-button approve-image" data-image="<?php echo $moderated_image->displayID; ?>"></a>

<?php

  else:
      echo '<br /><br /><p>No moderation requests</p><br /><br />';
  endif;

?>