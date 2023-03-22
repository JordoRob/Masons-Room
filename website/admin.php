<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Mason's Tavern Admin Portal</title>
    <?php
    session_start();
    if (!isset($_SESSION['adminVer']) || !isset($_SESSION['logged'])) {
        header('Location: home.php');
    } else if (isset($_SESSION['adminVer']) && isset($_SESSION['logged']))
        if ($_SESSION['adminVer'] != $_SESSION['logged']) {
            header('Location: home.php');
        } else {
            include "database.php"; ?>
        <link rel="stylesheet" href="css/general.css" />
        <link rel="stylesheet" href="css/specific-class.css" />
        <link rel="stylesheet" href="css/specific-id.css" />
        <link rel="stylesheet" href="css/global.css" />
        <link rel="stylesheet" href="css/adminpage.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="script/jquery-3.1.1.min.js"></script>
        <script>
            function joke() {
                var body = document.getElementById("window");
                body.setAttribute("style", "display:none");
                var icon = document.getElementById("joke");
                icon.setAttribute("style", "display:flex");
                icon.addEventListener("dblclick", reversejoke);
            }

            function reversejoke() {
                var icon = document.getElementById("joke");
                icon.setAttribute("style", "display:none");
                var body = document.getElementById("window");
                body.setAttribute("style", "");

            }

            function fun(el) {
                var test = el.parentElement.parentElement.nextElementSibling;
                if (test.getAttribute("style") == null) {
                    test.setAttribute("style", "display:none;")
                    el.children[0].src = "img/tasks/fullscreen.png";
                } else {
                    test.removeAttribute("style");
                    el.children[0].src = "img/tasks/minimise.png";
                }
            }
        </script>
</head>

<body>
    <div style="overflow:hidden;">
        <div class="scrolltext"><img src="img/shiba.gif" width="40px" style="transform:scaleX(-1)">Be a friend not a bully!<img src="img/shiba.gif" width="40px" style="transform:scaleX(-1)"></div>
    </div>
    <div id="window" class="outerbox">
        <div id="topbar"><img src="img/mason.png" width="28px">
            <p class="header">Mason's Tavern</p>
            <span class="rowflex-utility-main"><a href="#" class="top-buttons"><img src="img/tasks/minimise.png"></a><a href="#" onclick="fullscreen()" class="top-buttons outerbox" id="screenchange"><img src="img/tasks/fullscreen.png"></a><a href="#" class="top-buttons outerbox" onclick="joke()">
                    <img src="img/tasks/close.png"></a></span>
        </div>

        <?php

            echo ("<nav><a href='home.php'>Home</a>");
            echo ("<a  href='account.php?id=".$_SESSION['logged']."' id='logAccount' name=" . $_SESSION['logged'] . ">Account</a><a  href='#' id='logout' onclick='logout()'>Logout</a></nav>");
            echo ("<input type=hidden id='sessionUSERID' value=" . $_SESSION['logged'] . "></input>");
            echo ("<input type=hidden id='sessionUSERNAME' value=" . $_SESSION['username'] . "></input>");
            echo ("<input type=hidden id='sessionUSERPIC' value=" . $_SESSION['userpic'] . "></input>");
            $userid = $_SESSION['logged'];

        ?>
        </nav>
        <div class="search innerbox">Topic:<span class="search innersearch innerbox">file://<a class="desktop" href="home.php">Home</a>/Admin Tools</span>
        </div>
        <div class='flexbox'>
        <div class='reportbox innerbox'><h2>Reports:</h2>
        <h3>Search for reports: 
        <form class='admin-form' id='reports'> <input id='reports-searchterm'> by: 
                            <select id='reports-type'>
                            <option value='hero_id'>Hero ID</option>
                                <option value='villain_id'>Villain ID</option>
                                <option value='post_id'>Post ID</option>
                                <option value='reply_id'>Reply ID</option>
                            </select>
                            <label for='resolved' style='font-size:0.6em;'>Show Resolved Reports:</label>
                            <input type='checkbox' id='reports-check' name='resolved'>
                            <input type=submit value='Go' class='adminSubmit' name='reports' id='reportsubmit'></h3>
        </form>
            <ul id='reports-list'>
                <?php
                     $query = "SELECT report_id,focus,topic_id,reports.post_id,reports.reply_id,report,heros.username as hero,villains.username as villain,hero_id,villain_id,created_at FROM reports JOIN users as heros ON reports.hero_id=heros.user_id JOIN users as villains ON reports.villain_id=villains.user_id WHERE resolved=false ORDER BY created_at DESC;";
                    $result = $connection->query($query);
                     if (mysqli_num_rows($result) > 0) {
                         foreach ($result as $row) {

                            $id=0;
                            $villain=$link='account.php?id='.$row['villain_id'];
                            $hero=$link='account.php?id='.$row['hero_id'];
                            $link='account.php?id='.$row['villain_id'];
                            if($row['focus']=="post"){
                            $id=$row['post_id'];
                                $link='post.php?post='.$row['post_id']."&topic=".$row['topic_id'];}
                            if($row['focus']=="reply"){
                                $id=$row['reply_id'];
                                $link='post.php?post='.$row['post_id']."&topic=".$row['topic_id'].'#'.$row['reply_id'];
                            }if($row['focus']=="account"){
                                $id=$row['villain_id'];
                            }
                            echo "<li id='report-".$row['report_id']."'><span class='rowflex-center'><div class='outerbox reportentry'  id='resolved-0'><a class='hero' href=".$hero.">".$row['hero']."</a> reported <a href=".$villain." class='villain'>".$row['villain']."'s</a> <a href=".$link." class='offense'>".$row['focus']." [".$id."]</a>";
                            echo "</br><div class='innerbox reportbody'>".$row['report']."</br></div>".$row['created_at']."</div>";
                            echo "<span class='colflex-center'><a href='#' onclick='resolve(".$row['report_id'].",1)' class='resolved outerbox'>Resolve</a>";
                            echo "<a href='#' onclick='resolve(".$row['report_id'].",2)' class='resolved outerbox'>Delete</a></span></span></li>";
                         }
                        }
                ?>
            </ul>
    </div>
        <div class="rectangles innerbox" id="main2" style="overflow:hidden;">
            <div class='colflex-center'>
                <div class='outerbox admin-block users'>
                <div class="top">Users<span class="rowflex-utility"><a href="#" onclick="fun(this)"
                                class="top-buttons"><img src="img/tasks/minimise.png"></a></span></div>
                                <span>
                    <h3>Search for users:
                        <form class='admin-form' id='users'> <input id='users-searchterm'> by: 
                            <select id='users-type'>
                            <option value='username' selected>Username</option>
                                <option value='email'>Email</option>
                            </select>
                            <label for='banned' style='font-size:0.6em;'>Show Banned Users:</label>
                            <input type='checkbox' id='users-check' name='banned'>
                            <input type=submit value='Go' class='adminSubmit' name='users'>
                        </form>
                    </h3>
                    <div class='innerbox admin-body'  style='display:none;' id='users-body'>
                        <table class='admintable'>
                            <thead><tr>
                                <th>Username</th>
                                <th>User ID</th>
                                <th>Email</th>
                                <th>Posts</th>
                                <th>Replies</th>
                                <th>Reports</th>
                                <th>Ban</th>
                            </tr></thead>
                            <tbody id='users-tablebody'></tbody>
                        </table>
                    </div>
                    </span>
                </div>

                <div class='outerbox admin-block'>
                <div class="top">Posts<span class="rowflex-utility"><a href="#" onclick="fun(this)"
                                class="top-buttons"><img src="img/tasks/minimise.png"></a></span></div>
                                <span >
                    <h3>Search for posts:
                        <form class='admin-form' id='posts'> <input id='posts-searchterm'> by:
                            <select id='posts-type'>
                                <option value='post_title'>Title</option>
                                <option value='content'>Content</option>
                                <option value='username'>Username</option>
                            </select>
                            <label for='deleted' style='font-size:0.6em;'>Show Deleted Posts:</label>
                            <input type='checkbox' id='posts-check' name='deleted'>
                            <input type=submit value='Go' class='adminSubmit' name='posts'>
                        </form>
                    </h3>
                    
                    <div class='innerbox admin-body' style='display:none;' id='posts-body'>
                        <table class='admintable'>
                            <thead>
                            <tr>
                                <th>Username</th>
                                <th>User ID</th>
                                <th>Post ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Tools</th>
                            </tr></thead>
                            <tbody id='posts-tablebody'></tbody>
                        </table>
                    </div>
                    </span>
                </div>

            </div>
        <?php

        }
        ?>
        </div>
    </div>
    <span class='rowflex-center footer-box'><span class="innerbox bottombox">Created by Liam and Jordan</span><span class="innerbox bottombox">Photos by; <a href='https://twitter.com/Poloviiinkin'>@Poloviinkin</a> and <a href='https://www.instagram.com/blpixelartist/'>@blpixelartist</a></span></span>
    </div>
    <div ID="joke" style="display:none"><img src="img/mason.png" width="30px"><span style="font-size: 14px;font-weight:lighter">Masons Room</span></div>

    <?php include "loginform.php"; ?>

    <script src="loginhandler.js"></script>
    <script src="registerhandler.js"></script>
    <script src="script/fullscreen.js"></script>
    <script>
        function resolve(id,resolved){
            if(resolved==2){
                if(!confirm("Only delete reports that are spam or fake, please resolve if it is legitimate.")){
                    return;
                }
            }
                $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/resolve.php', //Your form processing file URL
            data      : {id:id,resolved:resolved}, //Forms name
            dataType  : 'json',
            success   : function(data){
                $("#reportsubmit").click();
            },error: function(){
                alert("shit");
            }
        });}
$(document).ready(function(){
    $(".adminSubmit").click(function(event){
        event.preventDefault();
    var table=event.currentTarget.getAttribute('name');
    $("#"+table+"-body").css("display","block");

    var checked=0;
    if($("#"+table+"-check").is(':checked')){
        checked=1;
    }
    var compare=$("#"+table+"-type").find(":selected").val();
    var term=$("#"+table+"-searchterm").val();
    console.log(checked);
    if(table=="posts"){
        $("#posts-tablebody").children("tr").remove();
    $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/adminQuery.php', //Your form processing file URL
            data      : {term:term,compare:compare,table:table,checked:checked}, //Forms name
            dataType  : 'json',
            success   : function(data){
                $.each(data, function (index, value) {
                    var row = "<tr id="+value.post_id+"><td><a href=account.php?id="+value.user_id+">"+value.username+"</a></td>";
                    row+="<td>"+value.user_id+"</td>";
                    row+="<td>"+value.post_id+"</td>";
                    row+="<td><a href='post.php?post="+value.post_id+"&topic="+value.topic_id+"'>"+value.post_title+"</a></td>";
                    row+="<td class='contentcell'>"+value.content+"</td>";
                    row+="<td><a href=# class='admin-buttons outerbox' title='Delete Post' onclick='deletePost("+value.post_id+")'>X</a></br><a href=# class='admin-buttons outerbox' title='Pin Post' onclick='pinPost("+value.post_id+")'><img class='flip' src='img/tasks/pin.png'></a></td>";
                    row=$(row);
                    $("#posts-tablebody").append(row);
                });
            },error: function(){
                alert("shit");
            }
        });}
else {if(table=='users'){
    $("#users-tablebody").children("tr").remove();
    $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/adminQuery.php', //Your form processing file URL
            data      : {term:term,table:table,compare:compare,checked:checked}, //Forms name
            dataType  : 'json',
            success   : function(data){
                $.each(data, function (index, value) {
                    var row = "<tr><td><a href=account.php?id="+value.user_id+">"+value.username+"</a></td>";
                    row+="<td>"+value.user_id+"</td>";
                    row+="<td>"+value.email+"</td>";
                    row+="<td>"+value.numPosts+"</td>";
                    row+="<td>"+value.numReplies+"</td>";
                    row+="<td>"+value.numReports+"</td>";
                    if(value.banned==1){
                        row+="<td><a href=# class='admin-buttons outerbox' title='Banned'><img src='img/tasks/banned.png'></a></td>";  
                    }else{
                    row+="<td><a href=# class='admin-buttons outerbox' title='Ban user' onclick='ban("+value.user_id+")'><img src='img/tasks/ban.png'></a></td>";}
                    row=$(row);
                    $("#users-tablebody").append(row);
                });
            },error: function() {
  alert("error?");
}
        });
}else if(table=="reports"){
    console.log("term:"+compare);
    $("#reports-list").children("li").remove();
    $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/adminQuery.php', //Your form processing file URL
            data      : {term:term,table:table,compare:compare,checked:checked}, //Forms name
            dataType  : 'json',
            success   : function(data){
                $.each(data, function (index, value) {
                                       var id = 0;
                                       var link = "";
                    var villain = link = 'account.php?id='+value.villain_id;
                   var hero = link = 'account.php?id='+value.hero_id;
                   var link = 'account.php?id='+value.villain_id;
                    if (value.focus == "post") {
                        id = value.post_id;
                        link = 'post.php?post='+value.post_id+"&topic="+value.topic_id;
                    }
                    if (value.focus == "reply") {
                        id = value.reply_id;
                        link = 'post.php?post='+value.post_id+"&topic="+value.topic_id+'#'+value.reply_id;
                    }
                    if (value.focus == "account") {
                        id = value.villain_id;
                    }
                    var row=("<li id='report-"+value.report_id+"'><span class='rowflex-center'><div class='outerbox reportentry' id='resolved-"+value.resolved+"'><a class='hero' href="+hero+">"+value.hero+"</a> reported <a href="+villain+" class='villain'>"+value.villain+"'s</a> <a href="+link+" class='offense'>"+value.focus+" ["+id+"]</a>");
                    row+=("</br><div class='innerbox reportbody'>"+value.report+"</br></div>"+value.created_at+"</div><span class='colflex-center'>");
                    if(value.resolved==0)
                    row+=("<a href='#' onclick='resolve("+value.report_id+",1)' class='resolved outerbox'>Resolve</a>");
                    else
                    row+=("<a href='#' onclick='resolve("+value.report_id+",0)' class='resolved outerbox'>Reflag</a>");
                    row+=("<a href='#' onclick='resolve("+value.report_id+",2)' class='resolved outerbox'>Delete</a></span></span></li>");
                    row=$(row);
                    $("#reports-list").append(row);
                });
            },error: function() {
  alert("error?");
}
        });
}
else{
    alert("This was your fault not mine.");

}
}
});
});
function ban(userid){
    var deleteall=false;
    if(confirm("Do you really want to ban this user?")){
        if(confirm("Would you like to delete all posts and replies associated with this account?")){
            deleteall=true;
        }
    $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/banUser.php', //Your form processing file URL
            data      : {userid:userid,deleteall:deleteall}, //Forms name
            dataType  : 'json',
            success   : function(data){
                if(data.success==3){
                    alert("That did not go right!");
                }else if(data.success==2){
                    alert("Try relogging in probably");
                }else if(data.success==1){
                    alert("You cannot delete admin users!");
                }else if(data.success==0){
                    location.reload();
                }
            },error: function(){
                alert("shit");
            }
    })
}}
    </script>
<script src="script/deletehandler.js"></script>
</body>

</html>