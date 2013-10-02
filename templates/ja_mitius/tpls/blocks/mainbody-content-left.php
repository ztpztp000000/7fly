<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * 3列主题内容, 内容在左边, mast-col on top of 2 sidebars: content - sidebar1 - sidebar2
 */
defined('_JEXEC') or die;
?>

<?php

  // 布局结构
  $layout_config = json_decode ('{  
    "two_sidebars": {
      "default" : [ "span4"  , "span4"  , "span4" , "span8"],
      "wide"    : [],
      "xtablet" : [ "span12"         , "span12"             , "span12"               , "span12"           ],
      "tablet"  : [ "span12"        , "span12"  , "span2"               , "span12"           ]
    },
    "one_sidebar": {
      "default" : [ "span4"         , "span4"             , "span8"             ],
      "wide"    : [],
      "xtablet" : [ "span12"         , "span12"             , "span12"             ],
      "tablet"  : [ "span12"        , "span12 spanfirst"  , "span12"            ]
    },
    "no_sidebar": {
      "default" : [ "span12 no-sidebar" ]
    }
  }');

  // positions configuration
  $mastcol  = 'mast-col';
  $sidebar1 = 'position-7';
  $sidebar2 = 'position-5';

  // Detect layout
  if ($this->countModules("$sidebar1 and $sidebar2")) {
    $layout = "two_sidebars";
  } elseif ($this->countModules("$sidebar1 or $sidebar2")) {
    $layout = "one_sidebar";
  } else {
    $layout = "no_sidebar";
  }

  $layout = $layout_config->$layout;

  //
  $col = 0;
?>

<section id="ja-mainbody" class="ja-mainbody wrap">
  <div class="container">
    <div class="row">    
      <?php if ($this->countModules("$sidebar1 or $sidebar2 ")) : ?>
      <div class="ja-sidebar <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
        <?php if ($this->countModules("$sidebar1 or $sidebar2")) : ?>
        <div class="row">
          <?php if ($this->countModules($sidebar1)) : ?>
          <!-- 侧边栏 1 -->
          <div class="ja-sidebar ja-sidebar-1 <?php echo $this->getClass($layout, $col) ?><?php $this->_c($sidebar1)?>" <?php echo $this->getData ($layout, $col++) ?>>
             <div class="main-siderbar">
			 	<jdoc:include type="modules" name="<?php $this->_p($sidebar1) ?>" style="T3Xhtml" /> <!--最新开服游戏预留-->
			 </div>
          </div>
          <!-- //侧边栏 1 -->
          <?php endif ?>
          
          <?php if ($this->countModules($sidebar2)) : ?>
          <!-- 侧边栏 2预留开服以下位置 -->
          <div class="ja-sidebar ja-sidebar-2 <?php echo $this->getClass($layout, $col) ?><?php $this->_c($sidebar2)?>" <?php echo $this->getData ($layout, $col++) ?>>
             <div class="main-siderbar">
			 	<jdoc:include type="modules" name="<?php $this->_p($sidebar2) ?>" style="T3Xhtml" />
			 </div>
          </div>
          <!-- //侧边栏 2 -->
          <?php endif ?>
        </div>
        <?php endif ?>
      </div>
      <?php endif ?>
	  
	        <!-- 主题内容 -->
      <div id="ja-content" class="ja-content <?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
        <div class="main-content">
		  <jdoc:include type="message" />
		  <?php if ($this->countModules($mastcol)) : ?>
			<!-- COL块 1 -->
			<div class="ja-mastcol ja-mastcol-1<?php $this->_c($mastcol)?>">
			  <jdoc:include type="modules" name="<?php $this->_p($mastcol) ?>"/>
			</div>
			<!-- //COL块 1 -->
			<?php endif ?>
		  <jdoc:include type="component" />
		  <?php $this->loadBlock ('spotlight-2') ?>
		</div>
      </div>
      <!-- //MAIN CONTENT -->
    </div>
  </div>
</section> 