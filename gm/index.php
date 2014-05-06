<?php
session_start();
error_reporting(NULL);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Vasplus Programming Blog - Import Gmail Contacts and Send Invitation using Ajax, Jquery and PHP</title>


<!-- Required Header File -->
<link href="css/style.css" rel="stylesheet" type="text/css">


</head>
<body>
<center>
<br clear="all"><div style="width:1000px; font-family:Verdana, Geneva, sans-serif; font-size:25px'">Import Gmail Contacts and Send Invitation using Ajax, Jquery and PHP</div><br clear="all" /><br clear="all" />










<!-- Code Starts Here -->
<?php
if(isset($_GET["vpb_send"]) && !empty($_GET["vpb_send"])) //The $_GET["vpb_send"] is gotten from the Invite Gmail Contacts button clicked
{
	include_once 'configuration.php';
	include_once 'gmail_connection_establishment.php';
	$oauth = new gmail_connection_establishment($your_consumer_key, $your_consumer_secret, $argarray, $debug, $your_callback_url);
	$getcontact = new import_gmail_contacts();
	$access_token = $getcontact->get_request_token($oauth, false, true, true);
	$_SESSION['oauth_token'] = $access_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $access_token['oauth_token_secret'];

	?>
    
    
    <div id="vasplus_programming_blog_wrapper">
     <p align="center"><a href="https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=<?php echo $oauth->vpb_decoder($access_token['oauth_token']); ?>"> <img src='images/connect.png' alt="Google Oauth Connect" border='0'/> </a></p><br clear="all" /><br clear="all" />
        
        <meta http-equiv='refresh' content='10;URL=https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=<?php echo $oauth->vpb_decoder($access_token['oauth_token']); ?>'>
       
        <p align="center"><img src="images/loading.gif" title="Loading..." /></p> <br clear="all" /><br clear="all" />
        <p align="center"><font style="font-family:Verdana, Geneva, sans-serif; font-size:13px;color:#CC0000;">You will be redirected stortly to login to your Gmail Account if not already logged in.</font></p>
    
    </div>

     <?php
}
else
{
	 ?>
     <div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black; width:550px; line-height:20px;" align="left">To demo the system, please click on the Invite Gmail Contacts button below.</div><br clear="all"><br clear="all">
     
     <a href="index.php?vpb_send=go" class="vpb_invite_button">Invite Gmail Contacts</a>
	 <?php
}
?>
<!-- Code Ends Here -->









</center>
</body>
</html>
