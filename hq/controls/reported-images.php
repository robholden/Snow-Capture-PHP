<?php

  // Get images for moderation
  $user_report = $sc->user->getUserReports(true);

  if(sizeof($user_report) > 0):
          
?>

      <table class="table table-striped session-table" style="width:100%">
        <thead>
          <tr>
            <th>Username</th>
            <th>Reported Image</th>
            <th>Type</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Response</th>
          </tr>
        </thead>
        <tbody>
              
<?php 

    foreach ($user_report as $key => $report):
      switch ($report->type) 
      {
        case 1:
          $strType = 'Irrelevant';
          break;
        
        case 2:
          $strType = 'Copyright infringement';
          break;
          
        default:
          $strType = 'Other';
      }
?>

      <tr class="user-report" data-report="<?php echo $report->id; ?>"> 
        <td><a href="/<? echo $report->username; ?>"><?php echo $report->username; ?></a></td>
        <td><a href="/capture/<?php echo $sc->security->encrypt($report->reported_id); ?>"><?php echo $report->title; ?></a></td>
        <td><?php echo $strType; ?></td>
        <td><?php echo $report->comment; ?></td>
        <td><?php echo $report->date; ?></td>
        <td>
          <a href="#" class="delete-report" data-report="<?php echo $report->id; ?>">
            <code>Delete Report</code>
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
      echo '<br /><br /><h4>Nothing has been reported</h4><br /><br />';
  endif;

?>