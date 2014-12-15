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

//Prepare the set here.

//First ten sets are tutorial sets, and one will be randomly picked.
$stmt = $db->prepare("SELECT SetID FROM Sets WHERE SetID<11 ORDER BY rand() LIMIT 1;");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$setid = $row['SetID'];

$stmt = $db->prepare("SELECT Tweet1, Tweet2, Tweet3, Tweet4, Tweet5, Tweet6, Tweet7, Tweet8, Tweet9, Tweet10 FROM Sets WHERE SetID=1");
$stmt->execute(array($setid));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$tweetid[0]=$row['Tweet1'];
$tweetid[1]=$row['Tweet2'];
$tweetid[2]=$row['Tweet3'];
$tweetid[3]=$row['Tweet4'];
$tweetid[4]=$row['Tweet5'];
$tweetid[5]=$row['Tweet6'];
$tweetid[6]=$row['Tweet7'];
$tweetid[7]=$row['Tweet8'];
$tweetid[8]=$row['Tweet9'];
$tweetid[9]=$row['Tweet10'];


for($i=0;$i<10;$i++){
	$stmt = $db->prepare("SELECT TweetText FROM Tweets WHERE TweetID=?");
	$stmt->execute(array($tweetid[$i]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$tweettext[$i]=$row['TweetText'];
}
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
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>

</head>
<body class="w-clearfix">
<?php include("./navigation.php");?>

  <div class="w-container play_container">
  
    <!--counter-->
   	<div class="play_countdown">
		<p id="play_paragraph"></p>
	</div>
	
	<!--new tweet will be in here-->
	<div class="play_paragraph">
		<p id="paragraph"></p>
	</div>
  

	<p id="rating_p">Which sentiment this tweet mostly has?</p>

	<nav>
		<ul id="sentiment">	
			<li><a id="rating1" class="tabRating" onclick="setEmotion('1', this)">Extremely Negative</a></li>
			<li><a id="rating2" class="tabRating" onclick="setEmotion('2', this)">Negative</a></li>
			<li><a id="rating3" class="tabRating" onclick="setEmotion('3', this)">Neutral</a></li>
			<li><a id="rating4" class="tabRating" onclick="setEmotion('4', this)">Positive</a></li>
			<li><a id="rating5" class="tabRating" onclick="setEmotion('5', this)">Extremely Positive</a></li>
		</ul>

	</nav>

	<div id="wordSelection">
		<p id="selection_p">Click on the word which made you give that point!</p>
		
		<!--clikable words of tweets will be created in this div-->
		<div id="tweetWords"></div>
	</div>
	<!-- tweets that we get from database will be sent to the javascript-->	
	 <script type="text/javascript">	
		var ar = <?php echo json_encode($tweettext) ?>;
		getTweets(ar);
	 </script>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
