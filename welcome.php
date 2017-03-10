<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<title>Welcome!</title> </head>
<body>
<h1>
Trending Stories
</h1>

<?php
require 'database.php';
session_start();
echo "<a href=\"sports.php\">Sports,  </a>";
echo "<a href=\"funny.php\">Funny,  </a>";
echo "<a href=\"news.php\">News,  </a>";
echo "<a href=\"science.php\">Science,  </a><br>";

$stmt = $mysqli->prepare("SELECT COUNT(*), username FROM users WHERE username=?");
// Bind the parameter
$stmt->bind_param('s', $user);
$user = $_SESSION['user_id'];
$stmt->execute(); 
// Bind the results
$stmt->bind_result($cnt, $username);
$stmt->fetch();
if ($cnt == 1){
    echo "<sucess>","Welcome back! ",  $user ,"</sucess> \n";
    echo "<a href=\"profile.php\">See Your Posts</a>";
    echo "<form action=\"logout.php\" method=\"post\"> \n <input type=\"submit\" name=\"Log out\" value=\"Log Out\"/> \n  </form> \n";
    
    echo "<form action=\"post.php\" method=\"post\" > \n ";
    echo " <input type=\"hidden\" name=\"token\" value=\"";
    echo $_SESSION['token'];
    echo "\" /> \n";
    echo "<input type=\"submit\" value=\"Post Story\" /> \n";
    echo "</form> \n";
    
}

else {
    echo "<a href=\"login.php\">LogIn</a> <a href=\"register.php\">SignUp</a>";
    echo "<br>","New user? Returning user? LogIn/SignUp to post and comment!";
}
$stmt -> close();
$pstmt = $mysqli->prepare("select p_id, title, link, p_id, body from stories order by views");
if(!$pstmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}
 
$pstmt->execute();
 
$result = $pstmt->get_result();
 
echo "<ul style=\"list-style: none;\">\n";
while($row = $result->fetch_assoc()){
    $fullBody = $row["body"];
        $truncatedBody = (strlen($fullBody) > 64) ? substr($fullBody, 0, 64).'...' : $fullBody;
        
    if ($row["link"] !== ''){     
	printf("<li><a href= \"%s\"> %s</a>, <a href = \"comments.php?p_id=%d\"> See more... </a></li><li> %s</li><li>  </li>\n",
		htmlspecialchars( $row["link"] ),
        htmlspecialchars( $row["title"] ),
        htmlspecialchars( $row["p_id"] ),
        htmlspecialchars( $truncatedBody)
        );
    }
    else {
        printf("<li> %s,  %d views, <a href = \"comments.php?p_id=%d\"> See more...</a> %s</li> <li>  </li>\n",
        htmlspecialchars( $row["title"] ),
        htmlspecialchars( $row["views"] ),
        htmlspecialchars( $row["p_id"] ),
        htmlspecialchars( $truncatedBody)
        
       
        );
    }
}
echo "</ul>\n";
 
$pstmt->close();
?>

</body>
</html>