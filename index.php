<?php
/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitter_config.php');
require_once('connection.php');

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: ./twitter_clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
//TODO Fill the content with useful info.
//$content = $connection->get('account/verify_credentials');
$content=$_SESSION['access_token'];

/* Some example calls */

$connection->get('statuses/user_timeline',array(
	'user_id' => $_SESSION['access_token']['user_id'],
	'trim_user' => 'true',
	'exclude_replies' => 'true',
	'include_rts' => 'false'
	));

//$connection->get('users/show', array('screen_name' => 'abraham'));
//$connection->post('statuses/update', array('status' => date(DATE_RFC822)));
//$connection->post('statuses/destroy', array('id' => 5437877770));
//$connection->post('friendships/create', array('id' => 9436992));
//$connection->post('friendships/destroy', array('id' => 9436992));

/* Include HTML to display on the page */
include('html.inc');
