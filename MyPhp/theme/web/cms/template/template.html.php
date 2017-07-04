<!DOCTYPE html>
<html>
    <head>
        <title><?= Core::getLib('template')->getTitle();?></title>
        <?= Core::getLib('template')->getHeader(); ?>
        <?php //Core::getBlock('core.template-header');?><!-- global header -->
        <?php Core::getLib('template')->getLayout('header'); ?> <!-- site header -->
    </head>
    <?  if (empty($_COOKIE['toggleMenu'])): $_COOKIE['toggleMenu'] = 1; endif;?>
    <body ng-app="cms-app" ng-controller="main-ctrl" <? if($_COOKIE['toggleMenu'] == 1 && Core::getLib('module')->getFullControllerName() != 'user.login'):?> class="js-pin-menu"<? endif;?>>
        <section id="root-panel"> 
            <?php Core::getBlock('core.header');?>
            <?php Core::getBlock('core.menu');?>
            <div id="main-panel" class="snap-content">
                <?php Core::getLib('module')->getControllerTemplate(); ?>
            </div>
            <?php Core::getLib('template')->getLayout('footer'); ?>
            <section class="panel-fixed none">
            </section>
        </section>
    </body>
</html>