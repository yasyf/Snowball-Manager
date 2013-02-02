<?php
//no  cache headers 
header("Location: http://www.yasyf.com/snowball/pictures/");
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Snowball Manager</title>
</head>
<body>
<?php

include "data.php";
include "credentials.php";
if(isset($_POST['name']) && isset($_POST['link']) and !isset($_POST['submit']))
{
	$con = mysql_connect("localhost",$databaseuser,$databasepass);

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_snowball", $con);
	$key = $_POST['name'];
	$name = $names[$key];
	$email = $emails[$key];
	$link = $_POST['link'];
	mysql_query("INSERT INTO names (`name`,`email`,`link`,`key`) VALUES ('$name', '$email', '$link', $key);");
	mysql_close($con);
	$text = "Hey ".$name.", \r\n Thanks for confirming your attendance to Snowball. A reminder that the event is on Saturday, December 8th, starting at 6:00pm. \r\n If you have an Apple device, you can add this ticket to your PassBook with the following link: ".$link.".";
	$html = "Hey ".$name.", <br /> Thanks for confirming your attendance to Snowball. A reminder that the event is on Saturday, December 8th, starting at 6:00pm. <br /> If you have an Apple device, you can add this ticket to your PassBook with the following link: <a href='".$link."'>".$link."</a>.";
	include 'sendgrid/SendGrid_loader.php';
	$sendgrid = new SendGrid($mailuser, $mailpass);
	$mail = new SendGrid\Mail();
	$mail->addTo($email)->
	       setFrom('snowball@yasyf.com')->
	       setSubject('Snowball Ticket Confirmation')->
	       setText($text)->
		       setHtml($html);
	$sendgrid->smtp->send($mail);
	echo "<img src='http://www.imaginecup.lk/App_Themes/Rainbow/images/icon_green_bullet.png'  /> Succesfully Delivered Mail To $name";

}
else if (isset($_POST['name']) && $_POST['submit'] == "Lookup") {

	$con = mysql_connect("localhost",$databaseuser,$databasepass);
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_snowball", $con);
		$key = $_POST['name'];
		$result = mysql_query("SELECT `link` FROM `names` WHERE `key`=$key") or die(mysql_error());
		$name = $names[$key];
		$email = $emails[$key];
		$row = mysql_fetch_array($result);
		$link = $row[0];
		if(mysql_num_rows($result) == 1) {
		  echo "<img src='http://www.imaginecup.lk/App_Themes/Rainbow/images/icon_green_bullet.png'  /> $name Paid! Link: <a href='$link'>$link</a>";
		}
		else {
		    echo "<img src='http://www.alpicasa.nl/siteimg/css/home_circle_red_right.png'  /> $name  Not Paid.";
		}
}
else if (isset($_POST['name']) && $_POST['submit'] == "Resend") {
	$con = mysql_connect("localhost",$databaseuser,$databasepass);
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_snowball", $con);
		$key = $_POST['name'];
		$result = mysql_query("SELECT `link` FROM `names` WHERE `key`=$key") or die(mysql_error());
		$name = $names[$key];
					$email = $emails[$key];
					$row = mysql_fetch_array($result);
					$link = $row[0];
		if(mysql_num_rows($result) == 1) {
			$text = "Hey ".$name.", \r\n Thanks for confirming your attendance to Snowball. A reminder that the event is on Saturday, December 8th, starting at 6:00pm. \r\n If you have an Apple device, you can add this ticket to your PassBook with the following link: ".$link.".";
			$html = "Hey ".$name.", <br /> Thanks for confirming your attendance to Snowball. A reminder that the event is on Saturday, December 8th, starting at 6:00pm. <br /> If you have an Apple device, you can add this ticket to your PassBook with the following link: <a href='".$link."'>".$link."</a>.";
			include 'sendgrid/SendGrid_loader.php';
			$sendgrid = new SendGrid($mailuser, $mailpass);
			$mail = new SendGrid\Mail();
			$mail->addTo($email)->
			       setFrom('snowball@yasyf.com')->
			       setSubject('Snowball Ticket Confirmation')->
			       setText($text)->
				       setHtml($html);
			$sendgrid->smtp->send($mail);
			echo "<img src='http://www.imaginecup.lk/App_Themes/Rainbow/images/icon_green_bullet.png'  /> Succesfully Delivered Mail  To $name<br />"; 
		 echo "<img src='http://www.imaginecup.lk/App_Themes/Rainbow/images/icon_green_bullet.png'  /> $name Paid! Link: <a href='$link'>$link</a>";
		}
		else {
		    echo "<img src='http://www.alpicasa.nl/siteimg/css/home_circle_red_right.png'  /> $name  Not Paid.";
		}
}
?>
<form action="" method="POST">

<select name="name">
<?php
foreach ($names as $key => $value) {
	?>  
	<option value="<?php echo $key; ?>" <?php if($key == $_GET['key']) echo "selected='selected'";?>><?php echo $value; ?></option>
<?php
}
?>
</select>
<input type="text" name="link" value="<?php if(isset($_GET['link'])) echo $_GET['link'];?>"/> 
<input type="submit" value="Insert" />
 <input type="submit" name="submit" value="Lookup">
 <input type="submit" name="submit" value="Resend">
</form>
		<a href="list.php">Master List</a>	
</body>
</html>