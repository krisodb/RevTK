<?php

/*
 * This file is part of the Reviewing the Kanji package.
 * Copyright (c) 2005-2010  Fabrice Denis
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Sets user password.
 *
 * Mind the escaping for the password in particular when using the shell, quote 
 * the password with single quotes, eg. --p 'foo!&bar%$'.
 *
 * For help:
 *   $ php batch/maintenance/setpassword.php
 *
 * For production maintenance:
 *   $ php batch/maintenance/setpassword.php --env prod --u '<username>' --p '<password>' --forum
 * 
 * WARNING!
 * - Does not validate against the website's password restrictions! (todo)
 *
 * @author  Fabrice Denis
 */

require_once('lib/batch/Command_CLI.php');

class CreateUser_CLI extends Command_CLI
{
  protected
    $cmdHelp = array(
      'short_desc' => 'Sets user password.',
      'usage_fmt'  => '--u "<username>" --p "<password>"',
      'options'    => array(
        'u'        => 'Username',
        'p'        => 'Password (use single quotes for special chars, i.e. --p \'foo&bar%$\')',
        'forum'    => 'Also set the password for the same user on the forum (optional)',
      )
    );


  public function init()
  {
    parent::init();
    
// Verify we're on the correct database
//print_r(coreConfig::get('database_connection'));exit;

    $connectionInfo = coreConfig::get('database_connection');
    $this->verbose("Using database: %s", $connectionInfo['database']);

    $username = trim($this->getOption('u'));
    $raw_password = trim($this->getOption('p'));

    if (empty($username) || empty($raw_password)) {
      $this->throwError('Username or password is empty.');
    }

    $userid = UsersPeer::getUserId($username);
    if (false === $userid) {
      $this->throwError('User named "%s" not found.', $username);
    }

    $this->verbose("Userid: %s", $userid);
    $this->verbose("Set password to: %s", $raw_password);

    $this->updateUser($userid, array('raw_password' => $raw_password));

    // only with linked PunBB forum
    if ($this->args->flag('forum'))
    {
      if (coreConfig::get('app_path_to_punbb') !== null)
      {
        PunBBUsersPeer::setPassword($username, $raw_password);
      }
      else
      {
        $this->throwError('Forum password: "app_path_to_punbb" is not defined in environment "%s"', CORE_ENVIRONMENT);
      }
    }

    $this->verbose('Success!');
  }

  /**
   * Updates any column data for given user id.
   *
   * 'raw_password' will be hashed as required and stored into 'password' column.
   * 
   * @param int   $userid
   * @param array $data 
   * @return boolean
   */
  private function updateUser($userid, $data)
  {
    if (isset($data['raw_password'])) {
      $data['password'] = coreContext::getInstance()->getUser()->getSaltyHashedPassword($data['raw_password']);
      unset($data['raw_password']);
    }

    if (false === UsersPeer::updateUser($userid, $data)) {
      $this->throwError('Could not update user id %s', $userid);
    }

    return true;
  }
}

$cmd = new CreateUser_CLI();
$cmd->init();
