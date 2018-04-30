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
            padding: 7px 1px 8px 8px;
            font-family: DejaVu Sans Mono, serif;
            font-size: 0.9rem;
        }
        body {
            background-color: #101010;
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        a:hover  {
            text-decoration: underline;
        }
        #empty {
            padding: 1px 2px 0 2px;
        }
        #get {
            color: #2e3f59;
            box-shadow: 0 2px 0 0 #2e3f59;
        }
        #post {
            color: #30634F;
            box-shadow: 0 2px 0 0 #30634F;
        }
        #delete {
            color: #893d3d;
            box-shadow: 0 2px 0 0 #893d3d;
        }
        #force {
            margin: 0;
            vertical-align: middle;
        }
        pre {
            margin: 0;
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

        onload = function () {
            console.log('asdas1');
            id = document.getElementById('id').value;
            // let e = document.getElementById('id');
            //e.oninput = showResult(document.getElementById('id'));
            //e.onpropertychange = e.oninput;
            //forceDeletion(document.getElementById('force').checked);
            useMethod('get');
        };
        function getCurrentDir() {
            let loc = window.location.pathname;
            return loc.substring(0, loc.lastIndexOf('/'));
        }
        function getColor(type) {
            switch (type){
                case 'get':
                    return('#1f4886');
                case 'post':
                    return('#1F865E');
                case 'delete':
                    return('#861f1f');
                case 'patch':
                    return('#86851f');
            }
        }
        function handle(e, value){
            if(e.keyCode === 13){
                showResult(value);
            }
        }
        function emptySearchBox() {
            document.getElementById('id').value = null;
            id = "";
            method = 'get'; // Security?
            makerequest();
        }
        function showResult(str) {
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
                    document.getElementById("json").innerText = JSON.stringify(JSON.parse(this.responseText), null,  ' ');
                }
            };
            let petition = "api/" + method + "/job.php?auth=1234&id=" + id + force;
            xmlhttp.open("GET",petition,true);
            xmlhttp.send();
            if (debug) {console.log(xmlhttp);console.log(petition);}
            document.getElementById("url").innerHTML = "<span id='method'>[" + method + "]</span>  <a href=\"" + getCurrentDir() + "/" + petition + "\" target=\"_blank\">" + petition + "</a>";
            document.getElementById('method').style.color = getColor(method);
        }
    </script>
</head>
<body>
<div class="toolbar">
    <button id="empty" onclick="emptySearchBox();">&#x2613;</button>
    <input id="id" type="text" placeholder="id" onkeypress="handle(event, this.value)" oninput="showResult(this.value)">
    <button id="get" style="color: #2e3f59;" onclick="useMethod('get');">get</button>
    <button id="post" style="color: #30634F;" onclick="useMethod('post');">post</button>
    <button id="delete" style="color: #893d3d;" onclick="useMethod('delete');">delete</button>
    <input id="force" type="checkbox" title="Force deletion" onclick="forceDeletion(this.checked)">
</div>
<br>
<div id="url">
    <br>
</div>
<div>
    <pre id="json"></pre>
</div>


</body>
