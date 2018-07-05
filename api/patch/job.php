<?php
/**
 * Created by mash.
 * Date: 23/04/18
 * Time: 0:26
 */

header('Content-Type: application/json');

if (isset($_GET["auth"])) {
    require('../../functions/connection.php');
    require('../../functions/strings.php');
    require('../../functions/query.php');
    require('../../functions/json.php');
    $path = "../../private/config.php";
    if (file_exists($path)) {
        $configs = require($path);
    } else {
        echo json_encode(array('error' => 'bad config file path'));
        die();
    }
    $auth = $_GET["auth"];

    if (isset($_GET["id"]) && !empty($_GET["id"])){ // Check if no blank id is given in query.
        $id = $_GET["id"];
    } else {
        echo error('No id provided.');
        die();
    }

    //$dni = issetGetQuery('dni', error('No dni provided'));
////// echo $updateSql;
    $checkSql = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '".$id."'", $auth), true); // Check if id already exists.

    if ($checkSql["entries"] != 0){ // Check if id exists.
        $dni = issetGetQuery('dni', $checkSql["output"][0]["DNI"]);
        $type = issetGetQuery('type', $checkSql["output"][0]["Type"]);

        $updateSql = "UPDATE maintenance SET `id`='". $id . "', `DNI`='". $dni . "', `Type`='". $type . "' WHERE `id` = '" . $id . "'";

        if (sqlPost($updateSql, $auth)){ // Check success in sqlPost.
            $checkSql = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '".$id."'", $auth), true); // Check updated row.
            echo json_encode(array('success' => true,'output' => $checkSql["output"]), JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo error("Id doesn't exist.");
    }
} else{
    echo json_encode(array('error' => 'invalid input params'));
}