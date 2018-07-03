<?php
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 11:59
 */

header('Content-Type: application/json');

if (isset($_GET["auth"]) && isset($_GET["id"])) {
    require ('../../functions/connection.php');
    require ('../../functions/json.php');
    $path = "../../private/config.php";
    if (file_exists($path)) {
        $configs = require($path);
    } else {
        echo json_encode(array('error' => 'bad config file path'));
        die();
    }
    $auth = $_GET["auth"];

    if (!empty($_GET["id"])){ // Check if no blank id is given in query
        $id = $_GET["id"];
        $sql = "SELECT * FROM maintenance WHERE id = '".$id."'";
    } else { // If not, SELECT all
        $sql = "SELECT * FROM maintenance";
    }

    $output = sqlGet($sql, $auth);
    validateJson($output);
} else {
    echo json_encode(array('error' => 'invalid input params'));
}
