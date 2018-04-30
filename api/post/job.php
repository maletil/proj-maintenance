<?
/**
 * Created by mash.
 * Date: 22/04/18
 * Time: 19:17
 */

if (isset($_GET["auth"])) {
    require('../../functions/connection.php');
    require('../../functions/strings.php');
    require('../../functions/json.php');
    $path = "../../private/config.php";
    if (file_exists($path)) {
        $configs = require($path);
    } else {
        echo json_encode(array('error' => 'bad config file path'));
        die();
    }
    $auth = $_GET["auth"];

    if (isset($_GET["id"]) && $_GET["id"] !== ""){ // Check if no blank id is given in query.
        $id = $_GET["id"];
    } else { // If not, create one
        $id = randomStr(12);
    }

    $sql = "INSERT INTO maintenance (id, Start,DNI,Type) VALUES ('" . $id . "', CURRENT_TIMESTAMP, 'Dni', 'Tipo')";

    $check = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '".$id."'", $auth), true); // Check if id already exists.

    if ($check["entries"] == 0){ // Check if id already exists.
        $output = sqlPost($sql, $auth); // Insert row.
        if ($output){ // Check success in sqlPost.
            $check = json_decode(sqlGet("SELECT * FROM maintenance WHERE id = '".$id."'", $auth), true); // Check if created.
            echo json_encode(array('success' => true,'created' => $check["output"]), JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo error("Id already exists. Use PATCH instead.");
    }
}