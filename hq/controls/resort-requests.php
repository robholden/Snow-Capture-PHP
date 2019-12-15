<a href="#" id="add-resort" class="button button-primary margin-bottom" data-toggle="modal" data-target="#AddResortModal">
  <i class="fa fa-plus icon-left"></i> Add Resort
</a>

<br />

<?php

  // Get images for moderation
  $resort_requests = $sc->user->getResortRequests();

  if(sizeof($resort_requests) > 0):
          
?>

      <table class="table table-striped session-table" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Request</th>
            <th>Response</th>
          </tr>
        </thead>
        <tbody>
        
<?php 

    foreach ($resort_requests as $key => $resort):

?>

      <tr class="resort-request" data-request="<?php echo $resort->id; ?>">
        <td><?php echo $key; ?></td>    
        <td><?php echo $resort->username; ?></td>
        <td><?php echo $resort->resort; ?></td>
        <td>
          <a href="#" class="delete-request" data-request="<?php echo $resort->id; ?>">
            <code>Delete Request</code>
          </a>
        </td>            
      </tr>

<?php

    endforeach;
    
?>

        </tbody>
      </table>

<?php 

  else:
      echo '<br /><br /><h4>No resort requests</h4><br /><br />';
  endif;

?>