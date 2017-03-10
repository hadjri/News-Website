<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<body>
<h2>

</h2>
<?php
    require 'database.php';
  
    session_start();
    echo "<log> <a href=\"welcome.php\">Back to main page</a></log>";
    $stmt = $mysqli->prepare("SELECT COUNT(*), username, title, link, body,date FROM stories WHERE p_id=?");
    // Bind the parameter
 
    $postID =  $_GET['p_id'];
  
    
    $stmt->bind_param('i', $postID);
    $stmt->execute(); 
    $stmt->bind_result($cnt, $op, $title, $link, $body, $date);
    $stmt->fetch();
    if ($cnt == 0){
     echo ("This Post Doesn't Exist");
    }
    $stmt ->close();
    
    echo "<h1>",$title, "</h1>";
     echo "<h3>",htmlspecialchars($date), "</h3>";
    echo "<h3> Posted By ", htmlspecialchars($op),"</h3>";
    if ($link !== ''){
     echo "<h3> <a href=\"",htmlspecialchars($link), "\"> Link </a></h3>";
    }
     echo "<h3>",htmlspecialchars($body), "</h3>";
    
    
    $isUser = False;
    $stmt = $mysqli->prepare("SELECT COUNT(*), username FROM users WHERE username=?");
    // Bind the parameter
    $user = $_SESSION['user_id'];
    $stmt->bind_param('s', $user);
    $stmt->execute(); 
    $stmt->bind_result($cnt, $username);
    $stmt->fetch();
    if ($cnt == 1){
    $isUser = True;
    }
    $stmt ->close();
    
    
    

        if (hash_equals($_SESSION['user_id'], $op)){
            echo "<form action=\"editdeletepost.php\" method=\"post\" > \n ";
            echo " <input type=\"hidden\" name=\"token\" value=\"";
            echo $_SESSION['token'];
            echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"p_id\" value=\"";
            echo $_GET['p_id'];
            echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"body\" value=\"";
            echo htmlentities($body);
            echo "\" /> \n";
              echo " <input type=\"hidden\" name=\"title\" value=\"";
            echo htmlentities($title);
            echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"link\" value=\"";
            echo htmlentities($link);
            echo "\" /> \n";
            echo "<input type=\"submit\" value=\"Edit/Delete\" /> \n";
            echo "</form> \n";
        }
    
    
    
    $pstmt = $mysqli->prepare("select comment, username, date, c_id from comments WHERE p_id = ? order by date");
      $pstmt -> bind_param('i',$_GET['p_id']);
    if(!$pstmt){
    	printf("Query Prep Failed: %s\n", $mysqli->error);
    	exit;
    }
    
    $pstmt->execute();
    $result = $pstmt->get_result();
    echo "<ul style=\"list-style: none;\">\n";
    while($row = $result->fetch_assoc()){
	printf("<li> At %s, <user>%s</user> said, <br>%s<br>",
        htmlspecialchars( $row["date"] ),
		htmlspecialchars( $row["username"] ),
        htmlspecialchars( $row["comment"] )
	);
    if (strcmp($row["username"], $_SESSION['user_id']) ==0){
           echo "<form action=\"editdeletecomment.php\" method=\"post\" > \n ";
            echo " <input type=\"hidden\" name=\"token\" value=\"";
            echo $_SESSION['token'];
            echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"p_id\" value=\"";
            echo $_GET['p_id'];
            echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"c_id\" value=\"";
            echo $row['c_id'];
               echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"comment\" value=\"";
            echo $row['comment'];
            echo "\" /> \n";
            echo "<input type=\"submit\" value=\"Edit/Delete\" /> \n";
            echo "</form> \n";
    }
    echo "<br>----------------------------------------------------</li>\n";
}
    echo "</ul>\n";
    $pstmt->close();
    
    
    if ($isUser ==True){
            echo "<form action=\"addcomment.php\" method=\"post\" > \n ";
            echo "Share a Comment: <textarea cols=50 rows=4 name=\"comment\"></textarea>";
            echo " <input type=\"hidden\" name=\"token\" value=\"";
            echo $_SESSION['token'];
            echo "\" /> \n";
            echo " <input type=\"hidden\" name=\"p_id\" value=\"";
            echo $_GET['p_id'];
            echo "\" /> \n";
            echo "<input type=\"submit\" value=\"Submit\" /> \n";
            echo "</form> \n";
    }
    
    
    //header("refresh:3; url=welcome.php");
	// Redirect to your target page
?>
</body>
</html>