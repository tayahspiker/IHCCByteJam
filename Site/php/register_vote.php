<?php

  require_once('globals.php');

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo "ByteJam " . date('Y') . " Vote" ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo CONFIG::getRootDIR(true).'css/globals.css'; ?>" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo CONFIG::getRootDIR(true).'css/main.css'; ?>"/>
</head>
<body>
  <header>
    <?php echo date('Y') . " ByteJam"; ?>
  </header>

  <div class="container">
  <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['form__survey__submit'])) {
        #user got here without selecting a team somehow (back/refresh prevention)
        if (!isset($_POST['team_id'])) {
          $alert_msg = 'A team was not selected when filling out the survey.</p><p>Please <a href="/bytejam/index.php">try again</a></p>';
          include_once('php/error.inc.php');
        }

        #fist thing, check if user already voted for team (this is just a continuinty check to make sure user didn't refresh page and accidently produce duplicate vote)
        $db->where(DB_TBLREF_VOTERESULT::ID, $_SESSION['judgeID'])->where(DB_TBLREF_VOTERESULT::TEAM, $_POST['team_id']);
        $result = $db->getOne(DB_TBLREF_VOTERESULT::TBL_NAME);
        if ($result) {
          #already voted
          $alert_msg = '<p>Your vote for this team has already been processed.</p><p>To vote for a different team, click <a href="/bytejam/index.php">here</a></p>';
          include_once('php/error.inc.php');
        }
        else {
          #user has not voted for team yet
          $rating = array();
          #loop through all POSt values and find the criteria (this needs to be done because every vote could have different criteria)
          foreach ($_POST as $key => $value) {
            if (substr($key,0,9) === 'Criteria_') {
              $rating[substr($key,9)] = $value[0];
            }
          }
          #sanity check to ensure the number of criteria matches the json number of criteria for particular judgeType
          $criteria = $survey_json['user_type'][$_SESSION['judgeType']];

          if (count($rating) !== count($criteria)) {
            $alert_msg = '<p>Error processing vote. Not all criteria was voted on.</p><p>To try again, click <a href="/bytejam/index.php">here</a></p>';
            include_once('php/error.inc.php');
          }

          $data = array(
            DB_TBLREF_VOTERESULT::ID => $_SESSION['judgeID'],
            DB_TBLREF_VOTERESULT::TEAM => $_POST['team_id'],
            DB_TBLREF_VOTERESULT::RATING => json_encode($rating),
            DB_TBLREF_VOTERESULT::JUDGETYPE => $_SESSION['judgeType']
          );
          
          if (CONFIG::ISDEBUG) var_dump($data);

          if ($db->insert(DB_TBLREF_VOTERESULT::TBL_NAME, $data)) {
            $alert_msg = '<p>Your vote has processed!</p><p>To vote for another team, click <a href="/bytejam/index.php">here</a></p>';
            include_once('php/success.inc.php'); 
          }
          else {
            $alert_msg = '<p>Error processing vote.</p><p>To try again, click <a href="/bytejam/index.php">here</a></p>';
            if (CONFIG::ISDEBUG) echo $db->getLastError();
            include_once('php/error.inc.php'); 
          };
        }
      }
    }
  ?>
  </div>
</body>
</html>