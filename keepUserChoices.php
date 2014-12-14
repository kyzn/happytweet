<?php	
session_start();

	 $index = $_GET['tweetIndex'];
     $_SESSION['emo'.$index] = $_GET['emo'];
	 $_SESSION['str'.$index] = $_GET['str'];
	 $_SESSION['time'.$index] = $_GET['time'];
?>