<?php
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitter_config.php');
require_once('connection.php');

$loggedin=true;

if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    $loggedin=false;
}

//Go away if not logged in.
if(!$loggedin){ header('Location: ./index.php');}
?>

<!DOCTYPE html>

<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Fri Nov 14 2014 17:56:36 GMT+0000 (UTC) -->
<html data-wf-site="545aa1eaee7d666f2dfa8a5f" data-wf-page="545aaf0fd1e6ae0320894dae">
<head>
  <meta charset="utf-8">
  <title>happytweet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/happytweet.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script type="text/javascript" src="js/tab.js"></script>
  <script type="text/javascript" src="js/countdown.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>
<body class="w-clearfix">
<?php include("./navigation.php");?>

  <div class="w-container play_container">
   	<div class="play_countdown">
		<p id="play_paragraph"></p>
	</div>
	
	<hr>	<nav>
			<ul>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this)">1</a></li>
				<li><a id="2" class="tabQuestionNumber" onclick="selectQuestion(this)">2</a></li>
				<li><a id="3" class="tabQuestionNumber" onclick="selectQuestion(this)">3</a></li>
				<li><a id="4" class="tabQuestionNumber" onclick="selectQuestion(this)">4</a></li>
				<li><a id="5" class="tabQuestionNumber" onclick="selectQuestion(this)">5</a></li>
				<li><a id="6" class="tabQuestionNumber" onclick="selectQuestion(this)">6</a></li>
				<li><a id="7" class="tabQuestionNumber" onclick="selectQuestion(this)">7</a></li>
				<li><a id="8" class="tabQuestionNumber" onclick="selectQuestion(this)">8</a></li>
				<li><a id="9" class="tabQuestionNumber" onclick="selectQuestion(this)">9</a></li>
				<li><a id="10" class="tabQuestionNumber" onclick="selectQuestion(this)">10</a></li>
			</ul>
			<hr>	
			<div class="play_paragraph">
				<p id="paragraph">My tweet</p>
			</div>
		</nav>

	<p id="rating_p">Which sentiment this tweet mostly has?</p>

	<nav>
		<ul id="sentiment">	
			<li><a id="rating1" class="tabRating" onclick="selectRating(this)">Extremely Negative</a></li>
			<li><a id="rating2" class="tabRating" onclick="selectRating(this)">Negative</a></li>
			<li><a id="rating3" class="tabRating" onclick="selectRating(this)">Neutral</a></li>
			<li><a id="rating4" class="tabRating" onclick="selectRating(this)">Positive</a></li>
			<li><a id="rating5" class="tabRating" onclick="selectRating(this)">Extremely Positive</a></li>
		</ul>

	</nav>

	<p id="selection_p">Click on the word which made you give that point!</p>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
