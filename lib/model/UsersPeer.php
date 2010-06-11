<?php
/**
 * Users.
 * 
 * @package RevTK
 * @author  Fabrice Denis
 */

class UsersPeer extends coreDatabaseTable
{
  // shortcut for ::getInstance()->getName()
  const
    TABLE = 'users';

  protected
    $tableName = 'users',
    $columns = array
    (
      'userid',
      'username',
      'password',
      'userlevel',
      'joindate',
      'lastlogin',
      'email',
      'location',
      'timezone'
    );

  /**
   * Credential values as stored in `userlevel`.
   */
  const USERLEVEL_ADMIN = 9;
  const USERLEVEL_USER  = 1;

  /**
   * Get this peer instance to access the base methods.
   * This function must be copied in each peer class.
   */
  public static function getInstance()
  {
    return coreDatabaseTable::_getInstance(__CLASS__);
  }

  /**
   * Find one user by given criteria and return all data for it.
   * 
   * Returns dates as unix timestamps (can be formatted by php)
   * with the prefix "ts_"
   * 
   * @param  string  $criteria    Column to search
   * @param  mixed   $value       Value to match
   * @return array   User array record, or false
   */
  private static function getUserBy($criteria, $value)
  {
    $select = self::getInstance()->select(array(
      '*',
      'ts_joindate' => 'UNIX_TIMESTAMP(joindate)',
      'ts_lastlogin' => 'UNIX_TIMESTAMP(lastlogin)'));

    $select->where($criteria . ' = ?', $value)
           ->query();
           
    $user = self::$db->fetch();

    return $user;
  }
  
  /**
   * Get user by unique name.
   * 
   * @param string  $username
   */
  public static function getUser($username)
  {
    return self::getUserBy('username', $username);
  }
  
  /**
   * Get user by unique id.
   * 
   * @param mixed  $userid    Should be numeric, but string is ok
   */
  public static function getUserById($userid)
  {
    return self::getUserBy('userid', $userid);
  }

  /**
   * Get user by email address.
   * 
   * @param string  $email
   */
  public static function getUserByEmail($email)
  {
    return self::getUserBy('email', $email);
  }
  
  /**
   * Get user id for name
   *
   * @return int  User id or false
   */
  public static function getUserId($username)
  {
    $select = self::getInstance()->select('userid')->where('username = ?', $username)->query();
    if ($row = self::$db->fetch())
    {
      return (int) $row['userid'];
    }
    return false;
  }

  /**
   * Lastlogin setter.
   * 
   * Sets lastlogin time to NOW() by default.
   *
   * @param int  $userid
   */
  public static function setLastlogin($userid, $timestamp = null)
  {
    return self::updateUser($userid, array('lastlogin' => $timestamp===null ? new coreDbExpr('NOW()') : $timestamp));
  }

  /**
   * Checks if username is registered.
   *
   * @return boolean True if username is already registered.
   */
  public static function usernameExists($username)
  {
    return (self::getInstance()->count('username = ?', $username) > 0);
  }

  /**
   * Insert a new user record.
   * 
   * The raw password can be any length, since the result will be a fixed length hash.
   * 
   * Required information:
   *   username
   *   raw_password
   *   email
   *   location
   * 
   * Optional:
   *   userlevel
   *   
   * @param array $userinfo  Assoc.array of form registration data
   */
  public static function createUser(array $userinfo)
  {
    $user = coreContext::getInstance()->getUser();
    $hashed_password = $user->getSaltyHashedPassword($userinfo['raw_password']);

    $userdata = array(
      'username'      => $userinfo['username'],
      'password'      => $hashed_password,
      'email'         => $userinfo['email'],
      'location'      => $userinfo['location'],
      'joindate'      => new coreDbExpr('NOW()')
    );

    // may be explicitly set by maintenance tools
    if (isset($userinfo['userlevel'])) {
      $userdata['userlevel'] = $userinfo['userlevel'];
    }

    return self::getInstance()->insert($userdata);
  }

  /**
   * Update columns in user record.
   * 
   * Data must be trimmed and validated!
   *
   * 'raw_password' will be hashed into 'password'.
   * 
   * @param  int   $userid
   * @param  array $columns  Column data
   * 
   * @return boolean
   */
  public static function updateUser($userid, $columns)
  {
    if (isset($columns['raw_password']))
    {
      // hash password for database
      $user = coreContext::getInstance()->getUser();
      $columns['password'] = $user->getSaltyHashedPassword($columns['raw_password']);
      unset($columns['raw_password']);
    }

    return self::getInstance()->update($columns, 'userid = ?', $userid);
  }
}
