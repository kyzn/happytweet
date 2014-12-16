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
//DEBUG
echo "user ".$_SESSION['access_token']['user_id']." ";
//Prepare the set here:

//First method: In the plays that are waiting for a match, bring random one of them that I did not play before.
$stmt = $db->prepare("SELECT P1.UserID,P1.SetID FROM Plays AS P1
WHERE P1.UserID!= ?
AND P1.MatchWith= 0
AND NOT EXISTS (
	SELECT P2.SetID FROM Plays AS P2
	WHERE P2.UserID = ?
	AND P1.SetID = P2.SetID)
ORDER BY rand() LIMIT 1;");

$stmt->execute(array($_SESSION['access_token']['user_id'],$_SESSION['access_token']['user_id']));
$numrows = $stmt->rowCount();

if($numrows == 0){ //No such set! Gonna try the second method.

	//Second method: In the all sets, bring one I did not solve yet.
	$stmt = $db->prepare("SELECT S1.SetID FROM Sets AS S1
	WHERE NOT EXISTS(
		SELECT P1.SetID FROM Plays AS P1
		WHERE P1.SetID = S1.SetID
		AND P1.UserID = ?)
	ORDER BY rand() LIMIT 1;");
	$stmt->execute(array($_SESSION['access_token']['user_id']));
	$numrows = $stmt->rowCount();

	if($numrows == 0){
		//Seems like user played all sets already..
		header('Location: ./index.php');
		//TODO: This will be replaced by set creation
	}else{
	//Second method worked.. 
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$setid = $row['SetID'];
	$_SESSION['setid']=$setid;
	//DEBUG
	echo "method 2 set $setid ";

	//Insert the game about to start to the database.
	$stmt = $db->prepare("INSERT INTO Plays (UserID, SetID, PlayedOn) VALUES (?,?, NOW());");	
	$stmt->execute(array($_SESSION['access_token']['user_id'],$setid));
	}

}else{
	//DEBUG
	echo "method 1 ";
	//First method worked! Continue with this set.
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$setid = $row['SetID'];
	$_SESSION['setid']=$setid;
	$matchid = $row['UserID'];
	$_SESSION['matchid']=$matchid;
	//DEBUG
	echo "set $setid matchwith $matchid ";

	//Update the matched row, ie. mark as "matched" so that noone else matches that.
	$stmt = $db->prepare("UPDATE Plays SET MatchWith=? WHERE UserID=? AND SetID=?;");
	$stmt->execute(array($_SESSION['access_token']['user_id'],$matchid,$setid));
	//Insert the game about to start to the database.
	$stmt = $db->prepare("INSERT INTO Plays (UserID, MatchWith, SetID, PlayedOn) VALUES (?,?,?,NOW())");
	$stmt->execute(array($_SESSION['access_token']['user_id'],$matchid,$setid));
}

//We got the set.


$stmt = $db->prepare("SELECT Tweet1, Tweet2, Tweet3, Tweet4, Tweet5, Tweet6, Tweet7, Tweet8, Tweet9, Tweet10 FROM Sets WHERE SetID=?");
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
		<p id="play_paragraph"></p><br><br>
	</div>
	
	<!--new tweet will be in here-->

	<div class="play_paragraph" style="text-align:center;">
		<p id="paragraph" style="text-align:center;"></p>
	</div>
  
	<p id="rating_p"><!--Which sentiment this tweet mostly has?--></p>

	<nav>
		<ul id="sentiment">	
			<li><a id="rating1" class="tabRating" onclick="setEmotion('1', this)">Extremely Negative</a></li>
			<li><a id="rating2" class="tabRating" onclick="setEmotion('2', this)">Negative</a></li>
			<li><a id="rating3" class="tabRating" onclick="setEmotion('3', this)">Neutral</a></li>
			<li><a id="rating4" class="tabRating" onclick="setEmotion('4', this)">Positive</a></li>
			<li><a id="rating5" class="tabRating" onclick="setEmotion('5', this)">Extremely Positive</a></li>
		</ul>

	</nav>

	<div id="wordSelection" style="text-align:center;">
		<p id="selection_p"><!--Click on the word which made you give that point!--></p>
		
		<!--clikable words of tweets will be created in this div-->
		<div id="tweetWords"></div>
		<br><br>
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
