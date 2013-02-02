<?php
//no  cache headers 
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if ($_REQUEST['logout'] == "yes") { //destroy the session
	session_start();
	$_SESSION = array();
	session_destroy();
}
function CapitalizeName($name) {
    $name = strtolower($name);
    $name = join("'", array_map('ucwords', explode("'", $name)));
    $name = join("-", array_map('ucwords', explode("-", $name)));
    $name = join("Mac", array_map('ucwords', explode("Mac", $name)));
    $name = join("Mc", array_map('ucwords', explode("Mc", $name)));
    return $name;
}

if(isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['name']))
{
	$name = CapitalizeName(trim($_POST['name']));
	$user = strtolower(trim($_POST['user']));
	$pass = $_POST['pass'];
	$ldapconn = ldap_connect("ldap.brentwood.bc.ca")  or die("Could not connect to LDAP server.");
	$bind = ldap_bind($ldapconn, "BCS\\$user", $pass)  or die("Could not connect to LDAP server.");
			if($bind) {
				session_start();
				$_SESSION["userid"] = md5($name);
				header("Location: http://www.yasyf.com/snowball/pictures/");
			}
			else {
				$tried = true;
			}
	
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Snowball Pictures Order From Login</title>
<link href="http://cache.yasyf.com/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<center>
<h2>Snowball Picture Order Form Login</h2>
<br /><br />
<form action="" method="POST" style="border-style:solid;border-width:2px;">
Login With Your Brentwood Credentials <br />
<?php
 if ($tried == true) {
	?>
	<span style="color:red">Login Failed</span><br />
	<?php
}
?>
Name: <input type="text" name="name"/> <br />
Username: <input type="text" name="user"/> <br />
Password: <input name="pass" type="password" /> <br />
<input type="submit" />
</form>
<br /><br />	
	<p><b>Need help? <a href="mailto:yasyf@yasyf.com?subject=Snowball Photos Login Help" style="color:green;text-decoration:none;">Email Yasyf</a>.</b></p>
	</center>	
</body>
</html>