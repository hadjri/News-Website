<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head>
<title>NEWS</title>
</head>
<body>
<h1>
NEWS POSTS:
</h1>
<?php
require 'database.php';
session_start();
$stmt = $mysqli->prepare("SELECT COUNT(*), username FROM users WHERE username=?");
// Bind the parameter
$stmt->bind_param('s', $user);
$user = $_SESSION['user_id'];
echo "Account: ", $user, "<br>";

echo "<log> <a href=\"welcome.php\">Back to main page</a></log>";

$pstmt = $mysqli->prepare("select p_id, title, link, views, p_id, body from stories where category='N'");
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
	printf("<li><storyTitle><a href= %s> %s</a>,  %d views, <a href = comments.php?p_id=%d> See more... </a></storyTitle><br> %s</li>\n",
		htmlspecialchars( $row["link"] ),
        htmlspecialchars( $row["title"] ),
        htmlspecialchars( $row["views"] ),
        htmlspecialchars( $row["p_id"] ),
        htmlspecialchars( $truncatedBody)
        );
    }
    else {
        printf("<li><storyTitle> %s,  %d views, <a href = comments.php?p_id=%d> See more...</a></storyTitle><br> %s</li>\n",
        htmlspecialchars( $row["title"] ),
        htmlspecialchars( $row["views"] ),
        htmlspecialchars( $row["p_id"] ),
        htmlspecialchars( $truncatedBody)
        
       
        );
    }
    echo "<br>";
}
echo "</ul>\n";
 
$pstmt->close();
?>
    
    
</body>
</html>