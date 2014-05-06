<?php 
session_start();
error_reporting(NULL);

include "config.php";
include_once 'configuration.php';
include_once 'gmail_connection_establishment.php';


$oauth = new gmail_connection_establishment($your_consumer_key, $your_consumer_secret, $argarray, $debug, $your_callback_url);
$getcontact_access = new import_gmail_contacts();

$request_token = $oauth->vpb_decoder(strip_tags($_GET['oauth_token']));
$request_token_secret = $oauth->vpb_decoder($_SESSION['oauth_token_secret']);
$oauth_verifier = $oauth->vpb_decoder(strip_tags($_GET['oauth_verifier']));

$contact_access = $getcontact_access->get_access_token($oauth,$request_token, $request_token_secret,$oauth_verifier, false, true, true);

$access_token=$oauth->vpb_decoder($contact_access['oauth_token']);
$access_token_secret = $oauth->vpb_decoder($contact_access['oauth_token_secret']);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Vasplus Programming Blog - Import Gmail Contacts and Send Invitation using Ajax, Jquery and PHP</title>




<!-- Required Header Files -->
<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery_1.5.2.js"></script>
<script type="text/javascript" src="js/vpb_send_invitation.js"></script>



</head>
<body>
<center>
<br clear="all"><div style="width:1000px; font-family:Verdana, Geneva, sans-serif; font-size:25px'">Import Gmail Contacts and Send Invitation using Ajax, Jquery and PHP</div><br clear="all" /><br clear="all" />


















<!-- Code Starts Here -->
<div style="width:600px; line-height:20px;" align="left">
<?php 
if($vpb_g_emails = $getcontact_access->importGmailContacts($oauth, $access_token, $access_token_secret, false, true,$max_email_number)) 
{
	mysql_query("delete from `import_emails` where `ip` = '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'");
	foreach($vpb_g_emails as $vpb_g_email => $vpb_g_e)
	{
		$vpb_emf = end($vpb_g_emails[$vpb_g_email]);
		foreach($vpb_emf as $vpb_e)
		{
			mysql_query("insert into `import_emails` values('', '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."', '".mysql_real_escape_string($vpb_e["address"])."')");
		}
	}
}
$check_imported_email_address = mysql_query("select * from `import_emails` where `ip` = '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'");
$total_imported_email_address = mysql_num_rows($check_imported_email_address);

if($total_imported_email_address < 1)
{
	echo "<br clear='all'><div class='info'>Sorry, we could not import any email address from your Gmail Account at the moment.<br>It could be as a result of connection problem.<br><br>Please <span class='ccc'><a href='index.php?vpb_send=go'>Click Here</a></span> to import the email addresses again. <br><br>Thank You!.</div>"; 
	?>
	<script type="text/javascript"> $(document).ready(function() { $('#vpb_invitationBox').hide(); } ); </script>
	<?php
}
else
{
	echo "<br clear='all'><div class='info'>You have imported ".$total_imported_email_address." email addresses from your Gmail Account.<br>Those email addresses are shown at the bottom of the below form. Thanks...</div>";
}

?>

</div><br clear="all"><br clear="all">




<center>
<div id="vpb_invitationBox" style=" padding:20px;padding-left:50px;padding-right:50px;width:500px;box-shadow: 0 2px 15px #666666;-moz-box-shadow: 0 2px 15px #666666;-webkit-box-shadow: 0 2px 15px #666666;-webkit-border-radius: 15px 15px; 15px 15px;-moz-border-radius: 15px 15px; 15px 15px;border-radius: 15px 15px; 15px 15px;" align="center">

<div style="font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black; line-height:18px;" align="left">Please fill in your fullname and email address then click on the <b>send invitation</b> button to send your invitation to all the below listed email addresses.</div>


<br clear="all" /><br clear="all" />

<div style="width:500px; font-family:Verdana, Geneva, sans-serif; font-size:12px;" align="left">

<div style="width:110px;float:left; padding-top:7px;" align="left">Your Fullname:</div>
<div style="width:300px; float:left;" align="left">
<input type="text" style="width:260px;outline:none;" id="vpb_fullname" class="vasplus_blog_form_opt" value="" />
</div><br clear="all" /><br clear="all" /><br clear="all" />


<div style="width:110px;float:left; padding-top:7px;" align="left">Email Address:</div>
<div style="width:300px; float:left;" align="left">
<input type="text" style="width:260px;outline:none;" id="vpb_email_address" class="vasplus_blog_form_opt" value="" />
</div><br clear="all" /><br clear="all" /><br clear="all" />

<div style="width:110px;float:left; padding-top:7px;" align="left">Email Subject:</div>
<div style="width:300px; float:left;" align="left">
<input type="text" style="width:260px;outline:none;" id="vpb_mail_subject" class="vasplus_blog_form_opt" value="Invitation From Vasplus Programming Blog" />
</div><br clear="all" /><br clear="all" /><br clear="all" />




<div style="width:110px;float:left; padding-top:7px;" align="left">Your Message:</div>
<div style="width:360px; float:left;" align="left">
<textarea id="vpb_message_body" class="vasplus_blog_form_opt" style="width:350px;height:150px;">Hello There!

Guess what? I think I might have found a great programming website that I thought I want to share with you.

vasPLUS Programming Blog is a user friendly programming system that focuses on: JavaScript, JQuery, Ajax, PHP, MySQL database, CSS, HTML and fields includes Software Designs, Software Applications and much more. 

Its a place to learn because free tutorials and demonstrations are given.

vasPLUS Programming Blog also have a powerful Wall Script and Private Messaging System which are similar to that of Facebook sold at a very low cost and moreover, signing up is free, easy and effortless. 

Well, I should know because I signed up already. Sorry for beating you to it.

Regards.
</textarea>
</div><br clear="all" /><br clear="all" />

<div id="vpb_send_invitation_status" align="left"></div><!-- This div displays the invitation result or response from the JS Code -->
<br clear="all" /><br clear="all" />

<div style="width:110px;float:left;" align="left">&nbsp;</div>
<div style="width:300px; float:left;" align="left">
<a href="javascript:void(0);" class="vpb_invite_button" id="send" onClick="Invitation_Form_Submission_By_Vasplus_Programming_Blog();">Send Invitation</a>
<a href="javascript:void(0);" class="vpb_invite_button" style="display:none; font-style:italic;" id="sending">Sending Invitation...</a>
</div><br clear="all" /><br clear="all" />




</div>	
<div id="imported_email_addresses_wrapper">
<br clear="all" /><br clear="all" />
<div class="info" style=" width:400px;"><b>Below are all the imported email addresses</b></div><br clear="all" />
<?php
if($total_imported_email_address < 1){ }
else
{
	while($get_imported_email_address = mysql_fetch_array($check_imported_email_address))
	{
        echo '<div style="width:400px; padding:6px; border:1px solid #F6F6F6; background:#FFF; color:#09F; text-align:left;box-shadow: 0 0 0 10px #cbcbcb;-moz-box-shadow: 0 0 0 10px #cbcbcb;-webkit-box-shadow: 0 0 10px #cbcbcb;">'.trim(strip_tags($get_imported_email_address["emails"])).'</div><br>';
	}
}
?>
</div>
</div>
</center>
<br clear="all" />
<!-- Code Starts Here -->


	
	
	
    





		
		
		
		
	


</center>
</body>
</html>