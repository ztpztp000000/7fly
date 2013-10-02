<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
//var_dump($this->game_info);exit;
?>



<form action="<?php echo JURI::base(); ?>index.php?option=com_gamesite&task=save" method="post" enctype="multipart/form-data">
    <table width="100%" cellspacing="5" cellpadding="0">
    <tr>
    <td width="20%" align="right">游戏介绍</td>
    <td><textarea name="game_depict" cols="80" rows="5" class="textarea"><?php echo $this->game_info->depict ?></textarea></td>
    </tr>
    
    <tr>
    <td width="20%" align="right">背景图片:</td>
    <td><input type="file" id="file-upload" name="bk_img" />只支持 .jpg .jpeg .png
    <?php if (!empty($this->game_info->bk_img)) : ?>
        <img src="<?php echo $this->game_info->bk_img ?>" alt="" style="width:100px; height:100px;" />
    <?php endif; ?></td>
    </tr>
    
    <tr>
    <td width="20%"  align="right">背景颜色</td>
    <td><input type="text" name="bk_color" size="30" value="<?php echo $this->game_info->bk_color ?>" class="input"/></td>
    </tr>
    
    <tr>
    <td width="20%" align="right">推广页面图片:</td>
    <td><input type="file" name="spread_bk" />只支持 .jpg .jpeg .png
    <?php if (!empty($this->game_info->spread_bk)) : ?>
        <img src="<?php echo $this->game_info->spread_bk ?>" alt="" style="width:100px; height:100px;" />
    <?php endif; ?></td>
    </tr>
    
    <tr>
    <td><input type="submit" id="file-upload-submit" value="<?php echo JText::_('提交'); ?>"/></td>
    </tr>
    </table>
    
    
    
    <input type="hidden" name="game_no" value="<?php echo $this->game_info->no ?>" />
    <input type="hidden" name="game_id" value="<?php echo $this->game_info->guid ?>" />
</form>
