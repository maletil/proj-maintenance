<?php

function validateJson($input){
    if (json_encode($input) !== null){
        echo $input;
    } else {
        $requestError = array("valid" => false, "output" => $input);
        return json_encode($requestError);
    }
}