<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 4/2/14
 * Time: 10:27 AM
 */

if(!function_exists('boolval')) {
    function boolval($x) {
        return $x ? true : false;
    }
}

function arrayval($x) {
    return is_array($x) ? $x : array();
}