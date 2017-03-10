<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>Editing Post...</title> </head>
<h1>
Editing Post...
</h1>

<?php
    require 'database.php';
    session_start() ;
    echo "<log> <a href=\"comments.php?p_id=";
    echo $_POST['p_id'];
    echo "\">Back to Story</a></log><br>";
   if(!hash_equals($_SESSION['token'], $_POST['token'])){
        header("refresh:3; url= welcome.php");
        die("<h3> Illegal attempt: Please only remove/delete comment from the page of the post! </h3>");
        
    }
    else{
   
        if ($_POST['edit']){
            $error = False;
             if (filter_var($_POST['link'], FILTER_VALIDATE_URL ) == False){
                $error = True;
                
                if ($_POST['link']=='' && $_POST['title'] !== ''){
                    $error = False;
                }
            }
             $clean_url =  filter_var($_POST['link'], FILTER_SANITIZE_URL);
            
            if ($error == False){
            $body = filter_var($_POST['body'], FILTER_SANITIZE_STRING);
            $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $cstmt = $mysqli->prepare("update stories set body = ?, link = ?, title = ? where p_id = ?");
            $cstmt->bind_param("sssi", $body, $clean_url, $title, $_POST['p_id']);
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
             else{
                echo "<h3>", "Need a valid HTML or body or both! </h3>";
             }
        }
      if ($_POST['delete']){
            $cstmt = $mysqli->prepare("delete from stories where p_id = ?");
            $cstmt->bind_param("s", $_POST['p_id']);
            if(!$cstmt){
                 printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
            }
            $cstmt->execute();
            $cstmt->close();
            
            echo "<sucess> Done Deleting! </sucess>";
             header("refresh:3; url= welcome.php");
        }
    }
   
    
    echo "<form action=\"editdeletepost.php\" method=\"post\" > \n ";
    echo "Title: <textarea cols=50 rows=1 name=\"title\" \">";
    echo $_POST['title'];
    echo "</textarea><br>";
    
    echo "Link: <textarea cols=50 rows=1 name=\"link\" \">";
    echo $_POST['link'];
    echo "</textarea><br>";
    echo "Body: <textarea cols=50 rows=4 name=\"body\" \">";
    echo $_POST['body'];
    echo "</textarea><br>";
    
     echo " <input type=\"hidden\" name=\"token\" value=\"";
        echo $_SESSION['token'];
         echo "\" /> \n";
           echo " <input type=\"hidden\" name=\"p_id\" value=\"";
        echo $_POST['p_id'];
        echo "\" /> \n";
       
            echo "<input type=\"submit\" name=\"edit\" value=\"Edit\" /> \n";
            echo "<input type=\"submit\" name=\"delete\" value=\"Delete\" /> \n";
            echo "</form> \n";
    echo "</form>"
    
?>
</html>