<?php
/**
 * Basic general services and information that GS offers to its plugins (and in the future to itself?
 */
class GS {
    protected static $plugin_enabled = array();
    public static function initialize() {
        global $live_plugins;
        self::$plugin_enabled = $live_plugins;
        // redefine the GS constants with a GS_ prefix for better readability
        define('GS_PLUGIN_PATH', GSPLUGINPATH);
        define('GS_DATA_OTHER_PATH', GSDATAOTHERPATH);
        define('GS_BACKUP_PATH', GSBACKUPSPATH);
        global $SITEURL;
        define('GS_SITE_URL', $SITEURL);
        global $GSEDITORLANG;
        define('GS_EDITOR_LANG', isset($GSEDITORLANG) && !empty($GSEDITORLANG) ? $GSEDITORLANG : 'en');
    }
    public static function load_plugin($plugin) {
        $filename = $plugin.'.php';
        if (array_key_exists($filename, self::$plugin_enabled) & self::$plugin_enabled[$filename]) {
            if (file_exists(GSPLUGINPATH.$filename)) {
                require_once(GSPLUGINPATH . $filename);
            }
        }
    }
}

