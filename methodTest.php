<?php
/**
 * Created by mash.
 * Date: 23/04/18
 * Time: 0:29
 */
?>

<head>
    <style>
        div {
            background: white;
            color: black;
            padding: 2px 1px 6px 5px;
            font-family: DejaVu Sans Mono, serif;
            font-size: 0.9rem;
        }
        body {
            background-color: #101010;
        }
    </style>
    <script>
        let method = "";
        let id = "";
        let force = "";
        //Config
        let debug = false;
        if (window.XMLHttpRequest) {
            // IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        } else {  // IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        function showResult(str) {
                    document.getElementById("livesearch").innerHTML = "";
                    document.getElementById("livesearch").style.border = "0px";
            id = str;
            makerequest();
        }
        function useMethod(str) {
            method = str;
            makerequest();
        }
        function forceDeletion(str) {
            if (str) {
                force = "&force=true";
            }
        }
        function makerequest() {
            xmlhttp.onreadystatechange=function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("livesearch").innerText = JSON.stringify(JSON.parse(this.responseText), null, "\t");
                }
            };
            let petition = "api/" + method + "/job.php?auth=1234&id=" + id + force;
            xmlhttp.open("GET",petition,true);
            xmlhttp.send();
            if (debug) {console.log(xmlhttp);console.log(petition);}
        }
    </script>
</head>
<body onload="id = document.getElementById('id').value;useMethod('get')">
<div>
    <input id="id" type="text" placeholder="id" onkeyup="showResult(this.value);">
    <button onclick="useMethod('get');">get</button>
    <button onclick="useMethod('post');">post</button>
    <button onclick="useMethod('delete');">delete</button>
    <input type="checkbox" onclick="forceDeletion(this.value)">
</div>
<br><br>
    <div id="livesearch"></div>

</body>
