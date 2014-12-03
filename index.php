<?php
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitter_config.php');
require_once('connection.php');

$loggedin=true;

if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    $loggedin=false;
}

?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Fri Nov 14 2014 17:56:36 GMT+0000 (UTC) -->
<html data-wf-site="545aa1eaee7d666f2dfa8a5f" data-wf-page="545aa1eaee7d666f2dfa8a60">
<head>
  <meta charset="utf-8">
  <title>happytweet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/happytweet.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>
<body>
    <?php if($loggedin){ include("./navigation.php"); }?>

  <div class="w-container main">
    


    <?php if($loggedin){ ?> 
<p class="style"><span>Start playing instantly by clicking play above.</span><br><br></p>

<?php }else{ ?> 
<p class="style"><span>HappyTweet is a multiplayer game, that lets you rate tweets according to their sentimental values. You can play against your friends, earn many achievements and have lots of fun!<br xmlns="http://www.w3.org/1999/xhtml">Even if you are not registered, you can still give it a try!&nbsp;</span>
    </p>

    <a class="button login" href="./twitter_redirect.php">Login with Twitter</a>
    <a class="button try" href="./tutorial.php">Give it a try!</a>
    <a class="button learn" href="./about.php"><strong>Learn More!</strong></a>


<?php } ?>

  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->


<?php


/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    //header('Location: ./twitter_clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];


/* If method is set change API call made. Test is called by default. */
//TODO Fill the content with useful info.
//$content = $connection->get('account/verify_credentials');
$content=$_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/*get timeline*/
/*$content=$connection->get('statuses/user_timeline',array(
  'user_id' => $_SESSION['access_token']['user_id'],
  'trim_user' => 'true',
  'exclude_replies' => 'true',
  'include_rts' => 'false',
  'since_id' => '99999'
  ));*/


//$content=$connection->get('users/show', array('screen_name' => 'kyzn'));

/* Include HTML to display on the page */
        print_r($content); ?>




</body>
</html>
