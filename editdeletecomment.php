<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>Adding Comment...</title> </head>
<h1>
Editing Comment...
</h1>

<?php
    require 'database.php';
    session_start() ;
    echo "<log> <a href=\"comments.php?p_id=";
    echo $_POST['p_id'];
    echo "\">Back to Story</a></log>";
   if(!hash_equals($_SESSION['token'], $_POST['token'])){
        header("refresh:3; url= welcome.php");
        die("<h3> Illegal attempt: Please only remove/delete comment from the page of the post! </h3>");
        
    }
    else{
    $cid = $_POST['c_id'];
    //post comment if it was empty after being filtered
        if ($_POST['edit']){
            $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
            $cstmt = $mysqli->prepare("update comments set comment = ? where c_id = ?");
            $cstmt->bind_param("si", $comment, $_POST['c_id']);
            if(!$cstmt){
                 printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
            }
            $cstmt->execute();
            $cstmt->close();
            
            echo "<sucess> Done Editing! </sucess>";
            $return = "refresh:3; url= comments.php?p_id=".$_POST['p_id'];
             header($return);
        }
      if ($_POST['delete']){
            $cstmt = $mysqli->prepare("delete from comments where c_id = ?");
            $cstmt->bind_param("s", $_POST['c_id']);
            if(!$cstmt){
                 printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
            }
            $cstmt->execute();
            $cstmt->close();
            
            echo "<sucess> Done Deleting! </sucess>";
            $return = "refresh:3; url= comments.php?p_id=".$_POST['p_id'];
             header($return);
        }
    }
   
    echo "<form action=\"editdeletecomment.php\" method=\"post\" > \n ";
    echo "<textarea cols=50 rows=4 name=\"comment\" \">";
    echo $_POST['comment'];
    echo "</textarea>";
     echo " <input type=\"hidden\" name=\"token\" value=\"";
        echo $_SESSION['token'];
         echo "\" /> \n";
           echo " <input type=\"hidden\" name=\"p_id\" value=\"";
        echo $_POST['p_id'];
        echo "\" /> \n";
        echo " <input type=\"hidden\" name=\"c_id\" value=\"";
        echo $_POST['c_id'];
        echo "\" /> \n";
            echo "<input type=\"submit\" name=\"edit\" value=\"Edit\" /> \n";
            echo "<input type=\"submit\" name=\"delete\" value=\"Delete\" /> \n";
            echo "</form> \n";
    echo "</form>"
    
?>
</html>