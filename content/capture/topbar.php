<?php

/**
 *
 * Parent: /content/capture.php
 * 
 * 
 **/
 
// Is it processing / draft?
$_processing = ($_image->status == IMAGE_PROCESSING);
$_private = ($_image->status == IMAGE_PRIVATE);

$is_owner = ($sc->session->exists()) ? $_image->isOwner($sc->user) : false;

$_prevpage = $sc->common->hasPreviousPage();

if ($_prevpage)
{
  $_prevpage = strpos(strtolower($_SERVER['HTTP_REFERER']), '/edit') ? false : $_prevpage;
}

?>

<?php if ($_private || $_processing): ?>
<section id="processing-message" <?php echo $_private ? 'class="draft"' : ''; ?>>
  <div class="container">
    <p><?php echo $_private ? 'Private Image' : 'Processing Image'; ?></p>
  </div><!-- .container -->
</section>
<?php endif; ?>

<section id="top-bar" class="<?php echo ($is_owner ? 'is-owner' : ''); echo ($sc->common->isMobile ? ' mobile' : ''); ?>">
  <div class="container text-center">
  	<?php if (! $sc->common->isMobile): ?>
  		<?php if ($_prevpage): ?>
      <a href="javascript:history.back()" class="button margin-right pull-left">
        <i class="fa fa-chevron-left icon-left"></i> <?php echo $_prevpage; ?>
      </a>
  		<?php endif; ?>
		<?php endif; ?>

<?php 
  
// Display options for authors
// IsUser
if ($sc->session->exists()):  

  // IsOwner
  if ($is_owner):

?>

    <a href="/capture/<?php echo $_image->displayID; ?>/edit" class="button edit-image pull-right">
      <i class="fa fa-edit icon-left"></i>
      Edit
    </a>

<?php

    // IsNotProcessing
    if (! $_processing):
      if (! $_private):

?>
      
    <a href="#" class="button button-danger private-this private-image pull-left" data-image="<?php echo $_image->displayID; ?>" title="Private Image" data-tooltip data-placement="right">
      <i class="fa fa-lock"></i>
    </a>

<?php

        // Else IsPrivate
        else:

?>
      
    <a href="#" class="button button-primary publish-this private-image pull-left" data-image="<?php echo $_image->displayID; ?>" title="Publish Image" data-tooltip data-placement="right" data-delay="1000">
      <i class="fa fa-check icon-left"></i>Publish
    </a>

<?php
        
      // End IsNotPrivate
      endif;

    // End IsNotProcessing
    endif;

  // End IsOwner
  endif;
  
// Else not user
elseif (!$sc->common->isMobile):
 
// End IsUser
endif;
  
?>

  </div><!-- .container -->
</section><!-- #top-bar --> 