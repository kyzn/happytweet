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


$stmt = $db->prepare("SELECT WeeklyPoint,TotalPoint FROM Users Where UserID=?");
$stmt->execute(array($_SESSION['access_token']['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalpoint=$row['TotalPoint'];
$weeklypoint=$row['WeeklyPoint'];


$stmt = $db->prepare("SELECT * FROM Plays WHERE UserID=? ORDER BY Plays.PlayedOn;");
$stmt->execute(array($_SESSION['access_token']['user_id']));
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Fri Nov 14 2014 17:56:36 GMT+0000 (UTC) -->
<html data-wf-site="545aa1eaee7d666f2dfa8a5f" data-wf-page="545d1c46d61602d4452b8afe">
<head>
  <meta charset="utf-8">
  <title>happytweet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/happytweet.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
'
</head>
<body>
<?php include("./navigation.php");?>

  <div class="w-container mygames_container">
    <p id= "total_point">My total point: <?php echo $totalpoint;?><br>
    My weekly point: <?php echo $weeklypoint;?></p>
    <a class="share mygames_share_score" href="https://twitter.com/intent/tweet?text=My HappyTweet score is <?php echo $totalpoint;?>! Come join me at happytweet.org&via=happytweetorg&related=happytweetorg">Share My Score</a>
    <!--<a class="share mygames_share_rank" href="#">Share My Rank</a><br>-->
    <table style="width:100%">
	  <tr class="titles">
	    <th>Date Time</th>
	    <th>End Game Point</th>
	    <th>Bonus Point</th>
	    <th>Match Point</th>
	    <th>Total Point</th>
	  </tr>
	  
	  <?php

		   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		   	$endpoint=$row['EndPoint'];
		   	$bonuspoint=$row['BonusPoint'];
		   	$matchpoint=$row['MatchPoint'];
		   	$totalforoneplay=$endpoint+$bonuspoint+$matchpoint;
		   	if($row['MatchWith']==0) $matchpoint="Not yet!";

			   echo "<tr>";
			   echo "<td align=\"center\">".$row['PlayedOn']."</td>";
			   echo "<td align=\"center\">".$endpoint."</td>";
			   echo "<td align=\"center\">".$bonuspoint."</td>";
			   echo "<td align=\"center\">".$matchpoint."</td>";
			   echo "<td align=\"center\">".$totalforoneplay."</td>";
			   echo "</tr>";
			   $index++;
		   }

		?>
   </table>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
