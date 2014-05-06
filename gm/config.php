<?php
/********************************************************************************
* This script is brought to you by Vasplus Programming Blog by whom all copyrights are reserved.
* Website: www.vasplus.info
* Email: info@vasplus.info
* Do not remove this copyright information from the top of this code.
*********************************************************************************/


//Database Connection Settings
define ('hostnameorservername','localhost'); //Your server or host name goes in here
define ('serverusername',''); //Your database Username goes in here
define ('serverpassword',''); //Your database Password goes in here
define ('databasenamed',''); //Your database name goes in here

global $connection;
$connection = @mysql_connect(hostnameorservername,serverusername,serverpassword) or die('Connection could not be made to the SQL Server. Please report this system error at <font color="blue">info@servername.com</font>');
@mysql_select_db(databasenamed,$connection) or die('Connection could not be made to the database. Please report this system error at <font color="blue">info@servername.com</font>');	
?>
