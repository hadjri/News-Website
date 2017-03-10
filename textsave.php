<?php
$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$message = $_POST['feedback'];
fwrite($myfile, $message);

fclose($myfile);
?>


<html>
<head>
	<title>File Sharing Website</title>
	
</head>
<body>
  
    <form method="post" enctype="multipart/form-data">
		<input type="hidden" name="upload" value="1"/>
        <p>File goes here:<br />
        <input type="file" name="uploaded"/></p>
        <p>Enter Username:<br />
        <input type="text" name="username"/></p>
        <p><input type="submit" name="submit" value="Submit"/></p>
	</form></br></br>
	
	<form action = "" method = "post">
		
	<label>
		What did you think of our website? Tell us!<br>
		<textarea cols=50 rows=8 name="feedback"></textarea>
	</label></br>
		<input type="submit" name="feedback_send" value="Send us feedback">
		
	</form>
        

</body>


</html>