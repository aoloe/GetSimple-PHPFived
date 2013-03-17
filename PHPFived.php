<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');

/*
Plugin Name: PHPFived
Description: Adds classes and constants that are more compatible with the way PHP 5 works.
Version: 0.1
Author: Ale Rimoldi
Author URI: ideale.ch
*/


# get correct id for plugin
$phpfived_plugin_id = basename(__FILE__, ".php");
$current_script_id = basename($_SERVER['SCRIPT_NAME'], ".php");

# register plugin
register_plugin(
  $phpfived_plugin_id, //Plugin id
  'PHPFived',  //Plugin name
  '0.1',    //Plugin version
  'Ale Rimoldi',  //Plugin author
  'http://www.ideale.ch/', //author website
  'Adds classes and constants that are more compatible with the way PHP 5 works.', //Plugin description
  'pages', //page type - on which admin tab to display;
  'PHPFived_routing'  //main function (administration)
);

// TODO: how to make sure that this is loaded before the scripts that need it?

/**
 * Define the plugin's wide settings. You may adapt them to match your GS install.
 */
define('PHPFIVED_PLUGIN_PATH', GSPLUGINPATH.$phpfived_plugin_id.'/');
define('PHPFIVED', true);

if (!is_frontend()) {
    i18n_merge($phpfived_plugin_id, substr($LANG,0,2)); 
}

if (!defined('SITEURL')) {define('SITEURL', $SITEURL);}
if (!defined('GSEDITORLANG')) {define('GSEDITORLANG', isset($GSEDITORLANG) && !empty($GSEDITORLANG) ? $GSEDITORLANG : 'en');}

include(PHPFIVED_PLUGIN_PATH.'/PHPFived.php');

PHPFived::set_plugin_id($phpfived_plugin_id);
PHPFived::set_plugin_info($plugin_info[$phpfived_plugin_id]);

PHPFived::initialize();

function PHPFived_routing(){
    PHPFived_routing::routing();
} 

