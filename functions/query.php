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