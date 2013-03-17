<?php
class PHPFived {
    static protected $plugin_id = '';
    public static function set_plugin_id($id) {self::$plugin_id = $id;}
    public static function get_plugin_id() {return self::$plugin_id;}
    static protected $plugin_info = array();
    public static function set_plugin_info(& $plugin_info) {self::$plugin_info = & $plugin_info;}

    public static function initialize() {
        self::load('PHP_future');
        self::load('GS');
        self::load('GS_UI');
        self::load('GS_Debug');
        self::load('GS_Message');
        self::load('GS_Entity');
        self::load('GS_Template');
    }

    public static function load($class) {
        if (!class_exists($class) && !defined(strtoupper($class))) {
            include(PHPFIVED_PLUGIN_PATH.$class.'.php');
            if (method_exists($class, 'initialize')) {
                $object = new $class();
                $object->initialize();
            }
        }
    }
} // PHPFived
