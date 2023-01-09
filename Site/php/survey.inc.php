<?php
  require_once('globals.php');
  
  #survey first loads by retrieving the questions from the following json file
  $criteria = $survey_json['user_type'][$_SESSION['judgeType']];

  #prep form but don't load it yet
  echo '<form class="survey" action="'.(CONFIG::getRootDIR(true).'php/register_vote.php').'" method="POST" name="form__survey">';       
      //NOTICE: This is the only query project wide that doesn't use DB_TBLREF constants as this one is easier to read this way due to subqueries   
      $query = "SELECT t.id, t.name
                  FROM team t
                  WHERE t.id NOT IN (SELECT v.team_id
                                      FROM vote_result v
                                      WHERE v.voter_id = '{$_SESSION['judgeID']}' AND v.voter_type = '{$_SESSION['judgeType']}') ";
      if ($_SESSION['judgeType'] !== 'student') {
        if ($_SESSION['judgeType'] === 'company') {
          $query.="AND t.education_division = '1'"; #companies can only vote for IHCC (this may be easier to set in the survey_criteria.json file if it becomes too complex)
        }
        else {
          #anything that is not a student or company can only vote for any team that's not in their own division
          $query.="AND t.education_division <> '1' AND t.education_division <> (SELECT education_division FROM division_rep WHERE id = '{$_SESSION['judgeID']}') ";
        }
      }
      
      $query.='ORDER BY t.id ASC, t.name ASC;';
      $allowed_teams = $db->rawQuery($query);

      #check if user has any teams remaining to vote for
      if (count($allowed_teams) > 0) {
        #has remaining teams -> populate teams in <select>
        echo '<div class="survey-group"><div class="topic">Select the team to vote for:</div><select name="team_id" required>';
          foreach ($allowed_teams as $key => $value) {
            echo '<option value="'.$value[DB_TBLREF_TEAM::ID].'">'.$value[DB_TBLREF_TEAM::NAME].'</option>';
          }
        echo '</select></div>';
        #populate questions from json file
        foreach ($criteria as $key => $value) {
          echo '<div class="survey-group">
                  <div class="topic">'.$value['topic'].':
                    <span class="desc">'.$value['desc'].'</span>
                  </div>
                  <div class="rating">';
                    #display the scale (1-10 for example) based on json 'config' max_rating setting
                    for ($i=0; $i < (int)$survey_json['config']['max_rating']; $i++) { 
                      $radio = "radio-". $key . ($i + 1);
                      echo '<input type="radio" name="Criteria_'.($value['topic']).'" class="survey-radio" id="'.$radio.'" value="'.($i+1).'" required/>
                            <label class="survey-radio-label" for="'.$radio.'">'.($i+1).'</label>';
                    }
          echo '</div></div>';
        }
        #only show submit button if criteria is found (protects again file name changes or otherwise and false db inserts)
        if (!is_null($criteria) && count($criteria) > 0) echo '<button type="submit" name="form__survey__submit"/>Submit</button>'; 
      }
      else {
        #user has no more remaining teams to vote for
        $alert_msg = 'You have succesfully voted for all the teams!';
        include_once('php/success.inc.php');
      }

  echo '</form>';

?>