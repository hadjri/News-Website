<!DOCTYPE HTML>
<html>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<head> <title>Register</title> </head>

<h1>
Join us!
</h1>
<h2>
<form action="register.php" method="post">
Username: <br/>
<input type="text" name="username" value="" /><br/><br/>
Email:</br>
<input type="text" name="email" value=""/></br></br>
Password:<br/>
<input type="password" name="password" value=""/></br></br>
Retype Password:<br/>
<input type="password" name="retype_password" value=""/></br></br>
<input type="submit" name="submit" value="Register"/>
</h2>
</form> 
<?php
echo "<body>";
require 'database.php';

if($_POST['submit']){
$error = False;
if (preg_match('/[^a-zA-Z._\-0-9]/i', $_POST['username'])){
    echo "<h3>", "Invalid Username; please only use alpha-numerical characters, ',', '_',and '-'.", "</h3>";
       $error = True;
}
else{
   $clean_username = $_POST['username'];
   //echo "<h4>";
   // print_r ($clean_username);
   // echo "</h4>";

}
$raw_email = $_POST['email'];
$sanitized_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$pw = '';
if (!strcmp($sanitized_email, $raw_email)==0 || !filter_var($sanitized_email, FILTER_VALIDATE_EMAIL)){
    echo "<h3>", "Invalid Email", "</h3>";
     $error = True;
}
if (preg_match('/[^a-zA-Z@#\*$%\^._\-0-9]/i', $_POST['password'])){
     echo "<h3>", "Invalid Password; please only use alpha-numerical characters, '@','', '_',and '-'.", "</h3>";
      $error = True;
}

else {
    $pw = $_POST['password'];
    if (preg_match('/[^a-zA-Z@#\*$%\^._\-0-9]/i', $_POST['retype_password'])){
     echo "<h3>", "Invalid Password; please only use alpha-numerical characters, '@','', '_',and '-'.", "</h3>";
 $error = True;
    }
    else {
        $rpw = $_POST['retype_password'];
        if (strcasecmp($pw, $rpw) != 0){
             echo "<h3>", "Your passwords did not match.", "</h3>";
             $error = True;
        }
    }
} 
if (!$error){
    $hashedPW = password_hash($pw,PASSWORD_BCRYPT);
    
    $precountstmt = $mysqli -> prepare ("SELECT COUNT(*) FROM users");
   if(!$precountstmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
   }
   $precountstmt->bind_result($preCount);
   $precountstmt ->execute();
   $precountstmt -> fetch();

   $precountstmt -> close();
  
    
    
    $stmt = $mysqli->prepare("insert into users (username, hashed_password, email, date_joined, num_posts) values ( ?, ?, ?, NOW(), 0)");
    $stmt->bind_param("sss", $clean_username, $hashedPW, $sanitized_email);
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    
    $stmt->execute();
    $stmt->close();
    
     $countstmt = $mysqli -> prepare ("SELECT COUNT(*) FROM users");
   if(!$countstmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
   }
   $countstmt->bind_result($afterCount);
   $countstmt ->execute();
   $countstmt -> fetch();

   $countstmt -> close();
   if ((int)$preCount < (int)$afterCount){
    echo "<sucess>", "Registered Sucessfully! Redirecting you to home...", "</sucess>";
    session_start();
    $_SESSION['user_id'] = $clean_username;
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
     header("refresh:3; url= welcome.php");
   }
   else{
     echo "<h3>", "Could not register. An account with the same username/email already exists!", "</h3>";
    session_start() ;
    session_destroy();
   }
}
}
echo "</body>";

?>
</html>