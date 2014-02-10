<?php
/**
 * Storage system that replaces GS global variables
 * In the future we could have XML, yaml and json storage engines
 */

class GS_Storage {
    static protected $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new GS_Storage();
        }
        return self::$instance;
    }

    public function GS_Storage() {
    }

    /**
     * The write() method is copied from the GS one, but the error suppression has been removed
     * @param SimpleXMLExtended $data
     * @param string $filename
     */
    public function write($data, $filename) {
        $result = false;
        // TODO: checking that data is a SimpleXMLExtended?
        if (is_writable($filename)) {
            $result = $data->asXML($filename) === TRUE;
        } else {
            GS_Message::get_instance()->add_error(sprintf(i18n_r('PHPFived/ERROR_FILENOTWRITABLE'), $filename));
        }

        if (defined('GSCHMOD')) {
            $result = $result && chmod($filename, GSCHMOD);
        } else {
            // TODO: why should we want to do a chmod when none is defined?
            // $result = $result && chmod($filename, 0755);
        }
        return $result;
    } // GS_Storage::write()

    public function backup($filename) {
        // TODO: implement a backup function
    }

} // GS_Storage
