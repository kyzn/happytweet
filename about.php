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
<!-- Last Published: Fri Nov 14 2014 17:56:35 GMT+0000 (UTC) -->
<html data-wf-site="545aa1eaee7d666f2dfa8a5f" data-wf-page="545aa64ad1e6ae0320894d10">
<head>
  <meta charset="utf-8">
  <title>happytweet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/happytweet.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>

</head>
<body>
    <?php if($loggedin){ include("./navigation.php"); }?>

  <div class="w-container about_container">
    <p class="style">This project is created as a part of social semantic web course at Bogazici University, Turkey. It aims to analyze sentiment of tweets through crowdsourcing and let users have fun at the same time.&nbsp;Users can rate a tweet according to its sentiment and specify which word mostly make them think in that way. Users choices have to match with their opponent's to get the highest school.</p>

    <?php if(!$loggedin){?>
    <a class="button about_try" href="tutorial.php">Give it a try!</a>
    <?php }else{?>
    <a class="button play" href="tutorial.php">Play now!</a>
    <?php } ?>
    <a class="button learn" href="./index.php"><strong>Home</strong></a>


  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
