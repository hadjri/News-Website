<!DOCTYPE html>
<html>
    <head>
        <title>News Site!</title>
    </head>
    
<body>
    
<?php

if($_GET['msg']==1){
    echo "Congrats! You have successfully registered.";
}

mysql_connect("ec2-54-202-28-206.us-west-2.compute.amazonaws.com","news_user","news_pass") or die(mysql_error());
mysql_select_db("news_site") or die(mysql_error());

$query = mysql_query("SELECT * FROM postings;");
while($row = mysql_fetch_array($query)){
    
    echo '<div style = "font-weight: bold;">';
    echo $row['title'];
    echo '</div>';
    echo "\n";
    echo $row['post'];
    echo "<br />\n";
    echo "Posted at ";
    $row_date = strtotime($row['date']);
    echo date("F j, Y, g:i a", $row_date);
    echo "<br />\n";
    echo "Posted by ";
    echo $row['name'];
    
    
}


?>
    
</body>
</html>