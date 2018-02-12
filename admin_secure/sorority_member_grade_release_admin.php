<?php

//IF main search gets an empty result shift, array_unshift() throws an error.  
   ///I think I fixed this because I can't reproduce it anymore

require_once("../includes/meta.php");
require("../includes/layout/header.php");
require("../includes/Database.php");
require("../includes/MembersList.php");
require("../includes/Form.php");
require("../includes/SocietyList.php");
require("../includes/createDatabase.php");

$searchFor = "";

//searchFor/$societySearch might be set if (a) a user searched for someone, (b) searched for a sorority or (c) a user clicked a 'remove' link and then canceled.  In either case, this  will serve to filter the results the page displays
if (isset($_POST['searchFor']) && !empty($_POST['searchFor'])) {
	$searchFor = $_POST['searchFor'];
} elseif(isset($_GET['searchFor']) && !empty($_GET['searchFor'])) {
	$searchFor = $_GET['searchFor'];
}

if (isset($_POST['societySearch']) && !empty($_POST['societySearch']) && $_POST['societySearch'] != "All") {
	$societySearch = $_POST['societySearch'];
} elseif (isset($_GET['societySearch']) && !empty($_GET['societySearch']) && $_GET['societySearch'] != "All") {
	$societySearch = $_GET['societySearch'];
} else {
	$societySearch = "All";
}


$membersList = new MembersList(MAINTABLE, $searchFor, $societySearch, $db);
$members = $membersList->getMembers();



?>
<section id="main">
<h1><?php echo ucfirst(SOCIETY) . " " . ucfirst(MEMBERSTATUS) . " "; ?>Grade Release Admin</h1>
<h3>Search in the Following Sorority: </h3>

<?php
//Build search form
$name = "search";
$method = "post";
$action = FILEPREFIX . "grade_release_admin.php";
$attributes = array(
	"id" => "search",
	"onSubmit" => "return check_form();"
	);
$form = new Form($name, $method, $action, $attributes);

//Only need to print the society selection buttons if we're on a members page, otherwise just defaults to "all"
if(MEMBERSTATUS === "member") {

	$societyList = new SocietyList($db, $societySearch, true);
	$societyList->printSocietyRadioButtons(TRUE);
}

$name = "searchFor";
$type = "text";
$attributes = array(
	"id" => $name
	);
if (isset($searchFor)) {
	$attributes['value'] = $searchFor;
}
new InputBox($name, $type, $attributes);

$value = "Search";
new SubmitButton($value);
echo "<p>*First Name, Last Name, SID</p>";

$form->closeForm();
?>

                


<p>Search Results: <?php echo $membersList->getMemberCount(); ?></p>
<p><a class = 'boldLink' href="<?php echo FILEPREFIX?>report_printer_friendly.php?societySearch=<?php echo $societySearch ?>&searchFor=<?php echo $searchFor ?>" target="_blank">View Printer Friendly Report </a></p></td>

         

<?php

$membersList->printMembersTable(true);

?>      

</section>

<?php



include("../includes/layout/footer.php");
?>