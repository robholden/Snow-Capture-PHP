<li class="separator"><span>Hello, <?php echo $sc->user->name; ?></span></li>
<li>
  <a href="/<?php echo $sc->user->username; ?>">
    View Profile
    <i class="fa fa-user"></i>
  </a>
</li>

<li>
  <a href="/<?php echo $sc->user->username; ?>/drafts">
    Drafts
    <span class="badge"><?php echo $sc->user->drafts; ?></span>
  </a>
</li>

<li>
  <a href="/<?php echo $sc->user->username; ?>/processing">
    Processing
    <span class="badge"><?php echo $sc->user->processing; ?></span>
  </a>
</li>

<li>
  <a href="/<?php echo $sc->user->username; ?>/privates">
    Privates
    <span class="badge"><?php echo $sc->user->privates; ?></span>
  </a>
</li>

<li>
  <a href="/<?php echo $sc->user->username; ?>/likes">
    Likes
    <span class="badge"><?php echo $sc->user->likes; ?></span>
  </a>
</li>

<li class="separator"></li>
<?php

  // Check if the user has not reached their maximum uploads
  // If they can display upload link
  // CanAccessUpload 
  if($sc->user->canAccessUpload()):
    $draftcount = $sc->user->drafts;

    // IsDraftCountLess 
    if($draftcount < $sc->user->limits->drafts):
?>
  
<li>
  <a href="#" id="upload-link" class="notification-link <?php echo ($sc->user->imageTerms ? 'open-upload' : 'open-image-terms'); ?> close-navs">
    Add Images
    <i class="fa fa-camera"></i>
  </a>
</li>

<?php
    
    // Else IsDraftCountMore
    else: 

      // Get draft image
      $previmage = $sc->user->getDraftImage();  

?>
  
<li class="faded">
  <a id="upload-link" href="#" class="notification-link limit-reached close-navs" data-limit="<?php echo $sc->user->limits->drafts; ?>">
    <?php echo $draftcount . '/' . $sc->user->limits->drafts; ?> Drafts
    <i class="fa fa-ban"></i>
  </a>
</li>

<?php

    // End IsDraftCountLess 
    endif;

  // End CanAccessUpload 
  endif;    

?>

<li>
  <a href="/<?php echo $sc->user->username; ?>/settings">
    Settings
    <i class="fa fa-cog"></i>
  </a>
</li>

<?php 

  // IsAdmin 
  if($sc->user->isAdmin()):

?>

<li class="separator"></li>
<li>
  <a href="/hq/dashboard">
    Admin Panel
    <i class="fa fa-tasks"></i>
  </a>
</li>
<li>
	<a href="#" id="generate-sitemap">
		Generate Sitemap
    <i class="fa fa-refresh"></i>
  </a>
</li>

<?php

  // End IsAdmin 
  endif; 

?>

<li class="separator"></li>
<li>
  <a href="/?logout=true" class="logout">
    Sign Out
    <i class="fa fa-sign-out"></i>
  </a>
</li> 