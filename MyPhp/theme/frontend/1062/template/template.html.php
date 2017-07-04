<!DOCTYPE html>
<html class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" xml:lang="en" lang="en">
<head>
    <title><?= Core::getLib('template')->getTitle();?></title>
    <?php Core::getBlock('core.template-header');?><!-- global header -->
    <?php Core::getLib('template')->getLayout('header'); ?> <!-- site header -->
</head>
<body>
    <div id="wrap">
        <div id="header">
            <?php Core::getBlock('core.header');?>
            <div class="lnb">
                <?php Core::getBlock('core.search');?>
                <?php Core::getBlock('slide.top');?>
                <?php Core::getBlock('core.menu');?>
            </div>
        </div>
        <?php Core::getLib('template')->getTemplate('core.block.rating'); ?>
        <div class="container">
            <div class="main_content">
                <?php Core::getLib('module')->getControllerTemplate(); ?>
                <div class="chinh_sua" id="cot_5"><?= $this->_aVars['aSettings']['column_5']?></div>
            </div>
        </div>
    </div>
    <?php Core::getLib('template')->getLayout('footer'); ?> <!-- site footer -->
    <div class="group_global_js">
    <?php Core::getLib('template')->getTemplate('core.block.jquery') ?>
    <?php Core::getBlock('core.template-footer');?><!-- global footer -->
    </div>
</body>