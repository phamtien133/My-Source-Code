<?php
$_CONF['db']['driver'] = 'mysql';
$_CONF['db']['host'] = 'localhost'; 
$_CONF['db']['user'] = 'root';
$_CONF['db']['pass'] = '';
$_CONF['db']['name'] = 'cmsnew';
$_CONF['db']['prefix'] = 'eratown_';
$_CONF['db']['port'] = '';

$_CONF['db']['slave'] = false;
$_CONF['db']['slave_servers'] = array();

$_CONF['core.dir'] = $_SERVER["DOCUMENT_ROOT"];
$_CONF['core.main_server'] = 'admin.';
$_CONF['core.ads_server'] = 'ads.vi';
$_CONF['core.versionExFile'] = '0.9.5';
$_CONF['core.local'] = 1;
$_CONF['core.phpexe'] = 'D:/server/bin/php/php.exe';

$_CONF['core.url_rewrite'] = '1';

$_CONF['core.salt'] = '4adec0e2d7eaa145c12fb173f0cb2db1';

$_CONF['core.cookie_path'] = '';
$_CONF['core.cookie_domain'] = '';

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
