<?php

  function date_in_range($minRange, $maxRange, $date) {
    if (gettype($minRange) !== 'string' || 
        gettype($date) !== 'string') {
          throw new InvalidArgumentException("Argument expecting string data type");
        }

    $start = strtotime($minRange);
    $end = strtotime($maxRange);
    $date = strtotime($date);
    
    if (is_null($maxRange)) return $date >= $start;    

    return ($date >= $start && $date <= $end);
  }

  function date_today_in_range($minRange, $maxRange) {
    $date = new DateTime('now', new DateTimeZone(CONFIG::TIMEZONE));
    return date_in_range($minRange, $maxRange, $date->format('Y-m-d H:i:s'));
  }

  function html_linebreak($qty = 1) {
    return str_repeat("<br>",$qty);
  }
  
  function html_nbsp($qty = 1) {
    return str_repeat("&nbsp;", $qty);
  }

  function reduce_sum($arr, $item) {
    return $arr += $item;
  }
  ####################################################################################################
  # THE FOLLOWING IS RECOMMENDED FOR DEBUG ONLY USES 
  ####################################################################################################

   /**
  * Prettifies an array to human readable parsing. This takes up a lot of vertical space when nested arrays are present, so use wisely.
  * This is not in-depth replacement of var_dump, only makes it easier to read. Only thing that is included with output is datatype and the key/value (KV) values.
  * The count of each nested array is included in brackets [<count>] next to the key value
  *
  * This works with Objects...for the most part
  *
  * NOTE: THIS IS FOR DEBUG PURPOSES ONLY, DO NOT USE THIS IN ANY PRODUCTION READY CODE FOR
  * MANY REASONS, BUT THE MOST OBVIOUS, debug_backtrace() AND PERFORMANCE
  *
  * You should only pass an array (first argument), the other 2 parameters are for recursion purposes only
  *
  * @param  [Array]  &$arr         Array to prettify, passed as reference. This is the only parameter that should be used outside the scope of this function
  * @param  integer $offset      [offset from left in number of spaces, this should not be used outside the scope of this function]
  * @param  boolean $isRecursive [identifies if the current call is a recursive iteration, this should not be used outside the scope of this function]
  * @return [String]               prettified string in HTML format
  */
  function array_prettify(&$arr, $offset = 2, $isRecursive = false) {
      if (!is_array($arr)) throw new \Exception("Error Processing Request. Expected array to be passed for first parameter");
      $bt = debug_backtrace();
      $_arr = array_shift($bt);
      $varname = "unknown_identifier"; $result = "";
      //get the name of the array passed
      foreach ($GLOBALS as $name => $val) {
        if ($val === $arr) {
          $varname = $name;
          break;
        }
      }
      //only output the variable name if it's the first run
      (!$isRecursive) ? $result .= "$" . $varname . " [" . count($arr) . "] => (" . gettype($arr) . ") | Caller => " . $_arr["file"] . " | Line# => " . $_arr["line"]  : "";
      $_cursor = 2 + $offset;
      $result .= html_linebreak() . html_nbsp($_cursor) . "[" . html_linebreak();
      foreach ($arr as $key => $value) {
        //check if value is another array, if so continue with recursion and offset based on current cursor point
        if (is_array($value)) {
          $keyIdentifier = $key . " [" . count($value) . "] => (" . gettype($value) . ")";
          $result .= html_nbsp($_cursor + 2) . $keyIdentifier . array_prettify($value, ceil((strlen($keyIdentifier) + $_cursor)/1.15), true);
        } elseif (is_object($value)) {
          $keyIdentifier = $key . " [" . @count($value) . "] => (" . gettype($value) . ")";
          $result .= html_nbsp($_cursor + 2) . $keyIdentifier . @array_prettify(json_decode(json_encode($value),true), ceil((strlen($keyIdentifier) + $_cursor)/1.15), true);
        } else {
          $result .= html_nbsp($_cursor + 2) . $key . " => (" . gettype($value) . ") " . $value;
          if ($value !== end($arr)) $result .= ",";
          $result .= html_linebreak();
        }
      }
      $result .= html_nbsp(2 + $offset) . "]" . html_linebreak();
      return $result;
  }
 /**
  * Console.logs the array as a traversable object.
  * @param  [array()] $arr array to log as object. This is not passed as a reference
  */
  function array_consoleify($arr) {
    if (!is_array($arr)) throw new \Exception("Error Processing Request. Expected array to be passed for first parameter", 1);
    return console_log(json_encode($arr));
  }
 /**
  * Console.logs to browser. This does not return
  * @param  Any $val value to be logged
  */
  function console_log($val) {
    echo "<script>console.log(" . $val . ");</script>";
  }
?>