<?php

    #config enum
    #all functions reference these config settings
    abstract class CONFIG {
      const ISDEBUG = false; #this is strictly for local host purposes. If you want to have debug on for say var_dumps on a live server, then please change the getRootDIR foldername accordingly
      #locale settings
      const TIMEZONE = 'America/Chicago';

      public static function getRootDIR($use_relative_path = false) {   
        $foldername = 'bytejam';
        if (self::ISDEBUG || $use_relative_path === true) return "/{$foldername}/";   
        return $_SERVER['DOCUMENT_ROOT'] . "/{$foldername}/";
      }
      
      public static function getDBInfo($val){
        if ($val === 'host') return (self::ISDEBUG) ?  'localhost' : 'localhost';
        if ($val === 'username') return (self::ISDEBUG) ?  'root' : 'ByteJamAdmin';
        if ($val === 'password') return (self::ISDEBUG) ?  '' : 'ByteJamIHCC';
        if ($val === 'database') return (self::ISDEBUG) ?  'bytejam' : 'ByteJam';
      }

      public static function isValidValue($value, $strict = false) {
        $constants = array(self::TIMEZONE => self::TIMEZONE);

        if ($strict) {
            return array_key_exists($value, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($value), $keys);
      }
    }
?>