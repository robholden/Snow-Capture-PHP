<?php 

  
  // Get sessions
  $sessions = $sc->session->getSessions();

?>

<div class="normal-content">

  <h3>
    Session Manager
  </h3>
  <p>
    Manage all your active sessions. You have the power to control where you are logged in.
  </p>
  
<?php 

if (sizeof($sessions) > 1):

?>

	<a href="#" data-toggle="modal" data-target="#SessionModal" class="button button-small margin-top clear-sessions">Clear sessions</a>

<?php 
  
    endif;
    
?>
  
  
  <hr />
  
  <div class="margin-bottom">
    Active Sessions: <strong><span id="active-count"><?php echo sizeof($sessions); ?></span></strong>
  </div>


<?php
  
  // Show sessions if there are any
  if($sessions):
  
    $_ids = "";
    foreach ($sessions as $key => $sess):      
      $active_sess = ($sc->session->id == $sess->id);
      if (!$active_sess)
      {
        $_ids .= ($key == 1 ? '' : ',') . $sess->displayID;
      }
  
?>

  <div class="row <?php echo !$active_sess ? 'active-session' : ''; ?> <?php echo $key > 0 ? 'margin-top-xl' : ''; ?>" data-session="<?php echo $sess->displayID; ?>">
    <div class="col-xs-12 col-md-4">
      <div class="panel-group" role="tablist">
        <div class="panel <?php echo $active_sess ? 'panel-success' : 'panel-info'; ?>">
          <div class="panel-heading session-heading" role="tab" id="heading_<?php echo $key; ?>">
            <button class="panel-title collapsed" role="button" data-toggle="collapse" href="#session_<?php echo $key; ?>" aria-expanded="false" aria-controls="session_<?php echo $key; ?>">            
              <strong><?php echo $sess->browser; ?></strong> 
              <?php echo $active_sess ? '<code class="small margin-left-xs">Current Session</code>' : ''; ?>              
            </button>
          </div>
          <div id="session_<?php echo $key; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo $key; ?>" aria-expanded="false">
            <ul class="list-group">
              <li class="list-group-item">IP Address: <strong><?php echo $sess->ipAddress; ?></strong></li>
              <li class="list-group-item">Last Accessed: <strong><?php echo date("d M Y (H:i:s)", strtotime($sess->lastActive)); ?></strong></li>
              <li class="list-group-item">
                <a href="#" data-toggle="modal" data-target="#SessionModal" class="red block init-end-session" data-session="<?php echo $sess->displayID; ?>">
                  <i class="fa fa-ban icon-left"></i> End Session 
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>      
    </div><!-- .col -->
  </div><!-- .row -->

<?php

    endforeach;  
    
    echo '<input id="active-sessions" type="hidden" value="' . $_ids .'" />';
    
  endif;

?>


</div>





