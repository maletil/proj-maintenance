<?php
/**
 * Created by PhpStorm.
 * User: mash
 * Date: 21/04/18
 * Time: 21:56
 */

// mysqli connection.

function mysqlDBConnect () {
    global $configs;
    if ($configs !== null) {
        $username = $configs['username'];
        $password = $configs['password'];
        $servername = $configs['host'];
        $dbname = $configs['dbname'];

        $conn = new mysqli($servername, $username, $password, $dbname);
        mysqli_set_charset($conn, "utf8");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    } else {
        echo json_encode(array('error' => 'no config file provided'));
        die();
    }
}

function mysqlDBDisconnect ($conn) {
    $close = mysqli_close($conn);

    if($close){
        // echo 'Disconnected.';
    }else {
        echo 'Error while disconnecting.';
    }
}

// Get requests.

function sqlGet ($sql, $auth){ // Returns json
    //TODO authcode.

        $conn = mysqlDBConnect();
        $entries = 0;
        if(!$result = mysqli_query($conn, $sql)) return(array("entries" => $entries));

        $sqlArray = array();
        while($row =mysqli_fetch_assoc($result)) {
            $sqlArray[] = $row;
            $entries++;
        }
        mysqlDBDisconnect($conn);
        if ($entries == 0) {
            return false;
        } else {
            $outputArray = array("success" => true, "entries" => $entries, "output" => $sqlArray);
            return json_encode($outputArray, JSON_UNESCAPED_UNICODE);
        }
}

// Post, Delete requests.

function sqlPost($sql, $auth){
    if ($sql !== ""){
        $conn = mysqlDBConnect();
        if ($conn->query($sql) === TRUE) {
            mysqlDBDisconnect($conn);
            return true;
        } else {
            $error = $conn->error;
            mysqlDBDisconnect($conn);
            return $error;
        }
    }
}