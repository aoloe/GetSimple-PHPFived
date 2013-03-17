<?php

/**
 * display on the page a debug string.
 */
if (!function_exists('debug')) {
    function debug($label, $value) {
        $bt = debug_backtrace();
        echo(
            "<pre><span style=\"background-color:lightgray;\" title=\"{$bt[0]['file']}\">[{$bt[0]['line']}] ".
            basename($bt[0]['file']).
            "::{$bt[count($bt) > 1 ? 1 : 0]['function']}()</span>\n$label:\n".
            htmlspecialchars(print_r($value, 1)).
            "</pre>\n"
        );
    }
}

/**
 * Display on the page the backtrace for the current point in the code.
 */
if (!function_exists('backtrace')) {
function backtrace() {echo ("<pre>".print_r(debug_backtrace(), 1)."</pre>");}
}

