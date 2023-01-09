<?php 

  # note, you should be able to do a majority of the site wide configurations and settings in config.php and db_tblref.php and all other scripts will execute fine
  # however, user end or client side elements may need to be updated to reflect those changes
  require_once('config.php');

  if (CONFIG::ISDEBUG) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
  }
  else {
    ini_set('display_errors', '0');
    error_reporting(0);
  }

  require_once('functions.inc.php');
  require_once('MysqliDb.php');
  require_once('db_tblref.php');
  
  session_start();
  set_include_path(dirname(__DIR__).'/');

  $db = new MysqliDb(CONFIG::getDBInfo('host'), CONFIG::getDBInfo('username'), CONFIG::getDBInfo('password'), CONFIG::getDBInfo('database'));
    
  $input = file_get_contents('json/survey_criteria.json', true); #make sure to use include_path argument
  $survey_json = json_decode($input, true); 
  unset($input);
?>