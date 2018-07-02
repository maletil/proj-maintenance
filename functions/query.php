<?php
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 23:07
 */

function issetGetQuery($param, $alternative){
    if (isset($_GET[$param])){
        return $_GET[$param];
    } else {
        return $alternative;
    }
}
function notEmptyQueryExists($param){
    if (isset($_GET[$param]) && !empty($_GET[$param])){
        return $_GET[$param];
    } else {
        return false;
    }
}
function queryNotEmpty($param){
    if (!empty($_GET[$param])){
        return true;
    } else {
        return false;
    }
}