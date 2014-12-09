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

$stmt = $db->prepare("SELECT Tweet1, Tweet2, Tweet3, Tweet4, Tweet5, Tweet6, Tweet7, Tweet8, Tweet9, Tweet10 FROM Sets WHERE SetID=1");
$stmt->execute(array($setid));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$tweetid[1]=$row['Tweet1'];
$tweetid[2]=$row['Tweet2'];
$tweetid[3]=$row['Tweet3'];
$tweetid[4]=$row['Tweet4'];
$tweetid[5]=$row['Tweet5'];
$tweetid[6]=$row['Tweet6'];
$tweetid[7]=$row['Tweet7'];
$tweetid[8]=$row['Tweet8'];
$tweetid[9]=$row['Tweet9'];
$tweetid[10]=$row['Tweet10'];


for($i=1;$i<11;$i++){
	$stmt = $db->prepare("SELECT TweetText FROM Tweets WHERE TweetID=?");
	$stmt->execute(array($tweetid[$i]));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$tweettext[$i]=$row['TweetText'];
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
        document.getElementById('countdown').innerHTML = "Time's up! Try again.";
        document.getElementById("tweet1").style.display = 'none';
        document.getElementById("tweet2").style.display = 'none';
        document.getElementById("tweet3").style.display = 'none';
        document.getElementById("tweet4").style.display = 'none';
        document.getElementById("tweet5").style.display = 'none';
        document.getElementById("tweet6").style.display = 'none';
        document.getElementById("tweet7").style.display = 'none';
        document.getElementById("tweet8").style.display = 'none';
        document.getElementById("tweet9").style.display = 'none';
        document.getElementById("tweet10").style.display = 'none';

    } else {    
        seconds--;
    }
}
var countdownTimer = setInterval(secondPassed, 1000);


function pointGiven(tweetnumber,point){
	 document.getElementById("tweetnumber").style.display = 'none';
	if($tweetnumber<11)
		document.getElementById("tweetnumber").style.display = 'block';
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
'
</head>
<body class="w-clearfix">


  <div class="w-container main">
  <p class="style">
  <span id="countdown" class="timer" style="font-size:30px"></span>
  <?php $tweet=1; ?><br>
  
  <!--<nav>
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
				<p id="paragraph"></p>
				  <br><label style="display:block;" id="tweet1" style="font-size:20px"><?php echo "$tweettext[1]"; ?></label>
				  <br><label style="display:none;" id="tweet2" style="font-size:20px"><?php echo "$tweettext[2]"; ?></label>
				  <br><label style="display:none;" id="tweet3" style="font-size:20px"><?php echo "$tweettext[3]"; ?></label>
				  <br><label style="display:none;" id="tweet4" style="font-size:20px"><?php echo "$tweettext[4]"; ?></label>
				  <br><label style="display:none;" id="tweet5" style="font-size:20px"><?php echo "$tweettext[5]"; ?></label>
				  <br><label style="display:none;" id="tweet6" style="font-size:20px"><?php echo "$tweettext[6]"; ?></label>
				  <br><label style="display:none;" id="tweet7" style="font-size:20px"><?php echo "$tweettext[7]"; ?></label>
				  <br><label style="display:none;" id="tweet8" style="font-size:20px"><?php echo "$tweettext[8]"; ?></label>
				  <br><label style="display:none;" id="tweet9" style="font-size:20px"><?php echo "$tweettext[9]"; ?></label>
				  <br><label style="display:none;" id="tweet10" style="font-size:20px"><?php echo "$tweettext[10]"; ?></label>
			</div>
		</nav>
		

	<button onclick="pointGiven(1,1)" id="point1" style="font-size:13px">Extremely Negative</button>
	<button onclick="pointGiven(<?php echo $tweet;?>,2)" id="point2" style="font-size:13px">Negative</button>
	<button onclick="pointGiven(<?php echo $tweet;?>,3)" id="point3" style="font-size:13px">Neutral</button>
	<button onclick="pointGiven(<?php echo $tweet;?>,4)" id="point4" style="font-size:13px">Positive</button>
	<button onclick="pointGiven(<?php echo $tweet;?>,5)" id="point5" style="font-size:13px">Extremely Positive</button>

  </span>


 </div>-->
	



	
 	<hr>	<nav>
			<ul>
			
			
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[1]);?>')">1</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[2]);?>')">2</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[3]);?>')">3</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[4]);?>')">4</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[5]);?>')">5</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[6]);?>')">6</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[7]);?>')">7</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[8]);?>')">8</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[9]);?>')">9</a></li>
				<li><a id="1" class="tabQuestionNumber" onclick="selectQuestion(this, '<?php echo addslashes($tweettext[10]);?>')">10</a></li>
			</ul>
			<hr>	
			<div class="play_paragraph">
				<p id="paragraph"></p>
			</div>
		</nav>

 </span>
	</div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
  
</body>
</html>
