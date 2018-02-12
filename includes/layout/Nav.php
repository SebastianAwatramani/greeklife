<?php
Class Nav {
	function __construct() {
	
		echo "<nav>\n";
		echo "<img id ='navLogo' src='" . HOST . "/images/menuLogo.jpg' />\n";
	
	}

	public function createMenu($ul, $header, $headerUrl = null) {
		echo "<ul class='navUl'>\n";
		echo "<li class='navListHeading boldLink'>";
		if(!is_null($headerUrl)) {
			echo "<a href='$headerUrl'>";
		}
		echo $header;
		if(!is_null($headerUrl)) {
			echo "</a>";
		}
		echo "</li>";
		for($i = 0; $i < count($ul); $i++) {
			echo "\t<li class='menu-item'><a href='{$ul[$i]['url']}'>{$ul[$i]['label']}</a></li>\n";
		}
		echo "</ul>\n";
	}
	public function closeNav() {
		echo "</nav>\n";
	}
}
?>