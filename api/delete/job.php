<?php
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 20:02
 */

header('Content-Type: application/json');

if (isset($_GET["auth"]) && isset($_GET["id"])) {
    require('../../functions/connection.php');
    require('../../functions/strings.php');
    require('../../functions/json.php');
    require ('../../functions/query.php');
    $path = "../../private/config.php";
    if (file_exists($path)) {
        $configs = require($path);
    } else {
        echo json_encode(array('error' => 'bad config file path'));
        die();
    }
    $auth = $_GET["auth"];
    $id = $_GET["id"];
    if (empty($id)){
        echo error('no id provided');
    }

    $force = issetGetQuery('force',false);

    if (!empty($id)) {

        $sql = "DELETE FROM maintenance WHERE id = '" . $id . "'";

        $check = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '" . $id . "'", $auth), true);
        if ($check["entries"] == 1) { // Check number of entries with that id.
            $output = sqlPost($sql, $auth);
            if ($output) { // Success message
                echo json_encode(array('success' => true, 'deleted' => $check["output"]), JSON_UNESCAPED_UNICODE);
            }
        } else {
                if ($force) {
                    $output = sqlPost($sql, $auth);
                    if ($output) { // Success message
                        echo json_encode(array('success' => true, 'deleted' => $check["output"]), JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    echo error("More than one id found.");
                }
            }
        } else {
        echo error("No entries found.");
    }
}