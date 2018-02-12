<?php 

//Creates a timed JavaScript redirect for pages that should redirect e.g. after 3 seconds
//Timeout is currently hardcoded due to limited usage
Class Redirect {
	function __construct($location) {
		$script = "<script>";
		$script .= "setTimeout(function() {window.location ='";
		$script .= $location;
		$script .= "';}, 3000);</script>";
		echo $script;

	}
}

?>