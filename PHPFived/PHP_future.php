<?php
/**
 * str_putcsv() already exists, but not str_putcsv()... so here is "my" implementation of it.
 */
if (!function_exists('str_putcsv')) {
    function str_putcsv($input, $delimiter = ',', $enclosure = '"') {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        // debug('input', $input);
        $output = array();
        foreach ($input as $field) {
            // debug('field', $field);
            if (preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field)) {
                $output[] = $enclosure.str_replace($enclosure, $enclosure.$enclosure, $field).$enclosure;
            } else {
                $output[] = $field;
            }
        }
        return implode($delimiter, $output);
    }
}

/**
 * unreal__FILE__ returns __FILE__, as it is seen by your webserver. and does not resolve symbolik links.
 * unreal__FILE__ already exists in my admin/inc/common.php file, because i need it for defining
 * GSROOTPATH, which is used for reading the GS configuration file.
 */
if (!function_exists('unreal__FILE__')) {
function unreal__FILE__() {
    $result = __FILE__;
    $f = $_SERVER['SCRIPT_FILENAME'];
    $rf = realpath($_SERVER['SCRIPT_FILENAME']);
    if ($f != $rf) {
        $ff = explode(DIRECTORY_SEPARATOR, $f);
        $rff = explode(DIRECTORY_SEPARATOR, $rf);
        while (!empty($ff) && !empty($rff)) {
            $fi = array_pop($ff);
            $rfi = array_pop($rff);
            if ($fi != $rfi) {
                $ff[] = $fi;
                $rff[] = $rfi;
                break;
            }
        }
        $result = implode(DIRECTORY_SEPARATOR, $ff).substr(__FILE__, strlen(implode(DIRECTORY_SEPARATOR, $rff)));
    }
    return $result;
}
}
