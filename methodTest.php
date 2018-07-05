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
            margin: 0 9px 0 9px;
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
        .toolbar{
            margin-top: 17px;
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
        #entry{
            cursor:pointer;
        }
        pre {
            margin: 0;
        }
    </style>
    <script>
        let method = "";
        let id = "";
        let dni = "";
        let type = "";
        let force = "";
        //Config
        let debug = false;
        if (window.XMLHttpRequest) {
            // IE7+, Firefox, Chrome, Opera, Safari
            ajax=new XMLHttpRequest();
        } else {  // IE6, IE5
            ajax=new ActiveXObject("Microsoft.XMLHTTP");
        }
        onload = function () {
            id = document.getElementById('id').value;
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
            console.log(e.target.id);
            switch (e.target.id) {
                case 'id':
                    id = value;
                    method = 'get'; // Security.
                    makerequest();
                    break;
                case 'dni':
                    dni = value;
                    if(e.keyCode === 13){
                        console.log("asdfaf");
                        makerequest();
                    }
                    break;
                case 'type':
                    type = value;
                    if(e.keyCode === 13){
                        makerequest();
                    }
            }
        }
        function emptyInputBox() {
            document.getElementById('id').value = null;
            document.getElementById('dni').value = null;
            document.getElementById('type').value = null;
            id = "";
            dni = "";
            type = "";
            method = 'get'; // Security?
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
        let json;
        function changeId(f){
            method = "get"; //Security?
            document.getElementById("id").value = f;
            id = f;
        }
        function makerequest() {
            if (debug) {console.log(method+ " request.");}
            ajax.onreadystatechange = function () {
                if (this.readyState === 4) { //If request is made.
                    if (this.status !== 0) { //If http header is recieved.
                        if (debug) {
                            console.log(this.status + " header received");
                        }
                        if (this.responseText === "") { //No response text in response
                            if (debug) {
                                console.log("No content received.")
                            }
                            document.getElementById("json").innerHTML = "<a style='color:darkred'>No content received from server.</a>"
                        } else {
                            //Only parse json if it isn't empty
                            json = JSON.parse(this.responseText);
                            //Fill input boxes with received info
                            if (json.success === true) {
                                if (debug) {
                                    console.log("success");
                                }
                                if (method !== "get" || json.entries === 1) { //Only one entry from get method.
                                    if (debug) {
                                        console.log("only one entry");
                                    }
                                    dni = json.output[0].DNI;
                                    document.getElementById("dni").value = json.output[0].DNI;
                                    type = json.output[0].Type;
                                    document.getElementById("type").value = json.output[0].Type;
                                }
                            }
                            //Add label to 'id' in json.
                            if (json.entries !== undefined) { //More than one entry
                                for (let i = 0; i < json.entries; i++) {
                                    let obj = json["output"][i];
                                    // console.log(obj.id);
                                    obj.id = "<a id='entry' style='color:goldenrod' onclick='changeId(this.innerText);makerequest();'>" + obj.id + "</a>";
                                }
                            } else { //Only one entry recieved.
                                if (json.success === true) {//If success
                                    json["output"][0].id = "<a id='entry' style='color:goldenrod' onclick='changeId(this.innerText);makerequest();'>" + json["output"][0].id + "</a>";
                                }
                            }
                            //Finally show JSON after (html) changes.
                            document.getElementById("json").innerHTML = JSON.stringify(json, null, ' ');
                        }
                    } else {
                        document.getElementById("json").innerHTML = "<a style='color:darkred'>No response from server</a>"
                    }
                }
            };
            const auth = 1234;
            let petition;
            if (method === "post" || method === "patch"){
                petition = "api/" + method + "/job.php?auth="+ auth +"&id=" + id + "&dni=" + dni + "&type=" + type;
                ajax.open("GET", petition, true);
            } else {
                petition = "api/" + method + "/job.php?auth="+ auth +"&id=" + id + force;
                ajax.open("GET", petition, true);
            }
            ajax.send();
            //Info
            if (debug) {console.log(ajax);console.log(petition);}
            document.getElementById("url").innerHTML = "<span id='method'>[" + method + "]</span>  <a href=\"" + getCurrentDir() + "/" + petition + "\" target=\"_blank\">" + petition + "</a>";
            document.getElementById('method').style.color = getColor(method);
        }
    </script>
</head>
<body>
<div class="toolbar">
    <button id="empty" onclick="emptyInputBox();">&#x2613;</button>
    <input id="id" type="text" placeholder="id" onkeypress="handle(event, this.value)" oninput="handle(event, this.value)">
    <button id="get" style="color: #2e3f59;" onclick="useMethod('get');">get</button>
    <button id="post" style="color: #30634F;" onclick="useMethod('post');">post</button>
    <button id="patch" style="color: #a8a83e;" onclick="useMethod('patch');">patch</button>
    <button id="delete" style="color: #893d3d;" onclick="useMethod('delete');">delete</button>
    <input id="force" type="checkbox" title="Force deletion" onclick="forceDeletion(this.checked)">
    <input id="dni" type="text" placeholder="DNI" style="margin-left: 10px" onkeypress="handle(event, this.value)" oninput="handle(event, this.value)">
    <input id="type" type="text" placeholder="Type" onkeypress="handle(event, this.value)" oninput="handle(event, this.value)">
</div>
<br>
<div id="url">
    <br>
</div>
<div>
    <pre id="json"></pre>
</div>


</body>
