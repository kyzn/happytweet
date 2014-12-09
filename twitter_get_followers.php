<?php
/* Load and clear sessions */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once("./twitter_config.php");
require_once("connection.php");

/*If not logged in, go back.*/
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['user_id'])) {
    header('Location: ./twitter_clearsessions.php');
}

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/*get timeline*/

$getarray=array('user_id' => $_SESSION['access_token']['user_id']);
$content=$connection->get('followers/ids',$getarray);


foreach($content->ids as $follower)
{

	$stmt=$db->prepare("INSERT INTO Followers VALUES (?,?);");
	//FollowerID, FollowedID
	$stmt->execute(array($follower,$_SESSION['access_token']['user_id']));

}
?>