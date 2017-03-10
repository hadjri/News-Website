<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>See you later!</title> </head>
<h1>
Good Bye :(
</h1>

<?php
    session_start() ;
    session_destroy();
    header("refresh:3; url=welcome.php");
?>
</html>