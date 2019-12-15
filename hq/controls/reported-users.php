<?php

  // Get images for moderation
  $user_report = $sc->user->getUserReports();

  if(sizeof($user_report) > 0):
          
?>

      <table class="table table-striped session-table" style="width:100%">
        <thead>
          <tr>
            <th>Username</th>
            <th>Reported User</th>
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
          $strType = 'Spam';
          break;
        
        case 2:
          $strType = 'Scammer';
          break;
        
        case 3:
          $strType = 'Unwanted contact';
          break;
        
        case 4:
          $strType = 'Hate speech or racism';
          break;
        
        case 5:
          $strType = 'Threats of violence';
          break;
        
        case 6:
          $strType = 'Identity theft or stolen personal information';
          break;
        
        case 7:
          $strType = 'Copyright';
          break;
          
        default:
          $strType = 'Other';
      }
?>

      <tr class="user-report" data-report="<?php echo $report->id; ?>">
        <td><a href="/<?php echo $report->username; ?>"><?php echo $report->username; ?></a></td>
        <td><a href="/<?php echo $report->title; ?>"><?php echo $report->title; ?></a></td>
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