<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>Adding Comment...</title> </head>
<h1>
Attempting to add comment
</h1>

<?php

    require 'database.php';
    session_start() ;

   if(!hash_equals($_SESSION['token'], $_POST['token'])){
        header("refresh:3; url= welcome.php");
        die("<h3> Illegal attempt: Please add comment from the page of the post! </h3>");
        
    }
    else{
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
    //post comment if it was empty after being filtered
     if ($comment !== ''){
             $precountcstmt = $mysqli -> prepare ("SELECT COUNT(*) FROM comments");
            if(!$precountcstmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
              exit;
            }
         $precountcstmt->bind_result($preCCount);
        $precountcstmt ->execute();
        $precountcstmt -> fetch();
         $precountcstmt -> close();
         
        $cstmt = $mysqli->prepare("insert into comments (p_id, comment, username, date) values ( ?, ?, ?,NOW())");
        
        $cstmt->bind_param("dss", $_POST['p_id'], $comment, $_SESSION['user_id']);
         if(!$cstmt){
             printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $cstmt->execute();
        $cstmt->close();
    
        $countcstmt = $mysqli -> prepare ("SELECT COUNT(*) FROM comments");
         if(!$countcstmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
         exit;
     }
         $countcstmt->bind_result($afterCCount);
        $countcstmt ->execute();
        $countcstmt -> fetch();
         $countcstmt -> close();
         if ($afterCCount > $preCCount){
            echo "<sucess>", "Comment Added!", "</sucess>";
            
         }
         else {
            echo "<h3>", "Comment not added due to internal error :( ", "</h3>";
         }
         $return = "refresh:3; url= comments.php?p_id=".$_POST['p_id'];
          header($return);
         
     }
    }
    
    
?>
</html>