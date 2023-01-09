<?php

  require_once("../php/globals.php");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    #on any drop button clicked
    if (isset($_POST["drop_tblname"])) {
      $tblname = $_POST['drop_tblname'];
      if ($db->delete($tblname)) {
        echo "Succesfully deleted all {$tblname}s";
      }
      else {
        echo "Error deleting {$tblname}s. Possible reason[s]:<br><br>{$db->getLastError()}";     
      }
    }

    #update voting settings
    if (isset($_POST["form__voting-settings__submit"])) {
      $data = array(
        DB_TBLREF_ADMIN::VOTING_START_DATE => $_POST['voting-start-date'] ?: null,
        DB_TBLREF_ADMIN::VOTING_END_DATE => $_POST['voting-end-date'] ?: null
      );
      
      if ($db->where("id", 1)->update(DB_TBLREF_ADMIN::TBL_NAME, $data)) {
        echo 'Successfully updated voting settings<br>';
      }
      else {
        echo 'Error updating db voting settings. Possible reason[s]:<br><br>' . $db->getLastError();
      }
    }

    #add teams
    if (isset($_POST['form__addTeam__submit'])) {
      $data = array();
      $keys = array(DB_TBLREF_TEAM::NAME, DB_TBLREF_TEAM::DIVISION);

      for ($i=0; $i < count($_POST['team_name']); $i++) { 
        if (!empty(trim($_POST['team_name'][$i]))) {
          array_push($data, array($_POST['team_name'][$i], $_POST['education_division'][$i]));
        }
      }

      insertMulti(DB_TBLREF_TEAM::TBL_NAME, $data, $keys);
    }
    
    #add companies
    if (isset($_POST['form__addCompany__submit'])) {
        $data = array();
        $keys = array(DB_TBLREF_COMPANY::NAME, DB_TBLREF_COMPANY::PIN);

        for ($i=0; $i < count($_POST['company_name']); $i++) { 
          if (!empty(trim($_POST['company_name'][$i]))) array_push($data, array($_POST['company_name'][$i], strtoupper(substr($_POST['company_name'][$i],0,2)).bin2hex(openssl_random_pseudo_bytes(2))));
        }

      insertMulti(DB_TBLREF_COMPANY::TBL_NAME, $data, $keys);
    }

    #add division rep/teacher/advisor
    if (isset($_POST['form__addRep__submit'])) {
      $data = array();
      $keys = array(DB_TBLREF_REP::NAME, DB_TBLREF_REP::DIVISION, DB_TBLREF_REP::PIN);

      for ($i=0; $i < count($_POST['rep_name']); $i++) { 
        if (!empty(trim($_POST['rep_name'][$i]))) {
          array_push($data, array($_POST['rep_name'][$i], $_POST['education_division'][$i], strtoupper(substr($_POST['rep_name'][$i],0,2)).bin2hex(openssl_random_pseudo_bytes(2))));
        }
      }

      insertMulti(DB_TBLREF_REP::TBL_NAME, $data, $keys);
    }
    
    $db->disconnect();
    $_POST = array();
    echo html_linebreak(2).'<a href="index.php">Go Back</a>';
  }


  function insertMulti($tbl, $data, $keys) {
    $db = $GLOBALS['db'];
    if (count($data) > 0) {
      $result = $db->insertMulti($tbl, $data, $keys);

      if ($result) {
        echo "Successfully added the following {$tbl}s:".html_linebreak(2);

        foreach ($data as $key => $value) {
          echo $value[0] . " " . $value[1] . html_linebreak();
        }
      }
      else {
        echo "Error adding {$tbl}s. Possible reason[s]:".html_linebreak(2)." {$db->getLastError()}";
      }
    }
    else {
      echo 'No data found to insert.';
    }
  }
?>