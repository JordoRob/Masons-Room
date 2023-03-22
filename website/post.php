<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="utf-8">
    <title>Mason's Tavern</title>
    <?php
    session_start(); 
    include "database.php";?>
    <link rel="stylesheet" href="css/general.css" />
    <link rel="stylesheet" href="css/specific-class.css" />
    <link rel="stylesheet" href="css/specific-id.css" />
    <link rel="stylesheet" href="css/global.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>

        function fun(el) {
            var test = el.parentElement.parentElement.nextElementSibling;
            if (test.getAttribute("style") == null){
                test.setAttribute("style", "display:none;");
                el.children[0].src = "img/tasks/fullscreen.png";
            } else {
                test.removeAttribute("style");
                el.children[0].src = "img/tasks/minimise.png";
            }
        }
    </script>
    <script src="script/joke.js"></script>
</head>

<body>
    <div style="overflow:hidden;">
        <div class="scrolltext"><img src="img/shiba.gif" width="40px" style="transform:scaleX(-1)">Checkout the new
            'Fruit' board!<img src="img/shiba.gif" width="40px" style="transform:scaleX(-1)"></div>
    </div>
    <div id="window" class="outerbox">
        <div id="topbar"><img src="img/mason.png" width="28px">
            <p class="header">Mason's Tavern</p>
            <span class="rowflex-utility-main"><a href="#" class="top-buttons"><img src="img/tasks/Minimise.png"></a><a
                    href="#" class="top-buttons outerbox"><img src="img/tasks/fullscreen.png"></a><a href="#"
                    class="top-buttons outerbox" onclick="joke()">
                    <img src="img/tasks/close.png"></a></span>
        </div>
    
        <?php

        echo ("<nav><a href='home.php'>Home</a>");

        if(isset($_SESSION['logged'])&&isset($_SESSION['username'])&&isset($_SESSION['userpic'])){
            echo("<a  href='account.php?id=".$_SESSION['logged']."' id='logAccount' name=".$_SESSION['logged'].">Account</a><a  href='#' id='logout' onclick='logout()'>Logout</a>");
            if(isset($_SESSION['admin'])){
                if($_SESSION['admin']==true){
                    if(isset($_SESSION['adminVer'])){
                        if($_SESSION['adminVer']==$_SESSION['logged']){
                            echo "<a href=admin.php id='adminPortal'>Admin Tools</a>";
                        }
                    }else{
                    echo "<a href=# id='adminPortal' onclick='adminShow()'>Admin Tools</a>";}
                    
                }}
            echo("<input type=hidden id='sessionUSERID' value=".$_SESSION['logged']."></input>");
            echo("<input type=hidden id='sessionUSERNAME' value=".$_SESSION['username']."></input>");
            echo("<input type=hidden id='sessionUSERPIC' value=".$_SESSION['userpic']."></input>");
        }else{
            echo("<a href='#' id='logAccount' onclick='loginShow()'>Login</a>");
        }
    
        ?>
        </nav>
        <div class="search innerbox">Topic:<span class="search innersearch innerbox">file://<a class="desktop"
                    href="home.php">Home/</a><?php
                            if(isset($_GET['post'])&&isset($_GET['topic'])){
                                $topicid=$_GET['topic'];
                                $postid=$_GET['post'];
                        $query = "SELECT topic_name FROM topics WHERE topic_id=?";
                        $sql = $connection->prepare($query);
                        $sql->bind_param("i",$topicid);
                        $sql->execute();
                        $result = $sql->get_result(); // get the mysqli result
                        $row = $result->fetch_assoc();
                        echo "<a class='desktop' href=topic.php?id=".$topicid.">".ucfirst($row['topic_name'])."/</a><a href=# class='desktop'>Post #".$postid."</a>";
                    ?></span>
        </div>
        <div class='rectangles innerbox' id='main'>
            <div class='work' name=<?php echo $postid; ?>>
        <div class='post-block' id='focuspost'>
        <?php
            $query = "SELECT username,post_title,score,posts.created_at,content,profile_pic,users.user_id,post_id FROM posts JOIN users ON posts.user_id=users.user_id WHERE posts.post_id=?";
            $sql = $connection->prepare($query);
            $sql->bind_param("i",$postid);
            $sql->execute();
            $result = $sql->get_result(); // get the mysqli result
            $row = $result->fetch_assoc();
            $date_time = new DateTime($row['created_at']);
            $date = date_format($date_time, "m/d/Y h:i A");
            $score = $row['score'];
            if($score==null) $score=0;
            echo "<a href='#focuspost' class='thread-nav-main'></a>";
            echo "<a href='account.php?id=".$row['user_id']."' class='colflex-center post-pic'><img class='post-image' src='".$row['profile_pic']."'</img><span id='mainUser'>".$row['username']."</span></a>";
            echo "<div class='outerbox posts-main'>";
            echo "<div class='post-top'>".$row['post_title']."<span>".$date."</span></div>";
            echo "<div class='innerbox posts-body'>";
            echo "<div class='post-content'>".$row['content']."</div>";
            if(isset($_SESSION['logged'])){
                echo "<span class='replyButton outerbox' id='buttonMain' onclick='reply(0)'>Reply</span><span class='replyButton outerbox' id='reportMain' onclick='createReport(this)' data-focus=\"post\" data-focusid='".$row['post_id']."' data-userid='".$row['user_id']."' data-username='".$row['username']."'>Report</span> </div>";
              }else echo "<span class='replyButton innerbox' id='buttonMain' onclick='loginShow()'>Reply</span><span class='replyButton innerbox' id='reportMain' onclick='loginShow()'>Report</span> </div>";
            echo "</div>";
            if(!isset($_SESSION['logged'])){
                echo("<div class='rating'><span class='ratebuttons-disabled'>Like</span></br><span>".$score."</span></br><span class='ratebuttons-disabled'>Dislike</span></div>");
            }else{
                $query2 = "SELECT rating FROM rated WHERE post_id=? AND user_id=?";
                $sql2 = $connection->prepare($query2);
                $sql2->bind_param("ii", $postid,$_SESSION['logged']);
                $sql2->execute();
                $result2 = $sql2->get_result();
                if(mysqli_num_rows($result2)>0){
                    $row2 = $result2->fetch_assoc();
                    if($row2['rating']<0){
                        echo("<div class='rating'><span class='ratebuttons-enabled outerbox' onclick='updateScore(this)'>Like</span></br><span id='scorecount'>".$score."</span></br><span class='Dislike ratebuttons-enabled outerbox' onclick='updateScore(this)' selected>Dislike</span></div>");
                    }else{
                        echo("<div class='rating'><span class='Like ratebuttons-enabled outerbox' onclick='updateScore(this)' selected>Like</span></br><span id='scorecount'>".$score."</span></br><span class='ratebuttons-enabled outerbox' onclick='updateScore(this)'>Dislike</span></div>");
                                }
                }else{
                    echo("<div class='rating'><span class='ratebuttons-enabled outerbox' onclick='updateScore(this)'>Like</span></br><span id='scorecount'>".$score."</span></br><span class='ratebuttons-enabled outerbox' onclick='updateScore(this)'>Dislike</span></div>");
                        }
                        }
                        echo "</div>";
                        echo "<ul class='replies' id='grandpa'>";
                $query="SELECT username, reply_id, content, created_at, profile_pic,users.user_id,banned FROM replies JOIN users ON replies.user_id=users.user_id WHERE post_id=? AND (parent_id IS NULL OR parent_id=0) ORDER BY created_at";
                $sql = $connection->prepare($query);
                $sql->bind_param("i",$postid);
                $sql->execute();
                $largest=0;
                $result = $sql->get_result();
                if (mysqli_num_rows($result) > 0) {
                    foreach($result as $row){
                        if($row['reply_id']>$largest)$largest=$row['reply_id'];
                        $date_time = new DateTime($row['created_at']);
                        $date = date_format($date_time, "m/d/Y h:i A");
                        echo "<li><ul id=".$row['reply_id']." class='replies'>";
                        echo "<div class='posts-reply'>";
                        echo "<div class='innerbox posts-body'>";
                        echo "<span style='font-weight:bold;'><a href='account.php?id=".$row['user_id']."'><img class='reply-image' alt='profile picture' src=".$row['profile_pic']."><span class='reply-username'>".$row['username']." said:</span></a>";
                        echo "<span class=reply-date>".$date."</span></span>";
                        echo "<div class='post-content'>".$row['content']."</div>";
                        if(isset($_SESSION['logged'])){
                          echo "<span class='replyButton outerbox' id='button".$row['reply_id']."' onclick='reply(".$row['reply_id'].")'>Reply</span>";
                            if($row['banned']==0){
                                echo "<span class='replyButton outerbox' id='report".$row['reply_id']."' onclick='createReport(this)' data-focus=\"reply\" data-focusid='".$row['reply_id']."' data-userid='".$row['user_id']."' data-username='".$row['username']."'>Report</span>";
                            }
                            if($_SESSION['logged']==$row['user_id']){
                               echo "<span class='replyButton outerbox' onclick='DelReply(".$row['reply_id'].")'>Delete</span>";
                            }
                          echo "</div>";
                        }else echo "<span class='replyButton innerbox' id='button".$row['reply_id']."' onclick='loginShow()'>Reply</span><span class='replyButton innerbox' id='report".$row['reply_id']."' onclick='loginShow()'>Report</span> </div>";
                        echo "</div><a href='#".$row['reply_id']."' class='thread-nav-reply'></a></ul></li>";  
                    }
                    echo "<input type=hidden id='biggest' value=".$largest."></input>";
                }}else{echo "Oops! Error Occured!";}
                        ?>
                        </ul>
        </div>
            </div>
            <span class='rowflex-center footer-box'><span class="innerbox bottombox">Created by Liam and Jordan</span><span class="innerbox bottombox">Photos by; <a href='https://twitter.com/Poloviiinkin'>@Poloviinkin</a> and <a href='https://www.instagram.com/blpixelartist/'>@blpixelartist</a></span></span>
            </div>
            
    </div>
    
    <div ID="joke" style="display:none"><img src="img/mason.png" width="30px"><span
            style="font-size: 14px;font-weight:lighter">Masons Room</span></div>
            <?php include "loginform.php";
            include "reportform.php";
    if(isset($_SESSION['admin']))
    if($_SESSION['admin']==true){
        include "adminform.php";}?>
    <script src="loginhandler.js"></script>
    <script src="script/deleteReplyhandler.js"></script>
     <script>
        $(document).ready(function(){
            var userid =$("#sessionUSERID").val();
            var postid = $(".work").attr("name");
            var best=$("#biggest").val();
            console.log(best);
            $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/loadreplies.php', //Your form processing file URL
            data      : {id:postid}, //Forms name
            dataType  : 'json',
            success   : function(data){
                $.each(data, function (index, value) {
                var reply = "<li><ul id="+value.reply_id+" class='replies'>";
                reply= reply+ "<div class='posts-reply'>";
                reply= reply+  "<div class='innerbox posts-body'>";
                reply= reply+  "<span style='font-weight:bold;'><a href='account.php?id="+value.user_id+"'><img class='reply-image' alt='profile picture' src="+value.profile_pic+"><span class='reply-username'>"+value.username+" said:</span></a>";
                reply= reply+  "<span class=reply-date>"+value.created_at+"</span></span>";
                reply= reply+  "<div class='post-content'>"+value.content+"</div>";
                if(userid!=null){
                    reply=reply+"<span class='replyButton outerbox' id='button"+value.reply_id+"' onclick='reply("+value.reply_id+")'>Reply</span>";
                    if(value.banned==0){
                       reply=reply+ "<span class='replyButton outerbox' id='report"+value.reply_id+"' onclick='createReport(this)' data-focus=\"reply\" data-focusid='"+value.reply_id+"' data-userid='"+value.user_id+"' data-username='"+value.username+"'>Report</span>";
                    }
                    if(userid==value.user_id){
                        reply=reply+"<span class='replyButton outerbox' onclick='DelReply("+value.reply_id+")'>Delete</span>";
                    }
                    reply=reply+"</div>";
                }else{reply=reply+"<span class='replyButton innerbox' id='button"+value.reply_id+"' onclick='loginShow()'>Reply</span><span class='replyButton innerbox' id='report"+value.reply_id+"' onclick='loginShow()'>Report</span> </div>";
                }
                reply= reply+  "</div><a href='#"+value.reply_id+"' class='thread-nav-reply'></a></ul></li>";  
                reply=$(reply);
                var parent = $("#"+value.parent_id);
                parent.append(reply);
                $(parent).children(".thread-nav-reply").css("display","block");
                if(value.reply_id>best)best=value.reply_id;
                });
            },
            error:  function(jqxhr, status, exception) {
             }
        });
        setInterval(newRep, 30000);
        function newRep(){
            console.log(best);
            var postid = $(".work").attr("name");
            $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/loadreplies.php', //Your form processing file URL
            data      : {id:postid,curRep:best}, //Forms name
            dataType  : 'json',
            success   : function(data){
                $.each(data, function (index, value) {
                var reply = "<li><ul id="+value.reply_id+" class='replies'>";
                reply= reply+ "<div class='posts-reply'>";
                reply= reply+  "<div class='innerbox posts-body'>";
                reply= reply+  "<span style='font-weight:bold;'><a href='account.php?id="+value.user_id+"'><img class='reply-image' alt='profile picture' src="+value.profile_pic+"><span class='reply-username'>"+value.username+" said:</span></a>";
                reply= reply+  "<span class=reply-date>"+value.created_at+"</span></span>";
                reply= reply+  "<div class='post-content'>"+value.content+"</div>";
                if(userid!=null){
                    reply=reply+"<span class='replyButton outerbox' id='button"+value.reply_id+"' onclick='reply("+value.reply_id+")'>Reply</span>";
                    if(value.banned==0){
                       reply=reply+ "<span class='replyButton outerbox' id='report"+value.reply_id+"' onclick='createReport(this)' data-focus=\"reply\" data-focusid='"+value.reply_id+"' data-userid='"+value.user_id+"' data-username='"+value.username+"'>Report</span>";
                    }
                    if(userid==value.user_id){
                        reply=reply+"<span class='replyButton outerbox' onclick='DelReply("+value.reply_id+")'>Delete</span>";
                    }
                    reply=reply+"</div>";
                }else{reply=reply+"<span class='replyButton innerbox' id='button"+value.reply_id+"' onclick='loginShow()'>Reply</span><span class='replyButton innerbox' id='report"+value.reply_id+"' onclick='loginShow()'>Report</span> </div>";
                }
                reply= reply+  "</div><a href='#"+value.reply_id+"' class='thread-nav-reply'></a></ul></li>";  
                reply=$(reply);
                var parent = $("#"+value.parent_id);
                parent.append(reply);
                $(parent).children(".thread-nav-reply").css("display","block");
                if(value.reply_id>best)best=value.reply_id;
                });
            },
            error:  function(jqxhr, status, exception) {
             }
        });
}

        });
     </script>
     <script>
            function updateScore(button){
                var postid=$(".work").attr("name");
                var userid =$("#sessionUSERID").val();
                var pressed = $(button).html();
                if(pressed=='Dislike'){
                    var rating=-1;
                }else if(pressed=='Like'){
                    var rating=1;
                }
                    if($(button).attr('class').includes(pressed)){
                        rating=0;
                    }
                    $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/updatescore.php', //Your form processing file URL
            data      : {post:postid,rating:rating}, //Forms name
            dataType  : 'json',
            success   : function(data){
                console.log(rating);
                var score=data.score;
                if(data.score==null){
                    score=0;
                }
                if(rating==0){
                    $(button).removeClass(pressed);
                }if(rating==1){
                    $(button).addClass(pressed);
                    var away = $(button).siblings();
                    $(away).removeClass('Dislike');

                }if(rating==-1){
                    $(button).addClass(pressed);
                    var away = $(button).siblings();
                    $(away).removeClass('Like');
                }
                $("#scorecount").html(score);
            },
            error:  function(jqxhr, status, exception) {
             alert('Exception:', exception);}
        });
        }
       function reply(parentid){
        console.log(parentid);
        $(".type-reply").remove();
        var currentuser = $("#sessionUSERNAME").val();
        var currentuserpic = $("#sessionUSERPIC").val();
        if(parentid==0){
                    var parent = $("#grandpa");
                    var user = $("#mainUser").text();
                    user+=" as <img class='reply-image' src='"+currentuserpic+"'> "+currentuser+": ";
                }else{
                    var parent=$("#"+parentid);   
        var user = $("#"+parentid).find(".reply-username").first().text();
        user= user.replace("said","as <img class='reply-image' src='"+currentuserpic+"'> "+currentuser);}
        var reply = "<li class='type-reply' id='input"+parentid+"'><ul class='replies'>";
                reply= reply+ "<div class='posts-reply'>";
                reply= reply+  "<div class='innerbox posts-body'>";
                reply= reply+  "<span style='font-weight:bold;'><span class='reply-username'>Reply to "+user+" </span></a>";
                reply= reply+  "<span class=reply-date>Now</span></span>";
                reply= reply+  "<div class='post-content'><textarea id='reply-content' style='width:99%;' rows=5></textarea></div>";
                reply=reply+ "<span class='replyButton outerbox' onclick='sendreply("+parentid+")'>Post</span><span class='replyButton outerbox' onclick='closeReply("+parentid+")'>Cancel</span> </div>";
                reply=reply+"</div></ul></li>";
                reply=$(reply);
                
                if(parent.find("li").first().length)
                parent.find("li").first().prepend(reply);
                else
                    parent.append(reply);
       }
       function closeReply(parentid){
        $("#input"+parentid).remove();
       }
       function sendreply(parentid){
        var postid=$(".work").attr("name");
        var text=$("#reply-content");
        if(!text.val().length){
            text.css("border","1px solid red");
            text.keyup(function(){
                text.css("border","1px solid black");
            })
        }else{
            var content=text.val();
        $.ajax({ //Process the form using $.ajax()
            type      : 'POST', //Method type
            url       : 'ajax/createReply.php', //Your form processing file URL
            data      : {postid:postid,parentid:parentid,content:content}, //Forms name
            success   : function(){
                location.reload();
            },error:function(){
                alert("idfk");
            }
        });
       }}
     </script>  
     <script src="script/fullscreen.js"></script>


</body>

</html>