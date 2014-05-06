/********************************************************************************
* This script is brought to you by Vasplus Programming Blog by whom all copyrights are reserved.
* Website: www.vasplus.info
* Email: info@vasplus.info
* Do not remove this copyright information from the top of this code.
*********************************************************************************/



//This function is responsible for sending the invitation to all imported email addresses
function Invitation_Form_Submission_By_Vasplus_Programming_Blog() 
{
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var vpb_fullname = $("#vpb_fullname").val();
    var vpb_email_address = $("#vpb_email_address").val();
	var vpb_mail_subject = $("#vpb_mail_subject").val();
	var vpb_message_body = $("#vpb_message_body").val();
	
	
	if(vpb_fullname == "")
	{
		$('#vpb_send_invitation_status').html('<div id="vpb_info">Please enter your fullname in the specified field to proceed. Thanks.</div>');
		$("#vpb_fullname").focus();
		return false;
	}
	else if(vpb_email_address == "")
	{
		$('#vpb_send_invitation_status').html('<div id="vpb_info">Please enter your email address in its field to move on. Thanks.</div>');
		$("#vpb_email_address").focus();
		return false;
	}
	else if(reg.test(vpb_email_address) == false)
	{
		$('#vpb_send_invitation_status').html('<div id="vpb_info">Please enter a valid email address to proceed. Thanks.</div>');
		$("#vpb_email_address").focus();
		return false;
	}
	else if(vpb_mail_subject == "")
	{
		$('#vpb_send_invitation_status').html('<div id="vpb_info">Please enter the subject of your invitation to proceed. Thanks.</div>');
		$("#vpb_mail_subject").focus();
		return false;
	}
	else if(vpb_message_body == "")
	{
		$('#vpb_send_invitation_status').html('<div id="vpb_info">Please enter your message content in the required field to go. Thanks.</div>');
		$("#vpb_message_body").focus();
		return false;
	}
	else 
	{
		var dataString = 'vpb_fullname=' + vpb_fullname + '&vpb_email_address=' + vpb_email_address + '&vpb_mail_subject=' + vpb_mail_subject + '&vpb_message_body=' + vpb_message_body;
		
		$.ajax({
			type: "POST",
			url: "vpb_send_invitation.php",
			data: dataString,
			cache: false,
			beforeSend: function() 
			{
				$('#send').hide();
				$('#sending').fadeIn('slow');
				$("#vpb_send_invitation_status").html('<div align="left" style=" margin-left:110px;font-family:Verdana, Geneva, sans-serif; font-size:12px;padding-top:10px;">Please wait <img src="images/loading.gif" align="absmiddle" title="Loading..." /></div>');
			},
			success: function(response) 
			{
				$("#vpb_fullname").val('');
				$("#vpb_email_address").val('');
				$("#vpb_mail_subject").val('');
				$("#vpb_message_body").val('').animate({
						"height": "100px"
				}, "fast" );
				
				$('#sending').hide();
				$('#send').fadeIn('slow');
				$('#imported_email_addresses_wrapper').fadeOut('slow');
				$('#vpb_send_invitation_status').hide().fadeIn('slow').html(response);
			}
		});
	}
}