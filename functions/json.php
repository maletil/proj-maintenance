<?php

function validateJson($input){
    if (json_encode($input) !== null){
        header('Content-Type: application/json');
        echo $input;
    } else {
        $requestError = array("valid" => false, "output" => $input);
        return json_encode($requestError);
    }
}