<!DOCTYPE html>
<html>
    <head>
        <title><?= Core::getLib('template')->getTitle();?></title>
        <?= Core::getLib('template')->getHeader(); ?>
        <?php //Core::getBlock('core.template-header');?><!-- global header -->
        <?php Core::getLib('template')->getLayout('header'); ?> <!-- site header -->
    </head>
    <body ng-app="cms-app" ng-controller="main-ctrl" id="js_body_width_frame">
        <section id="root-panel"> 
            <?php Core::getLib('module')->getControllerTemplate(); ?>
            <?php Core::getLib('template')->getLayout('footer'); ?>
        </section>
    </body>
</html>