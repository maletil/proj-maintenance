<?php
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 20:02
 */

if (isset($_GET["auth"]) && isset($_GET["id"])) {
    require('../../functions/connection.php');
    require('../../functions/strings.php');
    require('../../functions/json.php');
    require ('../../functions/query.php');
    $auth = $_GET["auth"];
    $id = $_GET["id"];

    $force = issetGetQuery('force',false);

    $sql = "DELETE FROM maintenance WHERE id = '" . $id . "'";

    $check = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '" . $id . "'", $auth), true);
    switch ($check["entries"]){ // Check number of entries with that id.
        case 1:
            $output = sqlPost($sql, $auth);
            if ($output) { // Success message
                echo json_encode(array('success' => 'true', 'output' => $check), JSON_UNESCAPED_UNICODE);
            }
            break;
        case 0:
            echo error("No entries found.");
            break;
        default:
            if ($force){
                $output = sqlPost($sql, $auth);
                if ($output) { // Success message
                    echo json_encode(array('success' => 'true', 'output' => $check), JSON_UNESCAPED_UNICODE);
                }
            } else {
                echo error("More than one id found.");
            }
    }
}