<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$name = trim(urldecode($_REQUEST['name']));
$key = $_REQUEST['key'];
$id = $_POST['id'];
if(!$_REQUEST['admin'] && $key != md5(urldecode($name))){
		?>
		<p><b>Need help? <a href="mailto:yasyf@yasyf.com?subject=Snowball Photos Help" style="color:green;text-decoration:none;">Email Yasyf</a>.</b></p>
<?php
		exit("Error: Inavalid Secrey Key");
	}

include "../credentials.php";
if(isset($_POST['id']))
{
	$con = mysql_connect("localhost",$databaseuser,$databasepass);

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("yasyf_snowball", $con);
	mysql_query("DELETE FROM pictures WHERE `id`='$id'");
	mysql_close($con);
	header("Location: http://www.yasyf.com/snowball/pictures/correction.php?confirm=true&name=".urlencode($name)."&key=".md5($name));
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Snowball Pictures Order From</title>
<link href="http://cache.yasyf.com/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<center>
<h2>Snowball Picture Order Correction Form</h2>
<h3 style="color:red">Use This Form To Delete Any Mistaken Orders</h3>
<b>Prices:</b> <span style="color:blue">4x6</span> (<span style="color:green">$2</span>) | <span style="color:blue">5x7</span> (<span style="color:green">$3</span>) | <span style="color:blue">8x10</span> (<span style="color:green">$5</span>)
<br /><br />
<?php
if(!$_REQUEST['admin']){
	?>
<form action="" method="POST" style="border-style:solid;border-width:2px;">
Your Orders:
<select name="id">
<?php
$con = mysql_connect("localhost",$databaseuser,$databasepass);
		if (!$con)
		  {
		  die('Could not connect: ' . mysql_error());
		  }
		mysql_select_db("yasyf_snowball", $con);
			$orders = mysql_query("SELECT * FROM `pictures` WHERE `name`='$name'") or die(mysql_error());
					while($row2 = mysql_fetch_array($orders)){

						$myid = $row2["id"];
						$myphoto = $row2["photo"];
						$mysize = $row2["size"];
						switch ($mysize) {
												case "4x6": $mycost = 2; break;
												case "5x7": $mycost = 3; break;
												case "8x10": $mycost = 5; break;
											}
						echo "<option value='$myid'>Photo #$myphoto ($mysize, $$mycost)</option>";
					}
?>

</select><br />
<input type="submit" value="Delete Order"/>
<?php 
if(isset($_GET['confirm'])){
	?>
	<br />Your order has been deleted.
	<?php
}
?>

</form>
<br /><br />
<?php 
}
?>
	
	<?php if($_REQUEST['admin']){
		?>
		<form action="" method="POST" style="border-style:solid;border-width:2px;">
		All Order:
		<select name="id">
		<?php
		$con = mysql_connect("localhost",$databaseuser,$databasepass);
				if (!$con)
				  {
				  die('Could not connect: ' . mysql_error());
				  }
				mysql_select_db("yasyf_snowball", $con);
					$orders = mysql_query("SELECT * FROM `pictures`") or die(mysql_error());
							while($row2 = mysql_fetch_array($orders)){

								$myid = $row2["id"];
								$myphoto = $row2["photo"];
								$mysize = $row2["size"];
								$myname = $row2["name"];
								switch ($mysize) {
									case "4x6": $mycost = 2; break;
									case "5x7": $mycost = 3; break;
									case "8x10": $mycost = 5; break;
													}
				echo "<option value='$myid'>$myname: Photo #$myphoto ($mysize, $$mycost)</option>";
							}
		?>
		</select><br />
		<input type="Delete Order" />
		<?php 
		if(isset($_GET['confirm'])){
			?>
			<br />Your order has been deleted.
			<?php
		}
		?>
		</form>
		<br /><br />
		<?php
	}
	?>
	<p><b><a href="http://www.yasyf.com/snowball/pictures/" style="color:green;text-decoration:none;">Return To Order Form</a></b></p>
	<p><b>Need help? <a href="mailto:yasyf@yasyf.com?subject=Snowball Photos Help" style="color:green;text-decoration:none;">Email Yasyf</a>.</b></p>
	</center>	
</body>
</html>