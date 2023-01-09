<?php

  require_once('php/globals.php');
  if (CONFIG::ISDEBUG) {
    echo '$_SESSION: '; 
    @var_dump($_SESSION);
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo "ByteJam " . date('Y') . " Score" ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo CONFIG::getRootDIR(true).'css/globals.css'; ?>" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo CONFIG::getRootDIR(true).'css/main.css'; ?>" />
  <link rel="stylesheet" type="text/css" media="screen" href="<?php echo CONFIG::getRootDIR(true).'css/surveyradio.css'; ?>" />
</head>
<body>
  <header>
    <?php 
      echo date('Y') . ' ByteJam'; 

      if (CONFIG::ISDEBUG) {
        if (isset($_GET['destroy']) && $_GET['destroy']==1) {
          session_unset();
          @session_destroy();
        }        
        echo '<a href="?destroy=1" style="color: white; font-size: 13px">destroy session</a>';
      }
    ?>    
  </header>
  
  <div class="container">
    <?php
      $settings = $db->getOne(DB_TBLREF_ADMIN::TBL_NAME);
      $startDate = $settings[DB_TBLREF_ADMIN::VOTING_START_DATE];
      #first check if voting is open based on start time (and expiry date if given).
      if ((!is_null($startDate) && 
          date_today_in_range($startDate, $settings[DB_TBLREF_ADMIN::VOTING_END_DATE]))) {
        #voting is open -> check session token for 'logged in user' (session is set for 2 days as noted below)
        if ((!isset($_SESSION['createdAt']) ||
            ($_SERVER['REQUEST_TIME'] - $_SESSION['createdAt']) > (60 * 60 * 24 * 2))) {
              #display login HTML markup if the session is out of range, or there is no current session, or there is no judgeID (user's id) (essentially not 'logged in')
              include_once('php/login.inc.php');
        }
        else 
        {
          #voting is open and the user is already 'logged in', update session token to prevent garbage collection and show survey
          $_SESSION['createdAt'] = time();
          include_once('php/survey.inc.php');
        }
      }
      else {
        #voting is closed
        $alert_msg = '<p>Voting is currently closed.</p>';

        if (!is_null($startDate)) {
          $time = date_format(date_create($startDate), 'h:ia');
          $date = date_format(date_create($startDate), 'M d Y');
          $alert_msg .= "<p>You can start voting at {$time} on {$date}</p>"; 
        }           

        include_once('php/error.inc.php');
      }
    ?>
  </div>
</body>
</html>