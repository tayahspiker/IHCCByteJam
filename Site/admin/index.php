<?php 

    require_once("../php/globals.php");  

    $divisions = $db->orderBy(DB_TBLREF_ED_DIVISIONS::DIVISION, "ASC")->get(DB_TBLREF_ED_DIVISIONS::TBL_NAME);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Byte Jam Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .block { display: block; }
    .actions {margin-top: 15px;}
    [data-canDuplicate="true"]:not(:first-of-type) { margin: 15px 0px; }
  </style>
</head>
<body>
  <!-- BEGINS VOTING FIELDSET -->
  <fieldset>
    <legend>Voting Settings</legend>
    <form action="admin-update.php" method="POST" name="form__voting-settings">
      <?php
        $settings = $db->getOne(DB_TBLREF_ADMIN::TBL_NAME);

        if ((!is_null($settings[DB_TBLREF_ADMIN::VOTING_START_DATE]) && 
            date_today_in_range($settings[DB_TBLREF_ADMIN::VOTING_START_DATE], $settings[DB_TBLREF_ADMIN::VOTING_END_DATE]))) {
          echo "<p>Voting is currently <b>Open</b></p>";
        }
        else {
          echo "<p>Voting is currently <b>Closed</b></p>";          
        }
      ?>
      
      <label class="block">Voting Start Date Time: 
      <input type="datetime-local" name="voting-start-date" value="<?php if (!is_null($settings[DB_TBLREF_ADMIN::VOTING_START_DATE])) echo str_replace(' ', 'T',$settings[DB_TBLREF_ADMIN::VOTING_START_DATE]); ?>"/></label class="block">
      
      <label>Voting End Date Time: 
      <input type="datetime-local" name="voting-end-date" value="<?php if (!is_null($settings[DB_TBLREF_ADMIN::VOTING_END_DATE])) echo str_replace(' ', 'T',$settings[DB_TBLREF_ADMIN::VOTING_END_DATE]); ?>"/></label>
      <input type="hidden" name="action"/>
      
      <sup class="block">Note:<br> Having a starting date for votes to be open without an expiry date will allow votes indefintely until you turn them off.</sup>
      <sup class="block">Remove both dates to turn off voting</sup>
      <br>
      
      <button onclick="displayResults(); return false;">Display Results</button> 
      <input type="submit" name="form__voting-settings__submit" value="Save"/>      
    </form>
  </fieldset> <!-- ENDS VOTING FIELDSET -->
  
  <!-- BEGINS TEAMS FIELDSET -->
  <fieldset>
      <legend>Teams</legend>
      <?php 
        $db->orderBy(DB_TBLREF_TEAM::NAME, "ASC");
        $teams = $db->get(DB_TBLREF_TEAM::TBL_NAME, null, array(DB_TBLREF_TEAM::ID, DB_TBLREF_TEAM::NAME));
        echo '<ul>';
        foreach ($teams as $key => $value) {
          echo '<li>'.$value[DB_TBLREF_TEAM::NAME].'</li>';          
        }
        echo '</ul>';

        if (count($teams) > 0) {
          echo '<form style="margin:15px 0;" action="admin-update.php" method="POST" name="form__dropTeams">
                  <input type="submit" value="Drop All" onclick="confirmDropTeams(); return false;"/>
                  <input type="hidden" name="drop_tblname" value="'.DB_TBLREF_TEAM::TBL_NAME.'"/>
                </form>';
        }
      ?>
      <form action="admin-update.php" method="POST" name="form__addTeam" autocomplete="off">
        <div data-canDuplicate="true">
          <label>Name:</label>
          <input type="textbox" name="team_name[]" required/>
          <label>Education Division:</label>
          <select name="education_division[]">
              <?php 
                foreach ($divisions as $key => $value) {
                  echo '<option value="'.$value[DB_TBLREF_ED_DIVISIONS::ID].'">'.$value[DB_TBLREF_ED_DIVISIONS::DIVISION].'</option>';
                }
              ?>
          </select>
        </div>
      
        <div class="actions">
          <button onclick="addDuplicateFields(this); return false;">Add Another Team</button>
          <input type="submit" name="form__addTeam__submit" value="Save All"/>
        </div>
      </form>  
  </fieldset> <!-- ENDS TEAMS FIELDSET -->
    
  <!-- BEGINS COMPANIES FIELDSET -->
  <fieldset>
      <legend>Companies</legend>
      <?php 
        $db->orderBy(DB_TBLREF_COMPANY::NAME, "ASC");
        $companies = $db->get(DB_TBLREF_COMPANY::TBL_NAME, null, array(DB_TBLREF_COMPANY::NAME, DB_TBLREF_COMPANY::PIN));
        if (count($companies) > 0) {
          echo "<ul>";
          foreach ($companies as $key => $value) {
            echo "<li>". $value[DB_TBLREF_COMPANY::NAME] . " <b>PIN:</b> " . $value[DB_TBLREF_COMPANY::PIN] . "</li>" ;
          }
          echo "</ul>";
          echo '<form style="margin:15px 0;" action="admin-update.php" method="POST" name="form__dropCompanies">
                  <input type="submit" value="Drop All"/>
                  <input type="hidden" name="drop_tblname" value="'.DB_TBLREF_COMPANY::TBL_NAME.'"/>
                </form>';
        }
      ?>
      <form action="admin-update.php" method="POST" name="form__addCompany" autocomplete="off">
        <div data-canDuplicate="true">
          <label>Name:</label>
          <input type="textbox" name="company_name[]" required/>
        </div>
        
        <div class="actions">
          <button onclick="addDuplicateFields(this); return false;">Add Another Company</button>
          <input type="submit" name="form__addCompany__submit" value="Save All"/>        
        </div>
      </form>  
  </fieldset><!-- ENDS COMPANIES FIELDSET -->
  
  <!-- BEGINS SCHOOL REPS FIELDSET -->
  <fieldset>
      <legend>School Rep [Teacher/Advisor]</legend>
      <?php 
        $db->orderBy(DB_TBLREF_REP::NAME, "ASC");
        $teachers = $db->get(DB_TBLREF_REP::TBL_NAME, null, array(DB_TBLREF_REP::NAME,DB_TBLREF_REP::PIN));
        echo '<ul>';
        foreach ($teachers as $key => $value) {
          echo "<li>".$value[DB_TBLREF_REP::NAME] . " PIN: <b>" . $value[DB_TBLREF_REP::PIN] ."</b></li>";          
        }
        echo '</ul>';

        if (count($teachers) > 0) {
          echo '<form style="margin:15px 0;" action="admin-update.php" method="POST" name="form__dropTeachers">
                  <input type="submit" value="Drop All"/>
                  <input type="hidden" name="drop_tblname" value="'.DB_TBLREF_REP::TBL_NAME.'"/>
                </form>';
        }
      ?>
      <form action="admin-update.php" method="POST" name="form__addRep" autocomplete="off">
        <div data-canDuplicate="true">
          <label>Name:</label>
          <input type="textbox" name="rep_name[]" required/>
          <label>Education Division:</label>
          <select name="education_division[]">
              <?php 
                foreach ($divisions as $key => $value) {
                  echo '<option value="'.$value[DB_TBLREF_ED_DIVISIONS::ID].'">'.$value[DB_TBLREF_ED_DIVISIONS::DIVISION].'</option>';
                }
              ?>
          </select>
        </div>
        
        <div class="actions">
          <button onclick="addDuplicateFields(this); return false;">Add Another Rep</button>
          <input type="submit" name="form__addRep__submit" value="Save All"/>        
        </div>
      </form>  
  </fieldset> <!-- END SCHOOL REPS FIELDSET -->
  
  <?php $db->disconnect(); ?>

  <script>
    function confirmDropTeams() {
      if(confirm("This will delete all teams. This action can not be undone. To continue click 'OK'")) documents.forms.form__dropTeams.submit();
    }
    function displayResults() {
      window.location.href = "display_results.php";
    }
    function addDuplicateFields(caller) {
      var parent = caller;
      let formname;
      
      do {
        //find parent form first to get form name caller belongs to
        parent = parent.parentElement;
        if (parent !== null && parent.nodeName === 'FORM') {
          formname = parent.name;
          break;
        }
      } while (true);

      //search for data attributes labeled canDuplicate in above form and clone it (this assumes there is only 1 right now, or if multiple, the last one will be used)
      let boxes = document.querySelector(`form[name="${formname}"] [data-canDuplicate="true"]`);
      let clone = boxes.cloneNode(true);
      let input;

      clone.childNodes.forEach(element => {
        //just for user friendliness
        if (element.tagName === 'INPUT') {
          element.value = "";
          input = element;
        } 
      });

      //add it to parent form
      document.forms[formname].insertBefore(clone, caller.parentElement);

      //more user friendliness
      input.focus();
      caller.scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
    }
  </script>
</body>
</html>