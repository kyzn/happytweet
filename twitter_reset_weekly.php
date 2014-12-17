<?php
/* Load and clear sessions */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once("./twitter_config.php");
require_once("connection.php");

/*If wrong password, go back.*/
$pass="lI73hdf82jA8f";

if($argv[1]==$pass || $_GET["p"]==$pass){
	$stmt = $db->prepare("UPDATE Users SET WeeklyPoint=0;");
	$stmt->execute();
	echo "Done!";
}else{
	echo "You're not allowed to do that!";	
}

?>