<?php
  require_once('../php/globals.php');

  $db->join(DB_TBLREF_TEAM::TBL_NAME.' AS t', 't.'.DB_TBLREF_TEAM::ID.'=v.'.DB_TBLREF_VOTERESULT::TEAM);
  $db->orderBy('t.'.DB_TBLREF_TEAM::NAME, 'ASC');
  $results = $db->get(DB_TBLREF_VOTERESULT::TBL_NAME.' AS v', null, array(DB_TBLREF_TEAM::NAME, DB_TBLREF_VOTERESULT::RATING, DB_TBLREF_VOTERESULT::JUDGETYPE));
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin Vote Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    table, td, th {
    border: 1px solid black;
    }
    table {
      border-collapse: collapse;
      width: 40%;
    }
  </style>
</head>
<body>
  <a href="index.php">Go Back</a>
  <hr>
  <form  style="margin:15px 0;" action="admin-update.php" method="POST" name="form__dropVotes">
    <input type="hidden" name="drop_tblname" value="<?php echo DB_TBLREF_VOTERESULT::TBL_NAME; ?>"/>
    <input type="submit" value="Drop All Votes" onclick="confirmDropVotes(); return false;"/>
  </form> <!-- END OF DROP VOTES FORM -->
  <hr>
  <p>Filter Results:</p>
  <sup>Note: If no boxes are checked, all results will be displayed</sup>
  <form action="display_results.php" method="POST">
    <?php
        #load availabed judge types (based on survey_criteria.json file)
        $user_types = $survey_json['user_type'];
        $i = 0;
        foreach ($user_types as $key => $value) {
          $ischecked = "";
          if (isset($_POST['user_type'.++$i])) {
            $ischecked = "checked";
          }
          else {
            unset($user_types[$key]);            
          }
          echo '<label style="margin-right:5px;"><input type="checkbox" name="user_type'.$i.'" value="'.$key.'"'. $ischecked .'>'.$key.'</label>';
        }
        $user_types = array_keys($user_types); #this filters the db results to only the checked judge types checkbox
    ?>
    <input type="submit" value="Go" name="query-filter__submit"/>
  </form>
  <h2>Results:</h2>

  <?php
    $final_results = array();
    if ($db->count > 0) {
      $team = '';
      foreach ($results as $arr) {
        $json = json_decode(json_encode($arr),true);
        $rating = json_decode($json[DB_TBLREF_VOTERESULT::RATING],true);
        
        if ($team !== $json[DB_TBLREF_TEAM::NAME]) {
          #this assumes the db data is sorted ASC by team name
          $team = $json[DB_TBLREF_TEAM::NAME];
          $final_results[$json[DB_TBLREF_TEAM::NAME]] = array();
        }

        #pushes only the type of votes checked in boxes in filter section
        if (count($user_types) === 0 || in_array($json[DB_TBLREF_VOTERESULT::JUDGETYPE], $user_types)) {
          array_push($final_results[$json[DB_TBLREF_TEAM::NAME]], array_reduce($rating, 'reduce_sum'));
        }
      }
    }
    if (CONFIG::ISDEBUG) echo array_prettify($final_results); #array_prettify is a custom function and should not be used in production
  ?>
  <table>
    <tbody>
      <?php
        $sorted = array();
        foreach ($final_results as $key => $value) {          
          $sorted[$key] = (count($value) === 0) ? 0 : array_reduce($value, 'reduce_sum',0)/count($value);
        }
        arsort($sorted);
        foreach($sorted as $key => $value) {
          echo '<tr>
                  <td>'.$key.'</td>
                  <td style="text-align:center">'.$value.'</td>
                </tr>';
        }
      ?>
    </tbody>
  </table>

  <script>
    function confirmDropVotes() {
      if(confirm("This will delete all votes. This action can not be undone. To continue click 'OK'")) documents.forms.form__dropVotes.submit();
    }
  </script>
</body>
</html>