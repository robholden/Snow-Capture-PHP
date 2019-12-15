<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form search search-icon margin-top-xl">
	<h4>Send test email</h4>
	<br />
  <input type="email" name="email" placeholder="Enter your email..." style="width: 100%; max-width: 500px;" value="<?php echo $sc->user->email; ?>">
  <button type="submit" class="search-btn"><i class="fa fa-envelope-o"></i></button>    
</form>

<br />
<br />

      
<?php

  // Get images for moderation
  $logs = $sc->user->getEmailLogs();
  
  if(sizeof($logs) > 0):  
          
?>

      <table class="table table-striped session-table" style="width:100%">
        <thead>
          <tr>
            <th>Username</th>
            <th>Subject</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        
<?php 

    foreach ($logs as $key => $log):

?>

        <tr> 
          <td><?php echo !empty($log->username) ? $log->username : '-----------------'; ?></td>
          <td><?php echo $log->subject; ?></td>
          <td><?php echo $log->date; ?></td>            
        </tr>

<?php

    endforeach;
    
?>

        </tbody>
      </table>

<?php 
  
    else:
        echo '<br /><br /><h4>No logs found</h4><br /><br />';
    endif;

?>