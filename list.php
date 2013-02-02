<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<title>Snowball Manager - <?php if(isset($_POST['type'])) {echo $_POST['type'];} else {echo "All";}?> Students</title>
</head>
<body>
<center>
<form action="" method="POST">
<input type="submit" name="type" value="All" />
<input type="submit" name="type" value="Paid" />
<input type="submit" name="type" value="Unpaid" />
</form>
			
<table border=1>
  <tbody>
    <tr>
<th>Key</th>      
<th>Name</th>
      <th>Status</th>
      <th>Ticket</th>
 <th>Edit</th>
    </tr>


<?php
include "data.php";
include "credentials.php";
$unpaid = array();
$paid = array();
	$con = mysql_connect("localhost",$databaseuser,$databasepass);
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_snowball", $con);
		foreach ($names as $key => $value) {
		$result = mysql_query("SELECT `link` FROM `names` WHERE `key`=$key") or die(mysql_error());
		$name = $names[$key];
		$email = $emails[$key];
		if(mysql_num_rows($result) == 1) {
			array_push($paid,$key);
			
		$row = mysql_fetch_array($result);
		$link = $row[0]; 
		if($_POST['type'] != "Unpaid")
					{
		 echo "<tr>
		 <td>$key</td>     
		 <td>$name</td>
		      <td><img src='http://www.imaginecup.lk/App_Themes/Rainbow/images/icon_green_bullet.png'  /></td>
		      <td><a href='$link'>$link</a></td>
		<td><a href='javascript:edit($key);'>Un-Sign-Up</a></td>
		    </tr>";
		
			}
		}
		else{
			array_push($unpaid,$key);
			if($_POST['type'] != "Paid")
			{
				
		echo "<tr>
		<td>$key</td>
				      <td>$name</td>
				      <td><img src='http://www.alpicasa.nl/siteimg/css/home_circle_red_right.png'  /></td>
				      <td>N/A</td>
				<td><a href='javascript:edit($key);'>Sign-Up</a></td>
				    </tr>";    
				
			}
		}
}
?>
	  </tbody>
	</table>	
	<script>
				  function edit(key){
					var link = window.prompt("Ticket Link");
					window.location = 'index.php?key='+key+'&link='+encodeURIComponent(link);
				}
				</script>
				<?php 
				if($_POST['type'] == "Unpaid")
				{
				?>
				<form action="" method="POST">
				<input type="submit" name="type" value="Email" />
				</form>
				<?php
				}
				if($_POST['type'] == "Email")
				{
					include 'sendgrid/SendGrid_loader.php';
					$sendgrid = new SendGrid($mailuser, $mailpass);
					foreach ($unpaid as $key => $value) {
						$name = $names[$value];
						$email = $emails[$value];
						$text = "Hey ".$name.", \r\n If you are receiving this email, you are currently not signed up to attend Snowball. A reminder that the event is on Saturday, December 8th, starting at 6:00pm, well after exams. \r\n Even if you don't have a date, it will be a fun time to hang out and enjoy a nice meal. If you would like to attend, please see your Grad Council representative as soon as possible.";
							$html = "Hey ".$name.", <br /> If you are receiving this email, you are currently not signed up to attend Snowball. A reminder that the event is on Saturday, December 8th, starting at 6:00pm, <b>well after exams</b>. <br /> Even if you don't have a date, it will be a fun time to hang out and enjoy a nice meal. If you would like to attend, please see your Grad Council representative as soon as possible.";
							$mail = new SendGrid\Mail();
							$mail->addTo($email)->
							       setFrom('snowball@yasyf.com')->
							       setSubject('Snowball Attendance')->
							       setText($text)->
								       setHtml($html);
							$sendgrid->smtp->send($mail);
				echo "<img src='http://www.alpicasa.nl/siteimg/css/home_circle_red_right.png'  /> Succesfully Delivered Mail To $name<br />"; 
					}
					
				}
				?>
				</center>
</body>
</html>