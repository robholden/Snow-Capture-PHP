<?php

  require_once('../api/site.init.php');
  $is_admin = $sc->session->exists() ? $sc->user->isAdmin() ? true : false : false;
  
  if($is_admin)
  {
    if(isset($_POST['title']) && isset($_POST['content']))
    {
      $title = $db->escape($_POST['title']);
      $content = $db->escape($_POST['content']);

      $query = "INSERT INTO sc_change_log(title, content) VALUES ('" . $title . "', '" . $content . "')";
      
      if(isset($_POST['id']))
      {
        $id = $db->escape($_POST['id']);

        if(is_numeric($id))
        {
          $query = "UPDATE sc_change_log SET title = '" . $title . "', content = '" . $content . "' WHERE id =" . $id;
        }
      }

      $log = $db->query($query);
      if($log)
      {
        header('Location: /stay-updated');
      }
    }
 
    elseif(isset($_POST['delete']) && isset($_POST['id']))
    {
      $id = $db->escape($_POST['id']);

      if(is_numeric($id))
      {
        $query = "DELETE FROM sc_change_log WHERE id = " . $id;
      }
      
      $log = $db->query($query);
      if($log)
      {
        header('Location: /stay-updated');
      }
    }
  }

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

  <meta name="description" content="Stay up to date with Snow Capture!" />
  <meta name="keywords" content="Snow Capture, Logs, Updates" />
  
  <title><?php echo SITE_NAME; ?> | Stay Updated</title>
  
  <?php include('helpers/css.php'); ?> 
  <?php include('helpers/js.php'); ?> 

</head> 

<?php include('helpers/body-start.php'); ?>


<?php include('helpers/header.php'); ?>

<main>
  <section class="posh white start">
    <div class="container">
      <h1>
        Stay Updated       
      </h1>
      
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      <span class="break-dot"></span>
      
      
    <?php

      if($is_admin):

    ?>
    
      <br />
      <a href="#" id="add-log" class="button button-primary" data-id="" data-toggle="modal" data-target="#AddLog">
        New Log <i class="fa fa-plus icon-right"></i>
      </a>

    <?php

      endif;

    ?>
    </div><!-- .container -->
  </section><!-- .posh -->



  <br /><br /> 
   
<?php
  
  $last_log = 0;
  $curr_log = 0;
  $index = 0;
  $log = $db->query("SELECT * FROM sc_change_log ORDER BY id DESC");
  if($log)
  {
    while($changes = $db->fetchArray($log)):

?>
  
  <?php if($index > 0): ?>
  <div class="container">      
    <span class="break-dot"></span>
    <span class="break-dot"></span>
    <span class="break-dot"></span>
  </div>
  <?php endif; ?>
  <section<?php echo  $is_admin ? ' id="' . $changes['id'] . '"' : ''; ?> class="posh normal-content to-left <?php echo $curr_log == 0 ? '' : 'whiter'; $curr_log = $curr_log == 0 ? 1 : 0; ?>">
    <div class="container">
 
      <h3 class="title"><?php echo $changes['title']; ?></h3>
      <div class="content"><?php echo $changes['content']; ?></div>

  <?php

    if($is_admin):

  ?>

      <br />
      <a href="#" class="edit-log button button-primary pull-left" data-id="<?php echo $changes['id']; ?>" data-toggle="modal" data-target="#AddLog">
        Edit Log <i class="fa fa-edit icon-right"></i>
      </a>
  
      <form action="/stay-updated" method="POST">
        <input name="id" type="hidden" value="<?php echo $changes['id']; ?>" />
        <input name="delete" type="hidden" value="true" />
        <button type="submit" class="button button-danger margin-left pull-left">
          Delete Log <i class="fa fa-trash icon-right"></i>
        </button>
      </form>

  <?php

    endif;

  ?>            

    </div><!-- .container -->
  </section><!-- .posh -->

<?php
    
      $last_log = $changes['id'] > $last_log ? $changes['id'] : $last_log;
      $index++;
    endwhile;
  }

?>
  <br /><br /> 
</main>

<?php include('helpers/footer.php'); ?>

<?php

  if($is_admin):

?>

<!-- Change resort -->
<div class="modal fade form" id="AddLog" tabindex="-1" role="dialog" aria-labelledby="AddResort" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h2>New Log</h2>
      </div>
      <div id="log-alert" class="alert alert-danger alert-dismissible hidden" role="alert">
        <p></p>
      </div>
      <form id="log-form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="modal-body padding">
          <div class="input-group margin-bottom">
            <label for="title">Title</label>
            <input name="title" id="title" value="<?php echo date("d.m.Y"); ?>" class="form-control validate" type="text" maxlength="255" placeholder="..." required>
          </div><!-- .input-group -->

          <div class="input-group">
            <label for="content">Change</label>
            <textarea id="content" name="content" rows="4" class="form-control validate" placeholder="..." required>Log v.<?php echo $last_log; ?></textarea> 
          </div><!-- .input-group -->
        </div><!-- .modal-body -->

        <input type="hidden" value="" id="log-form-id" name="id" />

        <div class="modal-footer">
          <button type="button" class="button pull-left" data-dismiss="modal">Close</button>
          <button type="submit" class="button button-primary">Publish</button>
        </div><!-- .modal-footer -->
      </form>
    </div><!-- .modal-content -->
  </div><!-- .modal-dialog -->
</div><!-- #AddLog -->

<script>
  $(document).ready(function(e){
    $(document).on('click', '.edit-log', function(e){
      var id = $(this).data('id');
      $('#title').val($('#' + id + ' .title').html());
      $('#content').val($('#' + id + ' .content').html());
      $('#log-form-id').val($(this).data('id'));

      e.preventDefault();
    });
  });
</script>

<?php

  endif;

?>

<?php include('helpers/end-js.php'); ?>


</body>
</html>
