<?php

  require_once('globals.php');
  if (CONFIG::ISDEBUG) var_dump($_POST);
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    #check if page is being loaded from submitting a pin form
    if (isset($_POST['btn__submit__judgePin'])) {
      #validate pin and restart session if successful login
      $db->where('pin', $_POST['judgePin']);
      $tblname = DB_TBLREF_REP::TBL_NAME;
      if ($_SESSION['judgeType'] === DB_TBLREF_COMPANY::TBL_NAME) $tblname = DB_TBLREF_COMPANY::TBL_NAME;
      $result = $db->getOne($tblname);
      if ($result) {
        session_restart($_SESSION['judgeType'], $result['id']);
      }
      else {
        $alert_msg = '<p>Invalid PIN</p>
            <p><a href="index.php">Try Again</a></p>';
        include_once('php/error.inc.php');
      }
    }

    #check if page is being loaded from submitting the select judge type form
    if (isset($_POST['btn__submit__judgeType'])) {
      $_SESSION['judgeType'] = $_POST['btn__submit__judgeType'];

      if ($_SESSION['judgeType'] !== 'student') {
        #show PIN form for any user not a student
        echo '<form class="survey" action="index.php" method="POST" name="form__judgePin">
                <p>Enter your personal pin:</p>
                <input type="text" autocomplete="off" name="judgePin" autofocus required/>
                <button type="submit" name="btn__submit__judgePin">Submit</button>
              </form>';
      }
      else {
        session_restart($_SESSION['judgeType'], bin2hex(openssl_random_pseudo_bytes(11)));
      }
    }
  }
  else {
    #page is not being loaded with POST data so show pick judge type form
    echo '<form class="survey" action="index.php" method="POST" name="form__judgeType">
      <p>Select your judge status:</p>';     
        $user_types = array_keys($survey_json['user_type']);

        foreach ($user_types as $key => $value) {
          echo '<button type="submit" class="btn-plain" name="btn__submit__judgeType" value="'.$value.'">'.ucfirst($value).'</button>';
        }
    echo '</form>';
  }

  function session_restart($judgeType, $judgeID) {
      session_unset();
      @session_destroy();
      session_start();
      $_SESSION['createdAt'] = $_SERVER['REQUEST_TIME'];
      $_SESSION['judgeType'] = $judgeType;
      $_SESSION['judgeID'] = $judgeID;
      header('Location: index.php', true);
  }
?>

