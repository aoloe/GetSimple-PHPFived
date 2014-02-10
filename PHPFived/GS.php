<?php
/**
 * Basic general services and information that GS offers to its plugins (and in the future to itself?
 */
class GS {
    protected static $plugin_enabled = array();
    protected static $plugin_loaded = array();
    public static function initialize() {
        global $live_plugins;
        self::$plugin_enabled = $live_plugins;
        // redefine the GS constants with a GS_ prefix for better readability
        define('GS_PLUGIN_PATH', GSPLUGINPATH);
        define('GS_DATA_OTHER_PATH', GSDATAOTHERPATH);
        define('GS_BACKUP_PATH', GSBACKUPSPATH);
        global $SITEURL;
        define('GS_SITE_URL', $SITEURL);
        define('GS_TEMPLATE_URL', GS_SITE_URL.'admin/template/');
        define('GS_JAVASCRIP_URL', GS_TEMPLATE_URL.'js/');
        global $GSEDITORLANG;
        define('GS_EDITOR_LANG', isset($GSEDITORLANG) && !empty($GSEDITORLANG) ? $GSEDITORLANG : 'en');
    }
    /**
     * Just a forward to the GS global function for now
     * @param array $plugin
     */
    public static function register_plugin($plugin) {
        register_plugin(
            $plugin['id'],
            $plugin['name'],
            $plugin['version'],
            $plugin['author'],
            $plugin['url'],
            $plugin['description'],
            $plugin['page_type'], // on which admin tab to display; may be overwritten by the plugin
            $plugin['main_function'] //main function (administration)
        );
    }

    /**
     * @param string $plugin name
     * TODO: find a way to ensure that at least the given version is present
     */
    public static function load_plugin($plugin) {
        $filename = $plugin.'.php';
        if (!in_array($plugin, self::$plugin_loaded) && array_key_exists($filename, self::$plugin_enabled) && self::$plugin_enabled[$filename]) {
            if (file_exists(GSPLUGINPATH.$filename)) {
                include_once(GSPLUGINPATH . $filename);
                self::$plugin_loaded[] = $plugin;
                if (class_exists($plugin) && method_exists($plugin, 'initialize')) {
                    $plugin_instance = new $plugin;
                    $plugin_instance->initialize();
                }
            }
        }
    }
}

