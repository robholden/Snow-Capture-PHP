<?php

  // Get images for moderation
  $users = $sc->user->getAllUsers();
  if(sizeof($users) > 0): 
          
?>

      <table class="table table-striped session-table" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Images</th>
            <th>Attempts</th>
            <th>Last Active</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        
<?php 

    foreach ($users as $key => $user):

?>

      <tr>
        <td><a href="/<?php echo $user->username; ?>"><?php echo $user->name; ?> (<?php echo $user->displayName; ?>)</a></td>    
        <td><a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a></td>
        <td><code><?php echo $user->countUploads(); ?></code></td>
        <td><code><?php echo $user->attempts; ?></code></td>
        <td><code><?php echo $user->lastAttemptedDate; ?></code></td>
        <td>
          <a href="#" class="disable-user <?php echo ($user->status == -1) ? 'hidden' : ''; ?>" data-id="<?php echo $user->displayID; ?>" data-toggle="modal" data-target="#DisableUserModal">
            <code>Active</code>
          </a>
         
          <a href="#" class="enable-user <?php echo ($user->status > 0) ? 'hidden' : ''; ?>" data-id="<?php echo $user->displayID; ?>">
            <code>Disabled</code>
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
      echo '<br /><br /><h4>No Users</h4><br /><br />';
  endif;

?>