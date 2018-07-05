<?
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 19:17
 */

header('Content-Type: application/json');

if (isset($_GET["auth"]) && isset($_GET["dni"]) && isset($_GET["type"])) {
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
    if (isset($_GET["id"]) && !empty($_GET["id"])) { // Check if no blank id is given in query.
        $id = $_GET["id"];
    } else {
        $id = randomStr(12);
    }
    if (queryNotEmpty('dni')){
        $dni = $_GET['dni'];
    } else {
        echo error('\'dni\' param is empty');
        die();
    }
    if (queryNotEmpty('type')){
        $type = $_GET["type"];
    } else {
        echo error('\'type\' param is empty');
        die();
    }

    $insertSql = "INSERT INTO maintenance (id, Start,DNI,Type) VALUES ('" . $id . "', CURRENT_TIMESTAMP, '". $dni ."', '". $type ."')";

    $checkSql = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '".$id."'", $auth), true); // Check if id already exists.

    if ($checkSql["entries"] == 0){ // Check if id doesn't already exist.
        if (sqlPost($insertSql, $auth)){ // Check success in sqlPost.
            $checkSql = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '".$id."'", $auth), true); // Check created row.
            echo json_encode(array('success' => true,'output' => $checkSql["output"]), JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo error("Id already exists. Use PATCH instead.");
    }
} else {
    //echo json_encode(array('error' => 'invalid input params'));
}