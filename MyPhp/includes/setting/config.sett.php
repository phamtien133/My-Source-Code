<?php

		// Report all errors except E_NOTICE
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT);
		ini_set('display_errors', 1);

$_CONF['db']['driver'] = 'mysqli';
$_CONF['db']['host'] = 'localhost';
$_CONF['db']['user'] = 'root';
$_CONF['db']['pass'] = '';
$_CONF['db']['name'] = 'db_empty';
//$_CONF['db']['name'] = 'cmsmarrybaby';
$_CONF['db']['prefix'] = 'eratown_';
$_CONF['db']['port'] = '';

$_CONF['db']['slave'] = false;
$_CONF['db']['slave_servers'] = array();

$_CONF['core.dir'] = $_SERVER["DOCUMENT_ROOT"];
if(substr($_SERVER["HTTP_HOST"], 0, 4) == 'sup.') $_CONF['core.main_server'] = 'sup.';
else $_CONF['core.main_server'] = '';

$_CONF['core.ads_server'] = 'ads.vi';
$_CONF['core.pay_server'] = 'account.';
$_CONF['core.versionExFile'] = '0.9.5';
$_CONF['core.local'] = 1;
$_CONF['core.phpexe'] = 'D:/server/bin/php/php.exe';

$_CONF['core.url_rewrite'] = '1';

$_CONF['core.salt'] = '4adec0e2d7eaa145c12fb173f0cb2db1';

$_CONF['core.cookie_path'] = '/';
//$_CONF['core.cookie_domain'] = str_replace('admin.', '', $_SERVER['HTTP_HOST']);
//session_set_cookie_params(0, '/', '.'.$_CONF['core.cookie_domain']);

$_CONF['core.cookie_domain'] = $_SERVER["SERVER_NAME"];//str_replace('www.', '', $_SERVER['HTTP_HOST']);
session_set_cookie_params(0, '/', '.'.$_CONF['core.cookie_domain']);
//session_set_cookie_params(0, '/', $_CONF['core.cookie_domain']);//'.'.$_CONF['core.cookie_domain']);
// Storage Engine (file, memcache)
$_CONF['core.cache_storage'] = 'memcache';

// Add salt
$_CONF['core.cache_add_salt'] = false;

// Cache suffix (file only)
$_CONF['core.cache_suffix'] = '.php';
$_CONF['core.cache_prefix'] = 'test_';

// Memcache Hosts
$_CONF['core.memcache_hosts'] = array(
    'loccalhost' => array(
        'host' => '127.0.0.1',
        'port' => '11211'
    )
);

// Memcahe persistent
$_CONF['core.memcache_persistent'] = false;

// Should we skip the cache check and display live content
$_CONF['core.cache_skip'] = false;

$_CONF['core.cache_template_file'] = false;

$_CONF['balancer']['enabled'] = true;
$_CONF['balancer']['servers'] = array();
