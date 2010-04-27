<?php

/*
 * This file is part of the Reviewing the Kanji package.
 * Copyright (c) 2005-2010  Fabrice Denis
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Quick solution to include proprietary or sensitive content for the production
 * build, such as PayPal, advertising, and so on. Some of these are viewable in
 * the public site html source, but are better left out of the Open Source
 * repository to avoid misuse of tracking ids, emails and so on.
 * 
 * If the asset to include is not found, a box displays a message instead.
 * 
 * Those assets use a file naming pattern excluded from commits (.gitignore)
 * 
 * TODO: find way to handle a "production site" branch, instead of run time mode.
 *
 * @param  $fromTemplate      Always pass in __FILE__ so that the current template folder is found
 * @param  $assetName
 * @param  $showPlaceHolder   Set to false to NOT display the placeholder on forked builds
 * 
 * @author  Fabrice Denis
 */
function get_local_content($fromTemplate, $assetName, $showPlaceHolder = true)
{
  ob_start();

  if (coreConfig::get('koohii_build'))
  {
    $assetFile = '__' . $assetName . 'View.php';
    $assetPath = dirname(realpath($fromTemplate));
    $file = $assetPath . DIRECTORY_SEPARATOR . $assetFile;

    if (file_exists($file))
    {
      // render as a partial so we have access to the default template variables
      $view = new coreView(coreContext::getInstance());
      $view->setTemplate($file);
      echo $view->render();
    }
    else
    {
    echo <<< EOD
<div style="background:red;color:yellow;padding:10px;">
koohii_build content NOT FOUND: <strong>${assetName}</strong>
</div>
EOD;
    }
  }
  else if ($showPlaceHolder)
  {
    // show a little box with the name of the local asset
    echo <<< EOD
<div style="border-radius:5px;-moz-border-radius:5px;border:none;background:#b2d2e3;color:#42697e;padding:10px;margin:0 0 1em;">
Public site content: <strong>${assetName}</strong>
</div>
EOD;
  }

  return ob_get_clean();
}
