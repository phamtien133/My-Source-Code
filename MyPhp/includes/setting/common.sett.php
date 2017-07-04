<?php
$_CONF['core.crop_seo_url'] = 75;
$_CONF['core.session_prefix'] = 'core_';
$_CONF['core.cookie_domain'] = '';
$_CONF['core.cookie_path'] = '/';
$_CONF['core.active_session'] = 15;
$_CONF['log.active_session'] = 15;
$_CONF['core.theme_session_prefix'] = 486256453;
$_CONF['core.auth_user_via_session'] = 0;
$_CONF['core.title_delim'] = '&raquo;';
$_CONF['core.default_time_zone_offset'] = 'z235';
$_CONF['core.setting_transport'] = 1;
$_CONF['core.setting_create_user_when_add_group'] = 1;
// app setting
$_CONF['app.api_key'] = 'AIzaSyDL0wVNwi0d0Z9lS-e0qUtyUwEqqo4ZdgM';
$_CONF['app.api_server_key'] = 'AIzaSyAgKZskUCZClyCzKBrJpc3mdjIolHMoO1c';
$_CONF['app.limit_accept_time'] = 300;
$_CONF['app.transport_fee'] = 10000;
$_CONF['app.threshold_time'] = 60*60; //second
$_CONF['app.threshold_distance'] = 1000; //met
$_CONF['support.enable_chat'] = 0;

$_CONF['wallet.bitcoin_exchange_rate'] = 1000;
$_CONF['wallet.invest_rate'] = 0.2;

$_CONF['core.login_domain'] = 'id.fi.ai';
// login Google - API key https://code.google.com/apis/console/?pli=1
$_CONF['core.login_google'] = array(
    'client_id' => '826057715930-0crlo7770bh433uqqa2dvsjta26tqo6e.apps.googleusercontent.com',
    'client_secret' => 'GZ9bMr10vKGYG0o2agE-lRMm',
    'redirect_uri' => 'http://'.$_CONF['core.login_domain'].'/tools/loginopenid.php'
);
// Facebook
$_CONF['core.login_facebook'] = array(
    'client_id' => '811697585539709',
    'client_secret' => 'd43a10a28e65805671e3d225bd0ab4eb',
    'redirect_uri' => 'http://'.$_CONF['core.login_domain'].'/tools/loginopenid.php'
);
// yahoo
$_CONF['core.login_yahoo'] = array(
    'client_id' => 'dj0yJmk9SE9CNUNnZ1lmcGJaJmQ9WVdrOWExWTVZek50TldFbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD1kMg--',
    'client_secret' => '47646c190e00251cb5cf69eacbbd1e69e4da72e4',
    'redirect_uri' => 'http://'.$_CONF['core.login_domain'].'/tools/loginopenid.php',
    'oauth_app_id' => 'kV9c3m5a'
);