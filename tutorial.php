<?php
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitter_config.php');
require_once('connection.php');

$loggedin=true;

if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    $loggedin=false;
}


//Prepare the set here.

//First ten sets are tutorial sets, and one will be randomly picked.
$stmt = $db->prepare("SELECT SetID FROM Sets WHERE SetID<11 ORDER BY rand() LIMIT 1;");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$setid = $row['SetID'];

$stmt = $db->prepare("SELECT Tweet1, Tweet2, Tweet3, Tweet4, Tweet5, Tweet6, Tweet7, Tweet8, Tweet9, Tweet10 FROM Sets WHERE SetID=?");
$stmt->execute(array($setid));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$tweetid = array();
$tweettext = array();

array_push($tweetid,$row['Tweet1']);
array_push($tweetid,$row['Tweet2']);
array_push($tweetid,$row['Tweet3']);
array_push($tweetid,$row['Tweet4']);
array_push($tweetid,$row['Tweet5']);
array_push($tweetid,$row['Tweet6']);
array_push($tweetid,$row['Tweet7']);
array_push($tweetid,$row['Tweet8']);
array_push($tweetid,$row['Tweet9']);
array_push($tweetid,$row['Tweet10']);

for($i=1;$i<11;$i++){
	$stmt = $db->prepare("SELECT TweetText FROM Tweets WHERE TweetID=?");
	$stmt->execute(array($tweetid[$i]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	array_push($tweettext,$row['TweetText']);
}
//got the tweets











?><script type="text/javascript">
var seconds = 59;

function secondPassed() {
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('countdown').innerHTML = remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = "";
        document.getElementById('tweet_display').innerHTML = "Time's up!";

    } else {    
        seconds--;
    }
}
var countdownTimer = setInterval(secondPassed, 1000);

var tweetNumber=1;

function pointGiven(){
	 if(tweetNumber!=10){
    tweetNumber++;
    tweetText = "tweet"+tweetNumber;//<?php echo json_encode(array_shift($tweettext));?>;
    document.getElementById("tweet_display").innerHTML =  tweetText;
   }else{
    document.getElementById("tweet_display").innerHTML = "Completed! Here's the results:";
    clearInterval(countdownTimer);
    document.getElementById('countdown').innerHTML = "";
    document.getElementById('pointButtons').style.visibility = "hidden";
   }
}




</script><?

//Go away if logged in. Tutorial is only for logged out users.
if($loggedin){ header('Location: ./index.php');}
?>

<!DOCTYPE html>

<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Fri Nov 14 2014 17:56:36 GMT+0000 (UTC) -->
<html data-wf-site="545aa1eaee7d666f2dfa8a5f" data-wf-page="545aaf0fd1e6ae0320894dae">
<head>
  <meta charset="utf-16">
  <title>happytweet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/happytweet.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <script type="text/javascript" src="js/tab.js"></script>
  <!--<script type="text/javascript" src="js/countdown.js"></script>-->

</head>
<body class="w-clearfix">


  <div class="w-container main">
  <p class="style">
  <span id="countdown" class="timer" style="font-size:30px"></span>
  <br><br>
  <span id="tweet_display" style="font-size:20px"><? echo array_shift($tweettext); ?></span>
  <br><br>
  <div id="pointButtons" align="center" style="visibility:visible;" >
  <button onclick="pointGiven()" id="point1" style="font-size:13px">Extremely Negative</button>
	<button onclick="pointGiven()" id="point2" style="font-size:13px">Negative</button>
	<button onclick="pointGiven()" id="point3" style="font-size:13px">Neutral</button>
	<button onclick="pointGiven()" id="point4" style="font-size:13px">Positive</button>
	<button onclick="pointGiven()" id="point5" style="font-size:13px">Extremely Positive</button>
</div>
  </span>


   	
	</div>
	






	<!--
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
  </div>-->
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
