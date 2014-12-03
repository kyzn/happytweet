<?php
//THIS IS NOT WORKING YET.

/**
 * @file
 * Clears PHP sessions and redirects to the connect page.
 */
 
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
	header('Location: ./index.php');

$stmt=$db->prepare("SELECT LastSyncID FROM `Users` WHERE UserID=?");
$stmt->execute(array($_SESSION['access_token']['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$LastSyncID=$row['LastSyncID'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/*get timeline*/
$content=$connection->get('statuses/user_timeline',array(
	'user_id' => $_SESSION['access_token']['user_id'],
	'trim_user' => 'true',
	'exclude_replies' => 'true',
	'include_rts' => 'false',
	'since_id' => $LastSyncID
	));

print_r ($content);



/* Redirect to post page. */
//header('Location: ./sync_post.php');
