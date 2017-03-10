<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>Welcome Back!</title> </head>
<h1>
Log In:
</h1>

<form action="login.php" method="post">
Username: <br/>
<input type="text" name="username" value="" /><br/><br/>
Password: <br/>
<input type="password" name="password" value=""/></br></br>
<input type="submit" name="submit" value="login"/>
</form>


<?php
require 'database.php';

// Use a prepared statement

if($_POST['submit']){
$stmt = $mysqli->prepare("SELECT COUNT(*), username, hashed_password FROM users WHERE username=?");
 
// Bind the parameter
$stmt->bind_param('s', $user);
$user = $_POST['username'];
$stmt->execute();
 
// Bind the results
$stmt->bind_result($cnt, $username, $pwd_hash);
$stmt->fetch();
 
$pwd_guess = $_POST['password'];
// Compare the submitted password to the actual password hash
// In PHP < 5.5, use the insecure: if( $cnt == 1 && crypt($pwd_guess, $pwd_hash)==$pwd_hash){
 
if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	// Login succeeded!
    session_start();
	$_SESSION['user_id'] = $username;
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    echo "<sucess>","Welcome back! Redirecting you to home...","</sucess>";
    
    header("refresh:3; url=welcome.php");
	// Redirect to your target page
} else{
    session_start() ;
    session_destroy();
	 echo "<h3>","The user/password combination does not exist!","</h3><br>";// Login failed; redirect back to the login screen
}
}
?>
</html>