<?php //INCLUDE THIS FILE TO PRINT STUDENT'S NAME AND SID ON A PAGE WHERE A USER HAS BEEN INSTANTIATED?>

<div class = "studentInfo">
	<h3>Name: </h3>
	<p><?php echo htmlspecialchars_decode($user->getFullName()); ?></p>
</div>
<div class = "studentInfo">
	<h3>Student ID: </h3>
	<p><?php echo htmlspecialchars_decode($user->getSid()); ?></p>
</div>