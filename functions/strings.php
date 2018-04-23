<?php
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 19:44
 */

function randomStr($chars){
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data),0,$chars);
}
function error($msg){ // Show errors
    return json_encode(array('success' => false, 'error' => $msg), JSON_UNESCAPED_UNICODE);
}