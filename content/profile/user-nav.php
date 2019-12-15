<?php 
  
  /*
   * INITIALISED IN /pages/user.php 
   * 
   */

  $is_admin = $sc->session->exists() ? $sc->user->isAdmin() : false;
  $is_placeholder = strpos($profile_user->pictureCover, 'placeholder');
  
  function navLinks ()
  {
    global $sc;
    global $is_self;
    global $profile_user;
    global $pageType;
    
    if($is_self):
    
?>
    
<div class="user-nav-links">      
  <a href="/<?php echo $profile_user->username; ?>" data-type="user" class="preload-profile user-link<?php echo ($pageType == 'user') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->active; ?>
    </span>
    <strong>
      Published
    </strong>
  </a>

	<?php if ($profile_user->drafts > 0): ?>
  <a href="/<?php echo $profile_user->username; ?>/drafts" data-type="drafts" class="preload-profile user-link<?php echo ($pageType == 'drafts') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->drafts; ?>
    </span>
    <strong>
      Draft<?php echo $profile_user->drafts > 1 ? 's' : ''; ?>
    </strong>
  </a>
  <?php endif; ?>

	<?php if ($profile_user->processing > 0): ?>
  <a href="/<?php echo $profile_user->username; ?>/processing" data-type='processing' class="preload-profile user-link<?php echo ($pageType == 'processing') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->processing; ?>
    </span>
    <strong>
      Processing
    </strong>
  </a>
  <?php endif; ?>

	<?php if ($profile_user->privates > 0): ?>
  <a href="/<?php echo $profile_user->username; ?>/privates" data-type='privates' class="preload-profile user-link<?php echo ($pageType == 'privates') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->privates; ?>
    </span>
    <strong>
      Privated
    </strong>
  </a>
  <?php endif; ?>
	
	<?php if ($profile_user->likes > 0): ?>
  <a href="/<?php echo $profile_user->username; ?>/likes" data-type='likes' class="preload-profile user-link<?php echo ($pageType == 'likes') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->likes; ?>
    </span>
    <strong>
      Liked
    </strong>
  </a>
  <?php endif; ?>
</div><!-- user-nav-links -->

<div class="user-options">
  <a href="/<?php echo $profile_user->username; ?>/settings" class="bulge option-icon" data-tooltip data-placement="bottom" title="Settings"><i class="fa fa-cog"></i></a>
</div><!-- .user-options -->
  
<?php 
  
  // Else IsNotSelf
  else:

?>
          
<div class="user-nav-links <?php echo (! $sc->session->exists() ? 'pull-right' : ''); ?>">      
  <a href="/<?php echo $profile_user->username; ?>" data-type="user" class="preload-profile user-link<?php echo ($pageType == 'user') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->active; ?>
    </span>
    <strong>
      Published
    </strong>
  </a>

  <a href="/<?php echo $profile_user->username; ?>/likes" data-type='likes' class="preload-profile user-link<?php echo ($pageType == 'likes') ? ' active' : ''; ?>">
    <span>
      <?php echo $profile_user->likes; ?>
    </span>
    <strong>
      Likes
    </strong>
  </a>
</div><!-- user-nav-links -->  

<?php 
  
    // IsUser
    if($sc->session->exists()):
  
?>
    
<div class="user-options margin-left-xl">
	<div class="sc-dropdown-container" data-dir-right>
    <a href="#" class="option-icon sortby-dropdown" data-dropdown="#user-options" data-tooltip data-placement="left" title="Options">
      <i class="fa fa-wrench"></i>
    </a>
  	<ul id="user-options" class="sc-dropdown animate">
    	<li>
<?php 
      
      // HasReported          
      if($sc->user->hasReportedUser($profile_user->id)):
   
?>
    
        <a href="#" class="unreport-user" data-id="<?php echo $profile_user->displayID; ?>">
          <span>Un-Report User</span>
          <i class="fa fa-check icon-right"></i>
        </a>
      
<?php 

      // Else HasNotReported          
      else:
     
?>
    
        <a href="#" data-toggle="modal" data-target="#ReportUserModal">
          <span>Report User</span>
          <i class="fa fa-ban icon-right"></i>
        </a>
    
<?php 
      
      // End HasReported
      endif;
    
?>          
      </li>
    </ul>
  </div>
</div><!-- .user-options -->
  
  <?php 
  
    endif;
  endif;
  
}

?>

<div id="cover-picture"	<?php echo !$is_placeholder ? 'style="background-image: url(' . $profile_user->pictureCover . ')"' : ''; ?>
	 class="cover-picture <?php echo $is_self ? ' choose-pic' : ''; echo $is_placeholder ? ' placeholder' : ''; ?>">

	<?php echo $is_placeholder ? '<span>Snow Capture</span>' : ''; ?>
	<?php echo $is_self ? '<div class="cover-bg"><i class="fa fa-plus" data-tooltip data-placement="bottom" data-delay="250" title="Change Image"></i></div>' : ''; ?>
</div> 

<?php if ($sc->common->isMobile) { navLinks(); } ?>

<section id="stick-user" class="user-nav white">
  <div class="container">
     
    <div class="user-badge"> 
      <a href="/<?php echo $profile_user->username; ?>" <?php echo $is_self ? 'class="profile-picture choose-pic" data-tooltip data-placement="bottom" data-delay="250" title="Change Image"' : 'class="profile-picture"'; ?>>
        <img class="user-picture <?php echo (strpos($profile_user->picture, 'placeholder') ? 'placeholder' : ''); ?>" src="<?php echo $profile_user->picture; ?>" alt="<?php echo $profile_user->displayName; ?>" width="175" />
      </a>     
      <div class="user-name">                    
        <a href="/<?php echo $profile_user->username; ?>">
          <h3><?php echo $profile_user->name; ?> </h3>
          <h4>
            <?php echo $profile_user->isOnline() ? '<span class="green">&bull;</span>' : ''; ?>
            <?php echo $profile_user->displayName; ?>                        
          </h4>
        </a>
      </div><!-- .user-name -->     
    </div><!-- .user-badge -->  

		<?php if (! $sc->common->isMobile) { navLinks(); } ?>
    
  </div><!-- .container -->
</section><!-- .posh -->