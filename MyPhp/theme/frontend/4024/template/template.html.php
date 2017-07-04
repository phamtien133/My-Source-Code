<!DOCTYPE html>
<html class="no-js" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" xml:lang="en" lang="en">
<head>
    <title><?php Core::getLib('template')->getTitle(); ?></title>
    <?php Core::getBlock('core.header');?><!-- global header -->
    <?php Core::getLib('template')->getLayout('header'); ?> <!-- site header -->
</head>
<body>
<div class="container">
    <?php Core::getLib('module')->getControllerTemplate(); ?>
</div>
<?php Core::getLib('template')->getLayout('footer'); ?> <!-- site footer -->
<div class="group_global_js">
<?php Core::getLib('template')->getTemplate('core.block.jquery') ?>
<?php Core::getBlock('core.footer');?><!-- global footer -->
</div>
</body>