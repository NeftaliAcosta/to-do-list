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
    //ob_start();
    ob_get_contents();
    include "public/index.php";
    ob_end_flush();
