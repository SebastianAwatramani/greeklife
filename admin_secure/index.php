
<?php
// Double check the description of the offline messages section
require("../includes/meta.php");
require("../includes/layout/header.php");
require("../includes/Database.php");
require("../includes/FormStatus.php");
require("../includes/FormStatusSwitcher.php");
require("../includes/createDatabase.php");
//This switches form status between online and offline in the case that the user intitiated a switch from the table on this page
if(isset($_GET['formName'])) {
	$formStatusSwitcher = new FormStatusSwitcher($db);
	$formStatusSwitcher->switchFormStatus();
}

//Get the form status for display in the table below
$formName = SOCIETY . "_" . "members";
$memberStatus = new FormStatus($formName, $db);
unset($formName);

$formName =  SOCIETY . "_" . "recruits";
$recruitStatus = new FormStatus($formName, $db);

?>
<script type="text/javascript" src="https://cdn.rawgit.com/zenorocha/clipboard.js/v1.5.16/dist/clipboard.min.js"></script>
<script type="text/javascript">

//This will copy the members/recruits href to the clipboard so that admins can easily send it to students e.g. in an email

var memberClipboard = new Clipboard('#memberCopyLink');
var recruitClipboard = new Clipboard('#recruitCopyLink');

	//Show div informing user that href was copied to the clipboard
function showToolTip() {
  	var tooltip = document.createElement('div');
    tooltip.id = "tooltip";

    var tooltipP = document.createElement('p');
    tooltipP.innerHTML = 'Copied to Clipboard';
    tooltip.appendChild(tooltipP);
    document.body.appendChild(tooltip);

		    //Fades the copy confirmed div out
	function fadeOut() {
		var opacity = window.getComputedStyle(tooltip).getPropertyValue("opacity");

		if(opacity > 0) {
			tooltip.style.opacity = opacity - .01;
		} else {
			window.clearInterval(timer);
		}
	}
	var timer = setInterval(fadeOut, 50);
}

memberClipboard.on('success', showToolTip);
recruitClipboard.on('success', showToolTip);
</script>
<section id="main">
<?php include("../includes/adminSectionHeader.php"); ?>
	<div class="linkList">
		<h3>Form Submission Reporting and Removal Options</h3>
		<ul>
			<li class = 'adminLinks'><a href="sorority_member_grade_release_admin.php">Members</a></li>
			<li class = 'adminLinks'><a href="sorority_recruit_grade_release_admin.php">Recruits</a></li>
		</ul>
	</div>
	<div class="linkList">
		<h3>Links to forms for students (click to copy url)</h3>
		<ul>
			<li class = 'adminLinks clickToCopy' id = 'memberCopyLink' data-clipboard-text  = 'http://registrar.colorado.edu/<?php echo ROOT; ?>/members.php'>
				Members
			</li>
			<li class = 'adminLinks clickToCopy' id = 'recruitCopyLink' data-clipboard-text  = 'http://registrar.colorado.edu/<?php echo ROOT; ?>/recruits.php'>
			Recruits
			</li>
		</ul>
	</div>
	<section class = 'linkList'>
		<h3>Form Availablity:</h3>
		<table id="formAvailability">
			<tr>
				<th>Sorority Members</th>
				<td><?php if($memberStatus->getFormStatus() === "online"){ echo "Online";} else { echo "Offline"; } ?></td>
				<td><a href="index.php?formName=<?php echo SOCIETY . htmlspecialchars("_"); ?>members&formStatus=<?php if($memberStatus->getFormStatus() === "online"){ echo "offline";} else { echo "online"; } ?>">Change to <?php if($memberStatus->getFormStatus() === "online"){ echo "offline";} else { echo "Online"; } ?></a></td>
			</tr>
			<tr>
				<th>Sorority Recruits</th>
				<td><?php if($recruitStatus->getFormStatus() === "online"){ echo "Online";} else { echo "Offline"; } ?></td>
				<td><a href="index.php?formName=<?php echo SOCIETY . htmlspecialchars("_"); ?>recruits&formStatus=<?php if($recruitStatus->getFormStatus() === "online"){ echo "offline";} else { echo "online"; } ?>">Change to <?php if($recruitStatus->getFormStatus() === "online"){ echo "offline";} else { echo "Online"; } ?></a></td>
			</tr>
		</table>
	</section>
</section>
<?php require("../includes/layout/footer.php"); ?>

