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
        function onload() {
            id = document.getElementById('id').value;
            //forceDeletion(document.getElementById('force').checked);
            useMethod('get');
        }
        function showResult(str) {
                    document.getElementById("livesearch").innerHTML = "";
                    document.getElementById("livesearch").style.border = "0px";
            id = str;
            method = 'get'; // Security.
            makerequest();
        }
        function useMethod(str) {
            method = str;
            makerequest();
        }
        function forceDeletion(str) {
            str.checked = force;
            if (str) {
                force = "&force=true";
            } else
                force = "";
        }
        function makerequest() {
            xmlhttp.onreadystatechange=function() {
                if (this.readyState === 4 && this.status === 200) {
                    document.getElementById("livesearch").innerText = JSON.stringify(JSON.parse(this.responseText), null, '\t');
                }
            };
            let petition = "api/" + method + "/job.php?auth=1234&id=" + id + force;
            xmlhttp.open("GET",petition,true);
            xmlhttp.send();
            if (debug) {console.log(xmlhttp);console.log(petition);}
            document.getElementById("url").innerHTML = "[" + method + "] <a href=\"" + window.location.href + "/" + petition + "\">" + petition + "</a>";
        }
    </script>
</head>
<body onload="onload()">
<div>
    <input id="id" type="text" placeholder="id" onkeyup="showResult(this.value);">
    <button onclick="useMethod('get');">get</button>
    <button onclick="useMethod('post');">post</button>
    <button onclick="useMethod('delete');">delete</button>
    <input id="force" type="checkbox" onclick="forceDeletion(this.checked)">
</div>
<br><div id="url"><br></div>
    <div id="livesearch"></div>

</body>
