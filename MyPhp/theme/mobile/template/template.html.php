<!DOCTYPE html>
<html class="no-select">
<head>
    <title><?= Core::getLib('template')->getTitle();?></title>
    <?= Core::getLib('template')->getHeader(); ?>
    <?php Core::getBlock('core.template-header');?><!-- global header -->
    <?php Core::getLib('template')->getLayout('header'); ?> <!-- site header -->
</head>
<body <? if(Core::getLib('module')->getFullControllerName() != 'core.index'): ?>class="small-page" <? endif;?>>
    <div class="root-pnl" data-call-back="scrollAllPage"> 
        <?php Core::getBlock('core.header');?>
        <?php //Core::getBlock('core.menu');?>
        <div id="container">
            <?php Core::getLib('module')->getControllerTemplate(); ?>
        </div>
        <footer>
            <?php Core::getLib('template')->getLayout('footer'); ?>
        </footer>
    </div>
</body>
</html>