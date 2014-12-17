<?php
/* Load and clear sessions */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once("./twitter_config.php");
require_once("connection.php");

/*If wrong password, go back.*/
$pass="lI73hdf82jA8f";
if($argv[1]==$pass || $_GET["p"]==$pass){

	for($i=0;$i<10;$i++){

		$stmt = $db->prepare("SELECT TweetID FROM (SELECT * FROM `Tweets` WHERE 1 ORDER BY InSets ASC LIMIT 100) lownumbers ORDER BY RAND() LIMIT 10");
		$stmt->execute();
		
		$tweetids=array();

		while($tweetid=$stmt->fetch(PDO::FETCH_ASSOC)){
			array_push($tweetids,$tweetid['TweetID']);
		}

		$stmt = $db->prepare("INSERT INTO Sets (Tweet1, Tweet2, Tweet3, Tweet4, Tweet5, Tweet6, Tweet7, Tweet8, Tweet9, Tweet10, CreatedOn) VALUES (?,?,?,?,?,?,?,?,?,?,NOW());");
		$stmt->execute($tweetids);


		$stmt = $db->prepare("UPDATE Tweets SET InSets=InSets+1 WHERE ((TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?) OR (TweetID=?));");
		$stmt->execute($tweetids);

	}

	header('Location: ./index.php');

}else{
	echo "You're not allowed to do that!";
}

?>