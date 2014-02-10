<?php

/**
 * display on the page a debug string.
 */
if (!function_exists('debug')) {
    function GS_debug($label, $value) {
        $bt = debug_backtrace();
        echo("<pre>".GS_get_backtrace_render($bt[0], count($bt) > 1 ? $bt[1] : null)."\n$label:\n");
            if (is_null($value)) {
                echo("NULL");
            } elseif (is_bool($value)) {
                echo($value ? "true" : "false");
            } else {
                echo(htmlspecialchars(print_r($value, 1)));
            }
        echo("</pre>\n");
    }
}

/**
 * Display on the page the backtrace for the current point in the code.
 */
if (!function_exists('GS_backtrace')) {
    function GS_get_backtrace_render($item, $previous = null) {
        return
            '<span style="background-color:lightgray;" title="'.(array_key_exists('file', $item) ? $item['file'] : '').'">'.
        (array_key_exists('file', $item) ? '['.$item['line'].'] '.basename($item['file']).'::' : '').(isset($previous) ? $previous['function'] : $item['function']).'()</span>';
    }
    function GS_backtrace() {
        $bt = array_reverse(debug_backtrace());
        $render = array();
        $previous = null;
        foreach ($bt as $item) {
            if (isset($previous)) {
                echo "<pre>".GS_get_backtrace_render($item)."</pre>\n";
            }
            $previous = $item;
        }
    }
}

