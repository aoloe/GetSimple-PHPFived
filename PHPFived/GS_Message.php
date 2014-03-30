<?php
/**
 * Messaging system that collect messages and messages them to the user on the page
 * TODO: - register a callback to add the call to  render at the end of each page loading
 */

// in the CS css we only have notify updated and error as classes for notifications... should i adapt?
define('GS_MESSAGE_MESSAGE', 'message');
define('GS_MESSAGE_SUCCESS', 'success');
define('GS_MESSAGE_WARNING', 'warning');
define('GS_MESSAGE_ERROR', 'error');
class GS_Message {
    static protected $instance = null;

    protected $message = array();
    protected $success = array();
    protected $warning = array();
    protected $error = array();

    protected $render_box_css = array (
        GS_MESSAGE_MESSAGE => 'notify',
        GS_MESSAGE_SUCCESS => 'updated',
        GS_MESSAGE_WARNING => 'notify',
        GS_MESSAGE_ERROR => 'error',
    );

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new GS_Message();
        }
        return self::$instance;
    }

    public function GS_Message() {
    }

    public function add_message($message) {
        $this->message[] = $message;
    }

    public function add_success($success) {
        $this->success[] = $success;
    }

    public function add_warning($warning) {
        $this->warning[] = $warning;
    }

    public function add_error($error) {
        // TODO: make sure that the warning is stored in the log files is stored in the log files
        trigger_error($error); // add it as a warning
        GS_backtrace();
        GS_Debug('error', $error);
        $this->error[] = $error;
    }

    public function has_error() {
        return !empty($this->error);
    }

    public function get() {
        $result = array(
            GS_MESSAGE_MESSAGE => $this->message,
            GS_MESSAGE_SUCCESS => $this->success,
            GS_MESSAGE_WARNING => $this->warning,
            GS_MESSAGE_ERROR => $this->error,
        );
        return $result;
    }

    public function get_rendered() {
        $result = "";
        $message = $this->get();
        if (!empty($message)) {
            $result .= "<script type=\"text/javascript\">\n";
            $result .= "$(function() {\n";
            foreach ($message as $key => $value) {
                if (!empty($value)) {
                    // TODO: why is json_encode needed? why does it add a quote at the beginning and end?
                    $result .= "    $('div.bodycontent').before('<div class=\"".$this->render_box_css[$key]."\" style=\"display:block;\">".json_encode("<ul class=\"GS_list\">\n<li>".implode("</li>\n<li>", $value)."</li>\n</ul>\n")."</div>');\n";
                }
            } // foreach message
            $result .= "$(\".updated, .error\").fadeOut(500).fadeIn(500);\n";
            $result .= "});\n";
            $result .= "</script>\n";
        } // if message
        return $result;
    }
}
