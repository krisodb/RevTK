<?php use_helper('Form','Validation','Links') ?>

<div class="layout-home">

<?php include_partial('home/homeSide') ?>

  <div class="col-main">
    <div class="col-box col-box-top content">

      <div class="app-header">
        <h2><?php echo link_to('Home', 'home/index') ?> <span>&raquo;</span> Labs</h2>
        <div class="clearboth"></div>
      </div>
      
<?php if (0 && CORE_ENVIRONMENT !== 'prod'): ?>
  <div style="-moz-border-radius:10px;padding:10px;background:#0077d4;color:#fff;font-weight:bold;margin:0 0 1em;border-radius:10px;">
    Please note this feature is currently on the TEST website! Sorry for the confusion,
    make sure to go back to <a href="http://kanji.koohii.com" style="color:#fc4">http://kanji.koohii.com</a> to use
    the <em>real</em> website. This is a development feature.
  </div>
<?php endif; ?>

  <div style="-moz-border-radius:5px;padding:10px;background:#0077D4;color:#fff;margin:0 0 1em;border-radius:5px;">
      <p> <strong>Welcome to the Reviewing the Kanji Labs!</strong></p>
      
      <p> Here I will try out some ideas that may someday become permanent features of the website.
          You are welcome to share ideas and give suggestions for this page, please <?php echo link_to_forum('use this topic', '/viewtopic.php?pid=97741', array('style' => 'color:yellow;')) ?>, thank you!
      </p>
  </div>
      
      <h3>iVocab Shuffle™</h3>

      <p> This simple flashcard review mode is inspired by Apple's iPod Shuffle.
          Click the Start button and then just press the SPACE key to flip cards.
      </p>

      <p>
        <?php echo link_to('<span>Start iVocab Shuffle™!</span>', 'labs/review', array('class' => 'uiIBtn uiIBtnDefault', 'query_string' => http_build_query(array('type' => 'untested')) )) ?>
      </p>

      <p> To make the words list only include kanji you have studied thus far,
          type the type in the number of the farthest kanji you have learned.        
      </p>

      <p>
<?php echo form_tag('labs/review', array('class' => 'main-form', 'method' => 'get')) ?>
 <?php echo form_errors() ?>
<?php echo input_tag('max_level', '0', array('class' => 'textfield', 'style' => 'width:80px')) ?>&nbsp;&nbsp;<?php echo submit_tag('Start') ?>


      </p>

      <p> Features:</p>
      <ul>
        <li><strong>Kanji Keywords</strong>: hover on the kanji with the mouse pointer to see a
        tooltip with the <em>Remembering the Kanji</em> keyword.</li>
        <li><strong>Study links</strong>: click any kanji to go to the corresponding
        Study page (hint: use Shift-click or Middle mouse button to open a new tab).</li>
        <li><strong>Discover new words</strong>: each new test will display a random
            selection of the "priority" entries defined in Jim Breen's
            Japanese/English dictionary (JMDICT).</li>
      </ul>

    </div>
  </div>

</div>
