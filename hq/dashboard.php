<?php

  require_once('../api/site.init.php');

  // Must be logged in to access
  $sc->session->privacyCheck(3);
  $mode = !empty($_GET['mode']) ? strtolower($_GET['mode']) : 'moderate-images';
  
  // TEST EMAIL
  if (isset($_POST['email']))
  {
    $response = (new Email)->testEmail($_POST['email']);
    echo '<br /> <br /> <br />';
    echo ($response === true ? 'MESSAGE SENT' : $response);
  }
  
?>
<?php require_once('../content/helpers/immediate.php'); ?>

<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8 lt-ie7" lang="en"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie10 lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie10 lt-ie9" lang="en"><![endif]-->
<!--[if IE 9]><html class="no-js lt-ie10" lang="en"><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!--[if IE]> <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<head>
  
  <?php include('../content/helpers/meta.php'); ?>
  
  <title>Admin Panel | <?php echo SITE_NAME; ?></title>
  
  <?php include('../content/helpers/css.php'); ?> 
  <?php include('../content/helpers/js.php'); ?>  
  
</head> 

<?php include('../content/helpers/body-start.php'); ?>

<?php include('../content/helpers/header.php'); ?>

<main>
  <section class="posh start">
    <div class="container">
      <h1>
        Admin Panel
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>

    </div><!-- .container -->
  </section><!-- .posh -->

  <section class="posh to-left end">
    <div class="container">

      <?php 

        $active_class = ' role="presentation" class="active"'; 

      ?>
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li<?php echo ($mode == 'users') ? $active_class : ''; ?>>
            <a href="/hq/users">Users</a>
          </li>
          <li<?php echo ($mode == 'reported-users') ? $active_class : ''; ?>>
            <a href="/hq/reported-users">Reported Users</a>
          </li>
          <li<?php echo ($mode == 'reported-images') ? $active_class : ''; ?>>
            <a href="/hq/reported-images">Reported Images</a>
          </li>
          <li<?php echo ($mode == 'moderate-images') ? $active_class : ''; ?>>
            <a href="/hq/moderate-images">Moderate Images</a>
          </li>
          <li<?php echo ($mode == 'resort-requests') ? $active_class : ''; ?>>
            <a href="/hq/resort-requests">Resort Requests</a>
          </li>
          <li<?php echo ($mode == 'banned-ips') ? $active_class : ''; ?>>
            <a href="/hq/banned-ips">Banned Ips</a>
          </li>
          <li<?php echo ($mode == 'email-log') ? $active_class : ''; ?>>
            <a href="/hq/email-log">Email Log</a>
          </li>
        </ul>

        <div class="tab-content normal-content">
          <div role="tabpanel" class="tab-pane fade in active">
            <?php 

              switch($mode) 
              {
                case 'users':
                  include('controls/users.php'); 
                  break;
                  
                case 'reported-users':
                  include('controls/reported-users.php'); 
                  break;
                  
                case 'reported-images':
                  include('controls/reported-images.php'); 
                  break;
                  
                case 'resort-requests':
                  include('controls/resort-requests.php'); 
                  break;
                  
                case 'banned-ips':
                  include('controls/banned-ips.php'); 
                  break;
                  
                case 'email-log':
                  include('controls/email-log.php'); 
                  break;

                case 'moderate-images':
                  echo '<div class="moderate-container">';
                  include('controls/moderation.php'); 
                  echo '</div>';
                  break;
                
                default:
                  echo phpversion();
                  break;
              }

            ?> 
          </div>
        </div>
      </div> 
    </div><!-- .container -->
  </section><!-- .posh -->
</main>



<!-- Change resort -->
<div class="modal fade form" id="AddResortModal" tabindex="-1" role="dialog" aria-labelledby="AddResort" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h2>Add Location</h2>
      </div>
      <div id="resort-alert" class="alert alert-danger alert-dismissible hidden" role="alert">
        <p></p>
      </div>
      <form id="resort-form" action="/api/admin/resort_add" method="POST">
        <div class="modal-body">
          <div class="row"> 
            <div class="col-xs-6">
              <div class="input-group margin-bottom">
                <label for="resort-name">Location Name</label>
                <input name="resort_name" id="resort-name" class="form-control validate" type="text" maxlength="50" placeholder="..." required>
              </div><!-- .input-group -->
            </div><!-- .col -->

            <div class="col-xs-6">
              <div class="input-group margin-bottom">
                <label for="country-id">Country</label>
                <select name="country_id" id="country-id" type="text" class="form-control validate" required>
                  <option value="">...</option>
          <?php

            $countries = (new Country)->getAll(); 
            foreach ($countries as $key => $country):

          ?>
                  <option value="<?php echo $country->code; ?>">
                    <?php echo $country->name; ?>
                  </option>
          <?php

            endforeach;
            
          ?>
                </select>
              </div><!-- .input-group -->
            </div><!-- .col -->
          </div><!-- .row -->


          <div class="row"> 
            <div class="col-xs-6">
              <div class="input-group">
                <label for="latitude">Latitude</label>
                <input name="latitude" id="latitude" class="form-control validate" type="text" placeholder="..." required>
              </div><!-- .input-group -->
            </div><!-- .col -->

            <div class="col-xs-6">
              <div class="input-group">
                <label for="longitude">Longitude</label>
                <input name="longitude" id="longitude" class="form-control validate" type="text" placeholder="..." required>
              </div><!-- .input-group -->
            </div><!-- .col -->
          </div><!-- .row -->
        </div><!-- .modal-body -->

        <div class="modal-footer">
          <button type="button" class="button pull-left" data-dismiss="modal">Close</button>
          <a href="#" id="geo-lookup" class="margin-right"><i id="temp-location" class="fa fa-globe"></i></a>
          <button type="submit" class="button button-primary">Add Location</button>
        </div><!-- .modal-footer -->
      </form>
    </div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div><!-- #AddResortModal -->


<!-- Disable User -->
<div class="modal fade" id="DisableUserModal" tabindex="-1" role="dialog" aria-labelledby="DisableUser" aria-hidden="true">
  <form id="disable-user" method="POST" action="#" class="modal-dialog form" data-id="">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h3 class="pull-left">Disable User</h3>
        <div class="clear"></div>
      </div>
      
      <div class="modal-body normal-content row">        
        <div class="col-xs-12 form-group">
          <label for="disable-comment">Comments</label>
          <textarea rows="5" id="disable-comment" maxlength="500" class="form-control" required></textarea>
        </div>
      </div><!-- .modal-body -->

      <div class="modal-footer">
        <button type="button" class="button pull-left" data-dismiss="modal">Cancel</button>
        <button type="submit" class="button button-primary">Disable User</button>
      </div><!-- .modal-footer -->   

    </div><!-- .modal-content -->
  </form><!-- .modal-dialog -->
</div><!-- #DisableUserModal --> 

<?php include('../content/helpers/footer.php'); ?>
<?php include('../content/helpers/end-js.php'); ?>

<script>
</script>
</body>
</html>
