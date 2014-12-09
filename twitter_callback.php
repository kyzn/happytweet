<?php
/* Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitter_config.php');
require_once('connection.php');


/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && 
	$_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./twitter_clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;


$errmsg_arr = array ();
$errflag = false;

/* Check if user registered before */
$stmt = $db->prepare("SELECT * FROM Users WHERE UserID=?");
$stmt->execute(array(
	$_SESSION['access_token']['user_id']
	));
$numrows = $stmt->rowCount();

$firsttime=0;

if($numrows == 0){ //No such user, add to database and proceed.
	$firsttime=1;
	$stmt = $db->prepare("INSERT INTO `Users` VALUES (?,?,?,?,0,0,NOW(),'0000-00-00','0');");
	$stmt->execute(array(
		$_SESSION['access_token']['user_id'],
		$_SESSION['access_token']['oauth_token'],
		$_SESSION['access_token']['oauth_token_secret'],
		$_SESSION['access_token']['screen_name']
		));
}else{ //User exists. Check if auths are correct.
	$firsttime=0;
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$truetoken = $row['AuthToken'];
	$truesecret = $row['AuthSecret'];
	if($truetoken != $_SESSION['access_token']['oauth_token'] ||
		$truesecret != $_SESSION['access_token']['oauth_token_secret']){
		//Token or secret doesn't match the one on the database.. Don't proceed.
		unset($_SESSION['access_token']);
		header('Location: ./twitter_clearsessions.php');
	}

}
/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  if($firsttime) header('Location: ./twitter_sync.php');
  else header('Location: ./index.php');
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  header('Location: ./twitter_clearsessions.php');
}
?>
