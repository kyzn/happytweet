<?php
/* Load and clear sessions */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once("./twitter_config.php");
require_once("connection.php");

/*If not logged in, go back.*/
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
	header('Location: ./index.php');
}

$userid=$_SESSION['access_token']['user_id'];
//Get tweets of logged in user which appear at least in 1 set.
$stmt=$db->prepare("SELECT * FROM Tweets WHERE UserID=? AND InSets>0;");
$stmt->execute(array($userid));
$tweetids=array();
while($tweetid=$stmt->fetch(PDO::FETCH_ASSOC)){
	array_push($tweetids,$tweetid['TweetID']);
}

//print_r($tweetids);


$total_vote=0;$total_point=0;

foreach($tweetids as $tweetid){
	for($i=1;$i<=10;$i++){
		$stmt=$db->prepare("SELECT * FROM `Sets` WHERE Tweet".$i."=?");
		$stmt->execute(array($tweetid));
		$numrows = $stmt->rowCount();
		//echo "$tweetid $i $numrows<br>";
		
		$sets = array();

		while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
			array_push($sets,$row['SetID']);
		}

		foreach($sets as $setid){
			$stmt=$db->prepare("SELECT Vote".$i." as vote FROM Plays WHERE SetID=?");
			$stmt->execute(array($setid));
			$numrows = $stmt->rowCount();
			//echo "$numrows many votes<br>";
			
			while($row_vote=$stmt->fetch(PDO::FETCH_ASSOC)){
				$vote=$row_vote['vote'];
				if($vote!=-1){
					$total_point+=(int)$vote;
					$total_vote++;
					//echo "$vote<br>";
				}
			}

		}

	}

}

if($total_vote==0) $average = 0;
$average = $total_point/$total_vote;
$average = round($average,2);
$_SESSION['myscore']=$average;
//echo " total votes $total_vote total points $total_point average $average<br>";

echo "$average_str";

/*for($i=0;$i<10;$i++){

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

header('Location: ./index.php');*/


?>