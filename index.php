<?php
function sanitizeOutput($buffer) {
    $search = array(
        '/\>[^\S ]+/s',
        '/[^\S ]+\</s',
        '/(\s)+/s',
        '/<!--(.|\s)*?-->/'
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $output = preg_replace($search, $replace, $buffer);
    return $output;
}

ob_start("sanitizeOutput");
include "public/index.php";

if (ob_get_level() > 0) {
    ob_end_flush();
}
