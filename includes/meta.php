<?php

//////////////////////////////////////////////////////////////////////////////////////////////////
// Settings here decide site behavior											 			  	//
// SOCIETY: Determines the type of site, e.g. sorority, mcg, ifc 								//
// MEMBERSTATUS: e.g. member, recruit                                                           //
// FILEPREFIX: e.g. sorority_member, used to build internal links								//
//      																						//
//////////////////////////////////////////////////////////////////////////////////////////////////

define("HOST", "http://localhost/greeklife");
define("SOCIETY", "sorority");
define("ROOT", "greeklife");

if(isset($_SERVER['SCRIPT_NAME'])) {
	$search = strpos($_SERVER['SCRIPT_NAME'], 'member');
	if($search) {
		define("MEMBERSTATUS", "member");
	} else {
		$search = strpos($_SERVER['SCRIPT_NAME'], 'recruit');
		if($search) {
			define("MEMBERSTATUS", "recruit");
		} else {
			define("MEMBERSTATUS", "");
		}
	}

	//Set a variable to check if page is an admin page or an error page.  This is to set the correct header text in <header>
	$search = strpos($_SERVER['SCRIPT_NAME'], 'admin');
	($search) ? $isAdminPage = TRUE: $isAdminPage = FALSE;


	$search = strpos($_SERVER['SCRIPT_NAME'], 'error');
	($search) ? $isErrorPage = TRUE: $isErrorPage = FALSE;
	
}	

define("MAINTABLE", SOCIETY . "_" . MEMBERSTATUS . "s");
define("FILEPREFIX", SOCIETY . "_" . MEMBERSTATUS . "_");
define("SECUREFOLDER", HOST . "/" ."secure/");
define("ADMINSECURE", HOST . "/" . "admin_secure/");
define("INCLUDESDIR", $_SERVER['DOCUMENT_ROOT'] . "\greeklife\\" ."\includes\\");

define("BR", "<br>");




?>