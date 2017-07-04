<!DOCTYPE html>
<html class="no-select">
<head>
    <title><?= Core::getLib('template')->getTitle();?></title>
    <?= Core::getLib('template')->getHeader(); ?>
    <?php Core::getBlock('core.template-header');?><!-- global header -->
    <?php Core::getLib('template')->getLayout('header'); ?> <!-- site header -->
</head>
<body>
    <div class="root-pnl is-mn"> 
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
