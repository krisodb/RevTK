<?php

/*
 * This file is part of the Reviewing the Kanji package.
 * Copyright (c) 2005-2010  Fabrice Denis
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Build the levels table (to be used by iVocabShuffle)
 * 
 * Usage:
 *   
 *   $ php data/scripts/makeLevelsTable.php
 *
 * @author  Nikita Kitaev
 */

require_once('lib/batch/Command_CLI.php');

class MakeLevelsTable_CLI extends Command_CLI
{
  protected
    $cmdHelp = array(
      'short_desc' => 'Build the levels table (to be used by iVocabShuffle)',
      'usage_fmt'  => '--v',
      'options'    => array(
      ) 
    );

  public function init()
  {
    parent::init();
    
// Verify we're on the correct database
//print_r(coreConfig::get('database_connection'));exit;
    
    $connectionInfo = coreConfig::get('database_connection');
    $this->verbose("Using database: %s", $connectionInfo['database']);

    $db = coreContext::getInstance()->getDatabase();
    
    $Q = "
CREATE TABLE dictlevels
(
SELECT jdict.dictid, max(framenum)
FROM jdict
JOIN dictsplit ON jdict.dictid = dictsplit.dictid
JOIN v_kanjipronstats ON dictsplit.kanji = v_kanjipronstats.kanji
JOIN kanjis ON v_kanjipronstats.kanjichar = kanjis.kanji
GROUP BY jdict.dictid
)
";
    $db->query($Q);


    $Q = "
ALTER TABLE `dictlevels` CHANGE `max(framenum)` `framenum` INT( 11 ) NULL DEFAULT NULL  
";
    $db->query($Q);

    $Q = "
ALTER TABLE `dictlevels` ADD PRIMARY KEY ( `dictid` ) 
";
    $db->query($Q);

    $this->verbose('Success!');
  }
}

$cmd = new MakeLevelsTable_CLI();
$cmd->init();

