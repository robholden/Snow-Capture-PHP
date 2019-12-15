<?php

// Display user related it stuff
// IsUser
if ($sc->session->exists()):

  // IsUserPage
  $temp_user = $sc->common->user();
  if ($temp_user->exists()):
    
?>

<!-- Report User -->
<div class="modal fade" id="ReportUserModal" tabindex="-1" role="dialog" aria-labelledby="ReportUser" aria-hidden="true">
  <form id="report-user" method="POST" action="#"	class="modal-dialog form" data-id="<?php echo $temp_user->displayID; ?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h3 class="pull-left">
      		Report 
          <?php echo $temp_user->displayName; ?>
        </h3>
        <div class="clear"></div>
      </div><!-- .modal-header -->
      
      <div class="modal-body normal-content row">
        <div class="col-xs-12 form-group">
          <label for="report-type">
            You are reporting a violation of the Snow Capture Terms of Service. All reports are confidential. 
            <br />
            <br /> 
            Reason <span class="codered">(required)</span>: 
          </label>
          <select id="report-type" class="form-control">
            <option value="1">Spam</option>
            <option value="2">Scammer</option>
            <option value="3">Unwanted contact</option>
            <option value="4">Hate speech or racism</option>
            <option value="5">Threats of violence</option>
            <option value="6">Identity theft or stolen personal information</option>
            <option value="7">Copyright infringement</option>
            <option value="8">Other</option>
          </select>
      	</div>
        <div class="col-xs-12 form-group">
          <label for="report-comment">Comments</label>
          <textarea rows="5" id="report-comment" maxlength="500" class="form-control"></textarea>
      	</div>
      </div><!-- .modal-body -->
      
      <div class="modal-footer">
        <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
        <button type="submit" class="button button-primary">Report User</button>
      </div><!-- .modal-footer -->
    </div><!-- .modal-content -->
  </form><!-- .modal-dialog -->
</div><!-- #ReportUserModal -->

<?php 

  // End IsUserPage
  endif;
  
?>







<?php 
  
  // IsImagePage
  $temp_image = $sc->common->image();
  if ($temp_image):
    
?>

<!-- Report User -->
<div class="modal fade" id="ReportImageModal" tabindex="-1" role="dialog" aria-labelledby="ReportImage" aria-hidden="true">
  <form id="report-image" method="POST" action="#" class="modal-dialog form" data-id="<?php echo $temp_image->displayID; ?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        
        <h3 class="pull-left">Report 
          <?php echo $temp_image->title; ?>
        </h3>
        
        <div class="clear"></div>
      </div><!-- .modal-header -->
      
      <div class="modal-body normal-content row">
        <div class="col-xs-12 form-group">
          <label for="report-type">
            You are reporting a violation of the Snow Capture Terms of Service. All reports are confidential. 
            <br />
            <br /> Reason 
            <span class="codered">(required)</span>: 
          </label>
  
          <select id="report-type" class="form-control">
              <option value="1">Irrelevant</option>
              <option value="2">Copyright infringement</option>
              <option value="3">Other</option>
          </select>
        </div><!-- .form-group -->
        
        <div class="col-xs-12 form-group">
          <label for="report-comment">Comments</label>
          <textarea rows="5" id="report-comment" maxlength="500" class="form-control"></textarea>
        </div><!-- .form-group -->
      </div><!-- .modal-body -->
            
      <div class="modal-footer">
        <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
        <button type="submit" class="button button-primary">Report Image</button>
      </div><!-- .modal-footer -->
    </div><!-- .modal-content -->
  </form><!-- .modal-dialog -->
</div><!-- #ReportImageModal -->

<?php 

  // End IsImagePage
  endif;

?>








<!-- UPLOAD MODAL -->
<?php include WEB_ROOT . 'content/views/upload.php'; ?>












<?php 
  
  // IsAdmin
  if($sc->user->isAdmin()):
    
?>

<!-- Reject Image -->
<div class="modal fade" id="RejectImageModal" tabindex="-1" role="dialog" aria-labelledby="RejectImage" aria-hidden="true">
  <form id="reject-image" method="POST" action="#" class="modal-dialog form" data-id="">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="pull-left">Reject Image</h3>
        <div class="clear"></div>
      </div>
      
      <div class="modal-body normal-content row">        
        <div class="col-xs-12 form-group">
          <label for="reject-reason">Reason</label>
          <textarea rows="5" id="reject-reason" maxlength="500" class="form-control" required></textarea>
          
          <select class='reason-box form-control margin-top'>
          	<option value=''>-- Choose Answer --</option>
          	<option value='We think your image quality is too low.'>Poor Quality</option>
          	<option value='We think the image will be offensive to others.'>Offensive</option>
          	<option value='We detected the image is copyrighted elsewhere - please contact us with proof of ownership.'>Copyrighted</option>
          	<option value='The image is not snow related enough.'>Unrelated</option>
          	<option value="We don't think it is quite right for our site.">Not Quite Right</option>
          </select>
        </div>
      </div><!-- .modal-body -->

      <div class="modal-footer">
        <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
        <button type="submit" class="button button-danger">Reject Image</button>
      </div><!-- .modal-footer -->   

    </div><!-- .modal-content -->
  </form><!-- .modal-dialog -->
</div><!-- #DisableUserModal --> 

<?php 

  // End IsAdmin
  endif;
  
?>










<div id="upload-cover">
  <div class="circle" id="upload-progress"></div>
  <div class="upload-status-container">
    <i id="upload-status">Uploading</i>
  </div>
  <div id="upload-dots" class="loading-dots"><span></span><span></span><span></span></div>
</div><!-- #upload-cover -->

<div id="confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ConfirmDialog" aria-hidden="true">
  <div class="modal-dialog">
    <form id="confirm-dialog" action="#">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
        
          <h3 class="pull-left"></h3>
          <div class="clear"></div>
        </div><!-- .modal-header -->
        
        <div class="modal-body"></div>

        <div class="modal-footer">
          <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
          <button type="submit" class="button button-primary">Yes</button>
        </div><!-- .modal-footer -->
      </div>
    </form>
  </div><!-- .modal-dialog -->
</div><!-- #confirm-modal -->


<?php 

// End IsUser
endif;

?>












<?php 

// IsMobile && HasFilter
if ($sc->common->isMobile && $sc->vars->hasFilter):
  
?>

<div id="filter-form">
  <div class="filter-bar text-center">
    <a href="#" class="close-navs pull-left">Cancel</a>
    <a href="#"	class="clear-filters">Reset</a>
    <a href="#" class="update-filter pull-right">
      Refine Images 
      <i class="fa fa-check icon-right"></i>
    </a>
    
    <div class="clear"></div>
  </div><!-- .filter-bar -->
  
  <div id="mobile-filter" class="container">
    <?php include(WEB_ROOT . 'content/filter/content.php'); ?>
  </div>
</div>
<!-- #filter-form -->

<?php

// End isMobile && hasFilter
endif;

?>





<input id="type" name="type" type="hidden" value="<?php echo $sc->vars->pageType; ?>" />    
<input id="user" name="user" type="hidden" value="<?php $u = $sc->common->user(); echo !$u->exists() ? '' : $u->username; ?>" />
<input id="filter-set" name="filter-set" type="hidden" value="<?php echo ($sc->common->filterSet() ? "true" : "false"); ?>" />




        
<footer>
  <div class="text-center">
  	<a href="#" class="button button-primary margin-bottom" data-scroll="body">back to top <i class="fa fa-arrow-up icon-right"></i></a>
	</div>
	
  <div class="container header-container">
    <div class="row">
      <ul class="col-xs-12 col-sm-4">
        <li>
          <a href="mailto:<?php echo $sc->validate->antispambot('contact@snowcapture.com'); ?>">
            <i class="fa fa-envelope icon-left"></i> 
            Contact Support	
          </a>
        </li>
        
        <li>
          <a href="mailto:<?php echo $sc->validate->antispambot('contact@snowcapture.com'); ?>">
            <i class="fa fa-edit icon-left"></i> 
            Leave Feedback
          </a>
        </li>
  
        <li>
          <a href="/policies/terms">
            <i class="fa fa-lock icon-left"></i> 
            Terms & Conditions
          </a>
        </li>
  
        <li>
          <a href="/policies/privacy">
            <i class="fa fa-lock icon-left"></i> 
            Privacy
          </a>
        </li>
  
        <li>
          <a href="/policies/cookies">
            <i class="fa fa-lock icon-left"></i> 
            Cookies
          </a>
        </li>
      </ul>
  
      <ul class="col-xs-12 col-sm-4 text-center">
      
      <?php if ($sc->session->exists()): ?>
        <li>
          <div id="beta-resort" class="<?php if($sc->session->exists()) { if ($sc->user->status < 0) { echo 'hidden'; } } ?>">
            <label for="request-resort"><h5>Request Location</h5></label>
            <form id="beta-resort-form">
              <input id="request-resort" type="text" autocomplete="off" maxlength="255" placeholder="Enter location name.." />
              <button type="submit">
                <i class="fa fa-envelope-o"></i>
              </button>
            </form>
          </div><!-- #beta-resort -->
        </li>
      <?php endif; ?>
      </ul>
      
      <ul class="col-xs-12 col-sm-4 text-right">
      <?php if ($sc->session->exists()): ?>
        <li>
          <a href="/sign-out" class="logout to-the-right">
            <i class="fa fa-sign-out icon-left"></i> 
            Sign Out
          </a>
        </li>
        
      <?php else: ?>
        
        <li>
          <a href="/sign-in" class="to-the-right"> 
            Sign In 
            <i class="fa fa-sign-in icon-right"></i>
          </a>
        </li>
          
        <li>
          <a href="/sign-up" class="to-the-right"> 
            Sign Up 
            <i class="fa fa-edit icon-right"></i>
          </a>
        </li>
      <?php endif; ?>
  
        <li>
          <a href="/stay-updated" class="to-the-right"> 
            Stay Updated
          </a>
        </li>
        <li>
          <a href="/about-us" class="to-the-right"> 
            About Us 
          </a>
        </li>
        <li>
          <a href="/how-it-works" class="to-the-right"> 
            How It Works
          </a>
        </li>
      </ul>
    </div><!-- .row -->
  
    <p class="text-center margin-top">              
      <a href="https://www.twitter.com/TheSnowCapture" class="social-icon twitter">
        <i class="fa fa-twitter"></i>
      </a>
      
      <br />
      
      &copy;<?php echo date('Y'); ?> Snow Capture 
    </p>
  </div><!-- .container -->
</footer>
