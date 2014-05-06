<?php
/********************************************************************************
* This script is brought to you by Vasplus Programming Blog by whom all copyrights are reserved.
* Website: www.vasplus.info
* Email: info@vasplus.info
* Do not remove this copyright information from the top of this code.
*********************************************************************************/

ini_set('error_reporting', E_NONE);
include "config.php"; //Include your database connection settings file


function format_fullnames($name=NULL) //Format Send's Fullname to Upper case every first letter of their names
{
	/* Formats a first or last name, and returns the formatted version */
	if (empty($name))
		return false;
		
	// Initially set the string to lower, to work on it
	$name = strtolower($name);
	// Run through and uppercase any multi-barrelled name
	$names_array = explode('-',$name);
	for ($i = 0; $i < count($names_array); $i++) 
	{	
		// "McDonald", "O'Conner"..
		if (strncmp($names_array[$i],'mc',2) == 0 || ereg('^[oO]\'[a-zA-Z]',$names_array[$i])) 
		{
			$names_array[$i][2] = strtoupper($names_array[$i][2]);
		}
		// Always set the first letter to uppercase, no matter what
		$names_array[$i] = ucfirst($names_array[$i]);
	}
	// Piece the names back together
	$name = implode('-',$names_array);
	// Return upper-casing on all missed (but required) elements of the $name var
	return ucwords($name);
}

//Sender's information and invitation message
$vpb_fullname = format_fullnames(strip_tags($_POST["vpb_fullname"]));
$vpb_email_address = strip_tags($_POST["vpb_email_address"]);
$vpb_mail_subject = strip_tags($_POST["vpb_mail_subject"]);
$vpb_message_body = strip_tags($_POST["vpb_message_body"]);
$formatMessage = nl2br($vpb_message_body);


//Year and Server
$daYed = date("Y");
$host = $_SERVER['HTTP_HOST'];


//Check all imported email address from the database table
$check_imported_email_address = mysql_query("select * from `import_emails` where `ip` = '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'");
$total_imported_email_address = mysql_num_rows($check_imported_email_address);

if($total_imported_email_address < 1)
{
	//Display error message since there is no imported email address in the database table
	echo '<div id="vpb_info">Hello '.$vpb_fullname.',<br><br>You have currently not imported any email address from your Gmail Account at the moment. <br>Please <span class="ccc"><a href="index.php?vpb_send=go">Click Here</a></span> to import email addresses from your Gmail Account in order to send your invitation.<br><br>Thanks.</div>';
}
else
{
	//Get all imported email addresses from the database table
	while($get_imported_email_address = mysql_fetch_array($check_imported_email_address))
	{
		$vpb_imported_mail_addresses = trim(strip_tags($get_imported_email_address["emails"]));
		
		//Pass all imported email addresses to an array to send invitation
		$receivers_email_addresses = array($vpb_imported_mail_addresses); 



// BEGINNING OF INVITATION 
for($i = 0; $i < count($receivers_email_addresses); $i++)
{
$message = <<<EOF

  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html xmlns="http://www.w3.org/1999/xhtml">
   <head>
   <title>Contact Form Mailer</title>
   </head>
      <body>
	 <table bgcolor="#F9F9F9" align="left" cellpadding="6" cellspacing="6" width="100%" border="0">
     <tr>
    <td valign="top" colspan="2">
	
	
      <p><font style='font-family:Verdana, Geneva, sans-serif; font-size:12px; color:black;'>
	  $formatMessage<br><br>
	  </font>
	  </p>
	  
	  	
     </td>
  </tr>
  <tr>
  <td colspan="2" align="center">
  <table height="40" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#F6F6F6" style="height:30;padding:0px;border:1px solid #EAEAEA;">
  <tr>
    <td><p align='center'><font style="font-family:Verdana, Geneva, sans-serif; font-size:10px;color:black;">Copyright &copy; $daYed | All Rights Reserved.</font></p></td>
  </tr>
</table>
</td>
</tr>
</table>

      </body>
   </html>
EOF;
// END OF MESSAGE 


    //    THIS EMAIL IS THE SENDERS EMAIL ADDRESS
    $from = $vpb_email_address;
   
    //    THIS IS THE SUBJECT OF THE INVITATION
    $subject = $vpb_mail_subject;
            
    //    SET UP THE EMAIL HEADERS
    $headers = "From: $vpb_fullname <$from>\r\n";
    $headers   .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers   .= "Message-ID: <".time().rand(1,1000)."@".$_SERVER['SERVER_NAME'].">". "\r\n";   
   
   
   //   LETS SEND THE INVITATION
   if(mail($receivers_email_addresses[$i], $subject, $message, $headers))
   {
	   //Delete imported email addresses on successful invitation
	   mysql_query("delete from `import_emails` where `ip` = '".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."'");
	   
	   //Display success message
	   $sent_status = '<div id="vpb_info">Hello '.$vpb_fullname.',<br><br>Your invitation has been sent successfully to '.$total_imported_email_address.' email address(es). Thank you for spreading the word about this website.<br><br>Once again, thanks.</div>';
   }
   else
   {
	   //Display error message
	   $sent_status = '<div id="vpb_info">Hey '.$vpb_fullname.',<br><br>Your invitation could not be sent at the moment due to connection problem. <br>Please try again or contact this website admin to report this error message if the problem persist.<br><br>Thanks.</div>';
   }
}
	}

echo $sent_status;
}
?>