<?php  
  #admin table column names enum
  abstract class DB_TBLREF_ADMIN
  {
    const TBL_NAME = 'admin_settings';
    const ID = 'id';
    const VOTING_START_DATE = 'voting_start_date';
    const VOTING_END_DATE = 'voting_end_date';

    public static function isValidValue($value, $strict = false) {
      $constants = array(self::ID => self::ID, 
                          self::VOTING_START_DATE => self::VOTING_START_DATE, 
                          self::VOTING_END_DATE => self::VOTING_END_DATE);

      if ($strict) {
          return array_key_exists($value, $constants);
      }

      $keys = array_map('strtolower', array_keys($constants));
      return in_array(strtolower($value), $keys);
    }
  }

  #team table column names enum
  abstract class DB_TBLREF_TEAM
  {
    const TBL_NAME = 'team';
    const ID = 'id';
    const NAME = 'name';
    const DIVISION = 'education_division';

    public static function isValidValue($value, $strict = false) {
      $constants = array(self::ID => self::ID,
                          self::NAME => self::NAME,
                          self::EDUCATION_DIVISION => self::EDUCATION_DIVISION);

      if ($strict) {
          return array_key_exists($value, $constants);
      }

      $keys = array_map('strtolower', array_keys($constants));
      return in_array(strtolower($value), $keys);
    }
  }
  
  #companies table column names enum
  abstract class DB_TBLREF_COMPANY
  {
    const TBL_NAME = 'company';
    const ID = 'id';
    const NAME = 'name';
    const PIN = 'pin';

    public static function isValidValue($value, $strict = false) {
      $constants = array(self::ID => self::ID,
                          self::NAME => self::NAME,
                          self::PIN => self::PIN);

      if ($strict) {
          return array_key_exists($value, $constants);
      }

      $keys = array_map('strtolower', array_keys($constants));
      return in_array(strtolower($value), $keys);
    }
  }
  
  #teachers table column names enum
  abstract class DB_TBLREF_REP
  {
    const TBL_NAME = 'division_rep';
    const ID = 'id';
    const NAME = 'name';
    const DIVISION = 'education_division';
    const PIN = 'pin';

    public static function isValidValue($value, $strict = false) {
      $constants = array(self::ID => self::ID,
                          self::NAME => self::NAME,
                          self::DIVISION => self::DIVISION,
                          self::PIN => self::PIN);

      if ($strict) {
          return array_key_exists($value, $constants);
      }

      $keys = array_map('strtolower', array_keys($constants));
      return in_array(strtolower($value), $keys);
    }
  }
  
  #education divisions table column names enum
  abstract class DB_TBLREF_ED_DIVISIONS
  {
    const TBL_NAME = 'education_division';
    const ID = 'id';
    const DIVISION = 'division';

    public static function isValidValue($value, $strict = false) {
      $constants = array(self::ID => self::ID,
                          self::DIVISION => self::DIVISION);

      if ($strict) {
          return array_key_exists($value, $constants);
      }

      $keys = array_map('strtolower', array_keys($constants));
      return in_array(strtolower($value), $keys);
    }
  }
  
  #vote result table column names enum
  abstract class DB_TBLREF_VOTERESULT
  {
    const TBL_NAME = 'vote_result';
    const ID = 'voter_id';
    const TEAM = 'team_id';
    const RATING = 'rating';
    const JUDGETYPE = 'voter_type';

    public static function isValidValue($value, $strict = false) {
      $constants = array(self::ID => self::ID,
                          self::TEAM => self::TEAM,
                          self::RATING => self::RATING,
                          self::JUDGETYPE => self::JUDGETYPE);

      if ($strict) {
          return array_key_exists($value, $constants);
      }

      $keys = array_map('strtolower', array_keys($constants));
      return in_array(strtolower($value), $keys);
    }
  }

?>