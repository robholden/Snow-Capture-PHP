<?php 
          
  $all_ips = '';
  foreach($sc->security->bannedIps() as $key => $ip)     
  {
    $sep = $key > 0 ? ', ' : ''; 
    $all_ips .= $sep . $ip;
  } 

?>
      
      <form id="ip-form" method="POST" action="/api/admin/ip_ban">
        <textarea id="ip-textarea" class="form-control" rows="10"><?php echo $all_ips; ?></textarea>
        <button type="submit" class="button button-primary margin-top pull-right">Update</button>
        <div class="clear"></div>
      </form>

      
      <br />
      
<?php

  // Get attempts
  $attempts = $sc->user->getAttempts();
  
  if(sizeof($attempts) > 0): 
          
?>

      <table class="table table-striped session-table" style="width:100%">
        <thead>
          <tr>
            <th>IP</th>
            <th>URL</th>
            <th>Date Sent</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        
  <?php 

    foreach ($attempts as $key => $attempt):
      $attempt_type = $attempt->status;
      switch ($attempt_type)
      {
        case 0:
          $attempt_type = 'High';
          break;
          
        case 1:
          $attempt_type = 'Medium';
          break;
          
        default:
          $attempt_type = 'Low';
      }
?>

          <tr>   
            <td><?php echo $attempt->ip; ?></td>
            <td><?php echo $attempt->url; ?></td>
            <td><code><?php echo $attempt->date; ?></code></td>   
            <td><code><?php echo $attempt_type; ?></code></td>                
          </tr>
    
<?php 

    endforeach;
    
?>

        </tbody>
      </table>

<?php 

  else:
      echo '<br /><br /><h4>No Attempts Made</h4><br /><br />';
  endif;

?>      