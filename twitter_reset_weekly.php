<?php
/* Load and clear sessions */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once("./twitter_config.php");
require_once("connection.php");

/*If wrong password, go back.*/
if ($_GET["p"]!="lI73hdf82jA8f"){
	echo "You're not allowed to do that!";
}
else{
	$stmt = $db->prepare("UPDATE Users SET WeeklyPoint=0;");
	$stmt->execute();
	echo "Done!";
}
?>