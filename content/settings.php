<?php

  require_once('../api/site.init.php');

  // Must be logged in to access
  $sc->session->privacyCheck(1);
  $mode = !empty($_GET['mode']) ? strtolower($_GET['mode']) : 'general';
  $general = ($mode == 'general') ? true : false;  
  
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

  <title><?php echo !$general ? ucwords($mode) . ' | ' : ''; ?>Settings | <?php echo SITE_NAME; ?></title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 
 
<?php include('helpers/body-start.php'); ?>


<?php include('helpers/header.php'); ?>


<main>
  <section class="posh start">
    <div class="container">
      <h1>
        Settings
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
    </div><!-- .container -->
  </section><!-- .posh -->

  <section class="posh to-left <?php echo $general ? '' : 'end'; ?>">
    <div class="container">

      <?php 

        $active_class = ' role="presentation" class="active"'; 

      ?>
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li<?php echo ($mode == 'general') ? $active_class : ''; ?>>
            <a href="/<?php echo $sc->user->username; ?>/settings">General</a>
          </li>
          <li<?php echo ($mode == 'sessions') ? $active_class : ''; ?>>
            <a href="/<?php echo $sc->user->username; ?>/settings/sessions">Sessions</a>
          </li>
          <li<?php echo ($mode == 'security') ? $active_class : ''; ?>>
            <a href="/<?php echo $sc->user->username; ?>/settings/security">Security</a>
          </li>
          <li<?php echo ($mode == 'preferences') ? $active_class : ''; ?>>
            <a href="/<?php echo $sc->user->username; ?>/settings/preferences">Preferences</a>
          </li>
        </ul>

        <div class="tab-content normal-content">
          <div role="tabpanel" class="tab-pane fade in active">
            <?php 

              switch($mode) 
              {
                case 'sessions':
                  include('settings/settings-sessions.php'); 
                  break;
                  
                case 'security':
                  include('settings/settings-security.php'); 
                  break;
                  
                case 'preferences':
                  include('settings/settings-preferences.php'); 
                  break;
                
                default:
                  include('settings/settings-general.php');  
                  break;
              }

            ?> 
          </div>
        </div>
      </div> 
    </div><!-- .container -->
  </section><!-- .posh -->



<?php

  if($general):

?>

  <section class="text-center margin-bottom">
    <div class="container">
      <a href="#" class="small red" data-toggle="modal" data-target="#DeleteModal">
        <i class="fa fa-trash icon-left"></i>
        Delete Account
      </a>
    </div><!-- .container -->
  </section><!-- .posh -->

<?php

  endif;

?>


</main>



<?php

  if($general):

?>


<!-- Delete Account Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true" data-session="">
	<form id="delete-account" class="form" method="POST" action="/api/user/delete">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h3 class="pull-left">Delete Account</h3>
          <div class="clear"></div>
        </div>
        
        <div class="modal-body normal-content">
          <p>Deleting your account means: </p>          
          <ul>
            <li>
              <strong>all</strong> your images will be deleted, these are <strong>unrecoverable</strong>.
            </li>
            <li>
              Your username can be taken by another user.
            </li>
            <li>
              All data about you will be gone. I like to keep things clean.
            </li>
          </ul>
          
          <hr />
          
          <div class="form-group">
            <label for="delete-password">Finally, please confirm your password</label>
            <input id="delete-password" type="password" placeholder="Enter Password" class="form-control"  required/>
          </div>
        </div><!-- .modal-body -->

        <div class="modal-footer">
          <input type="hidden" name="token" id="token" value="<?php echo hash('sha512', $sc->user->username . AUTH); ?>">
          <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
          <button type="submit" class="button button-primary">I understand, delete my account</button>
        </div><!-- .modal-footer -->   

      </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
  </form>
</div><!-- #DeleteModal -->   



<!-- Confirm Modal -->
<div class="modal fade" id="ConfirmModal" tabindex="-1" role="dialog" aria-labelledby="ConfirmModal" aria-hidden="true" data-session="">
	<form id="save-email" class="form" method="POST" action="#">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h3 class="pull-left">Confirm Password</h3>
          <div class="clear"></div>
        </div>
        
        <div class="modal-body">
          
          <div class="form-group margin-none">
            <label for="email-password">Please enter your password</label>
            <input id="email-password" type="password" placeholder="Enter Password" class="form-control"  required/>
          </div>

        </div><!-- .modal-body -->

        <div class="modal-footer">
          <button id="confirm-cancel" type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
          <button type="submit" class="button button-primary">Save Settings</button>
        </div><!-- .modal-footer -->   

      </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
  </form>
</div><!-- #SessionModal -->    

<?php

  endif;

?>




<?php 

if($mode == 'sessions'):

?>

<!-- Session Modal -->
<div class="modal fade" id="SessionModal" tabindex="-1" role="dialog" aria-labelledby="SessionModal" aria-hidden="true" data-session="">
  <form id="end-session" class="form" method="POST" action="/api/session/end">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h3 class="pull-left">End Session</h3>
          <div class="clear"></div>
        </div>
        
        <div class="modal-body">
          
          <div class="session-group form-group margin-none">
            <label for="session-password">Please enter your password</label>
            <input id="session-password" type="password" placeholder="Enter Password" class="form-control" required/>
          </div>

        </div><!-- .modal-body -->

        <div class="modal-footer">
          <button id="session-cancel" type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
          <button type="submit" class="button button-primary">End Session</button>
        </div><!-- .modal-footer -->   

      </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
  </form>
</div><!-- #SessionModal -->    

<?php

endif;

if($mode == 'security'):

?>

<!-- Security Modal -->
<div class="modal fade" id="SecurityModal" tabindex="-1" role="dialog" aria-labelledby="SessionModal" aria-hidden="true" data-session="">
	<form id="security-form" class="form" method="POST" action="/api/user/update_security">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h3 class="pull-left">Update Settings</h3>
          <div class="clear"></div>
        </div>
        
        <div class="modal-body">
          
          <div class="session-group form-group margin-none">
            <label for="old-password">Please enter your password</label>
            <input id="old-password" type="password" placeholder="Enter Password" class="form-control" required/>
          </div>

        </div><!-- .modal-body -->

        <div class="modal-footer">
          <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
          <button type="submit" class="button button-primary">Update</button>
        </div><!-- .modal-footer -->   

      </div><!-- .modal-content -->
    </div><!-- .modal-dialog -->
  </form>
</div><!-- #SecurityModal -->    

<?php

endif;

?>


<?php include('helpers/footer.php'); ?>
<?php include('helpers/end-js.php'); ?>

</body>
</html>
