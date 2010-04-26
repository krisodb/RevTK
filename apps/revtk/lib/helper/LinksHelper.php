<?php

/*
 * This file is part of the Reviewing the Kanji package.
 * Copyright (c) 2005-2010  Fabrice Denis
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Helpers to create links to application resources.
 * 
 * @package RevTK
 * @author  Fabrice Denis
 */

/**
 * Creates a link to a member's profile page.
 * 
 * @param  string    $username   Username
 * @param  array     $options    Optional attributes for the link, see link_to()
 */
function link_to_member($username, $options = array())
{
  $internal_uri = '@profile?username='.$username;
  return link_to($username, $internal_uri, $options);
}

/**
 * This helper creates a link to the Study area page for a given kanji.
 * 
 * @param string  $sKeyword   The label
 * @param mixed   $sKanjiId   Kanji id is a kanji character, framenum or keyword
 */
function link_to_keyword($sKeyword, $sKanjiId = '', $options = array())
{
  if ($sKanjiId === '') {
    $sKanjiId = $sKeyword;
  }
  
  return link_to($sKeyword, '@study_edit?id='.$sKanjiId, $options);
}

/**
 * Helper which creates a link to the community forum.
 * 
 * Arguments meanings are similar to link_to() but the url should be
 * a plain url, relative to the forum domain. The forum base url is
 * defined in settings.php
 *
 * @param string  $text     Link label, cf. link_to()
 * @param string  $rel_url  Url relative to base forum url, must begin with "/"
 *
 * @return string   Link tag
 */
function link_to_forum($text, $rel_url, $options = array())
{
  if (coreConfig::get('app_path_to_punbb') !== null)
    $url = coreConfig::get('app_forum_url') . $rel_url;
  else
    $url = "http://forum.koohii.com" . $rel_url;
  return link_to($text, $url, $options);
}

