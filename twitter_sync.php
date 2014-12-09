<?php
//THIS IS NOT WORKING YET.

/* Load and clear sessions */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once("./twitter_config.php");
require_once("connection.php");

/*If not logged in, go back.*/
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['user_id'])) {
    header('Location: ./twitter_clearsessions.php');
}

/*Else, get the last sync time.*/
$stmt = $db->prepare("SELECT (SELECT LastSync FROM `Users` WHERE UserID= ?) < 
	( SELECT NOW() - INTERVAL 1 DAY) AS DayPassed;");
$stmt->execute(array($_SESSION['access_token']['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$DayPassedAfterLastSync = $row['DayPassed'];

/*If last sync was in last 24 hrs, do not sync. Go back.*/
if($DayPassedAfterLastSync == 0) 
	//Maybe we can put this message in a POST, then show it on index.
	echo "Your last sync was today! Come again tomorrow.";
	header('Location: ./index.php');
	

//Get the id of last synced tweet
$stmt=$db->prepare("SELECT LastSyncID FROM `Users` WHERE UserID=?");
$stmt->execute(array($_SESSION['access_token']['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$LastSyncID=$row['LastSyncID'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/*get timeline*/

if($LastSyncID) //Not the first sync, use last sync id.
	$getarray=array('user_id' => $_SESSION['access_token']['user_id'],
	'trim_user' => 'true',
	'exclude_replies' => 'true',
	'include_rts' => 'false',
	'since_id' => $LastSyncID);
else //First sync ever!
	$getarray=array('user_id' => $_SESSION['access_token']['user_id'],
	'trim_user' => 'true',
	'exclude_replies' => 'true',
	'include_rts' => 'false');

$content=$connection->get('statuses/user_timeline',$getarray);



$LastSyncIDNotUpdated=1;
date_default_timezone_set('Europe/Istanbul');

foreach($content as $tweet)
{
  if($LastSyncIDNotUpdated){
	$LastSyncID=$tweet->id;
	$LastSyncIDNotUpdated=0;
  }
  if($tweet->lang == "en"){ //Get only English tweets!
  	//Convert Twitter time to MySQL time:
  	$mysqlDate = date_format(date_create_from_format('D M d H:i:s T Y', $tweet->created_at), 'Y-m-d H:i:s');

  	//Insert into tweets table

  	$stmt=$db->prepare("INSERT INTO Tweets VALUES (?,?,?,?,NOW())");
  	//TweetID, UserID, TweetText, TweetTime, CreatedOn
$stmt->execute(array(
	$tweet->id,
	$_SESSION['access_token']['user_id'],
	$tweet->text,
	$mysqlDate));

echo "$newDate {$tweet->lang} {$tweet->id} {$tweet->text}\n";
  }

}

//update user's last sync id & time

$stmt=$db->prepare("UPDATE `Users` SET `LastSync`=NOW(),`LastSyncID`=? WHERE `UserID`=?;");
$stmt->execute(array(
	$LastSyncID,
	$_SESSION['access_token']['user_id']));


/* Redirect */
//Maybe we can put this message in a POST, then show it on index.
echo "Synced!";
header('Location: ./index.php');