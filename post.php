<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>Post!</title> </head>
<h1>
Post a Story!
</h1>
<?php
session_start();
echo "<log> <a href=\"welcome.php\">Back to main page</a></log>";
?>
    
<h2>
 <form action="post.php" method="post">
    Link <input type="text" name="link" size ="101"> <br>
    Title <input type="text" name="title" size="100" > <br>
    <select name="category">
         <option value="sports">Sports</option>
         <option value="news">News</option>
         <option value="funny">Funny</option>
         <option value="science">Science</option>
    </select>
    
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    Body (optional) <textarea cols=50 rows=8 name="comment"></textarea> 
    <input type= "submit" name="submit" value = "Post"/></form>
</h2>
<?php
    require 'database.php';
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        header("refresh:3; url= welcome.php");
        die("<h3> Please select post from the home page! </h3>");
        
    }
    else {
    
    $error = False;
    if($_POST['submit']){
            if (filter_var($_POST['link'], FILTER_VALIDATE_URL ) == False){
                $error = True;
                
                if ($_POST['link']=='' && $_POST['title'] !== ''){
                    $error = False;
                }
            }
             $clean_url =  filter_var($_POST['link'], FILTER_SANITIZE_URL);

        $title = $_POST['title'];
        if ($_POST['category'] =='sports'){
             $category = 'S';
        }
        else if ($_POST['category'] =='news'){
             $category = 'N';
        }
        else if ($_POST['category'] =='science'){
             $category = 'Sc';
        }
        else if ($_POST['category'] =='funny'){
             $category = 'F';
        }
        
        if (!$error){
            $precountstmt = $mysqli -> prepare ("SELECT COUNT(*) FROM stories");
            if(!$precountstmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
              exit;
            }
         $precountstmt->bind_result($preCount);
        $precountstmt ->execute();
        $precountstmt -> fetch();
         $precountstmt -> close();
         
        $stmt = $mysqli->prepare("insert into stories (title, link, body, username, category, date) values ( ?, ?, ?,?,?,NOW())");
        
         $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
        $stmt->bind_param("sssss", $title, $clean_url, $comment, $_SESSION['user_id'],$category);
         if(!$stmt){
             printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        $stmt->execute();
        $stmt->close();
    
        $countstmt = $mysqli -> prepare ("SELECT COUNT(*) FROM stories");
         if(!$countstmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
         exit;
     }
         $countstmt->bind_result($afterCount);
        $countstmt ->execute();
        $countstmt -> fetch();
         $countstmt -> close();
         if ($afterCount > $preCount){
            echo "<sucess>", "Story Added!", "</sucess>";
            
            
            $pidstmt = $mysqli -> prepare ("SELECT LAST_INSERT_ID()");
   $pidstmt -> bind_result($pid);
   $pidstmt -> execute();
   $pidstmt -> fetch();
   $pidstmt -> close();
  
  
         }
         else {
            echo "<h3>", "Story not added; make sure that all fields were filled and valid.", "</h3>";
         }
         
    }
    else {
        echo "<h3>", "Please include the full link; make sure to also include http/https:// in the url", "</h3>";
    }
    }
   
    
    }
    
    
   
?>

</html>