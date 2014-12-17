<?php
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('twitter_config.php');
require_once('connection.php');

$loggedin=true;

if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    $loggedin=false;
}

//Go away if logged in.
if($loggedin){ header('Location: ./index.php');}
	
?>

<!DOCTYPE html>
<!-- This site was created in Webflow. http://www.webflow.com-->
<!-- Last Published: Fri Nov 14 2014 17:56:36 GMT+0000 (UTC) -->
<html data-wf-site="545aa1eaee7d666f2dfa8a5f" data-wf-page="545d20d8038a60b221209a69">
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


  <div class="w-container result_container">
  
	<?php

		//emo1..10 str1..10 time10 elimde.

		//a) emo1..10'u Plays userid setid verip kaydet.
		//no userid for tutorial
		//$userid = $_SESSION['access_token']['user_id'];

		$setid = $_SESSION['setid'];
		unset($_SESSION['setid']);

		/*if(isset($_SESSION['matchid'])){
			$matchid=$_SESSION['matchid']; 
			unset($_SESSION['matchid']);
		}else{
			$matchid=-1;
		}*/

		//DEBUG
//		echo "set $setid ";
		$emo = $_SESSION['emo'];
		$str = $_SESSION['str'];
		$time = $_SESSION['time'][10];
		list($min,$sec) =explode(" : ",$time);
		$time=60*$min+$sec;

		unset($_SESSION['emo']);
		unset($_SESSION['str']);
		unset($_SESSION['time']);

		//DEBUG
//		echo " scores ";
		//DEBUG
//		for ($x = 1; $x <= 10; $x++) echo $emo[$x].' ';
		//DEBUG
		//echo " words ";
		//DEBUG
//		for ($x = 1; $x <= 10; $x++) echo $str[$x].' ';


		//c) geriye çektiğim word idlerini play tablosunda wordlere ekle

		$wordids=array();

		for($i=1;$i<=10;$i++){
			$stmt = $db->prepare("SELECT WordID FROM Words WHERE WordText = ?");
			$stmt->execute(array($str[$i]));
			$numrows = $stmt->rowCount();
			if($numrows==0) $wordids[$i]=0;
			else{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$wordids[$i]=$row['WordID'];	
			} 
		}


		//DEBUG
//		echo "wordids ";
		//DEBUG
//		for ($x = 1; $x <= 10; $x++) echo $wordids[$x]." ";


		$commonvotes=array();

		$stmt=$db->prepare("SELECT ROUND(AVG(Vote1)) AS Vote1, ROUND(AVG(Vote2)) AS Vote2, ROUND(AVG(Vote3)) AS Vote3, ROUND(AVG(Vote4)) AS Vote4, ROUND(AVG(Vote5)) AS Vote5, ROUND(AVG(Vote6)) AS Vote6, ROUND(AVG(Vote7)) AS Vote7, ROUND(AVG(Vote8)) AS Vote8, ROUND(AVG(Vote9)) AS Vote9, ROUND(AVG(Vote10)) AS Vote10, COUNT(*),SetID FROM Plays WHERE SetID = ".$setid." AND Vote1!=-1 AND Vote2!=-1 AND Vote3!=-1 AND Vote4!=-1 AND Vote5!=-1 AND Vote6!=-1 AND Vote7!=-1 AND Vote8!=-1 AND Vote9!=-1 AND Vote10!=-1;");
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		for($i=1;$i<=10;$i++){
			$commonvotes[$i]=$row['Vote'.$i];

		}

		$commonwordids=array();

		for($i=1;$i<=10;$i++){
			
			$stmt=$db->prepare("SELECT Word".$i.", COUNT(*) AS magnitude FROM Plays WHERE SetID=".$setid." GROUP BY Word".$i." ORDER BY magnitude DESC LIMIT 1;");
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			$commonwordids[$i]=$row['Word'.$i];

		}

		//DEBUG
//		echo "commonvotes ";
		//DEBUG
//		for ($x = 1; $x <= 10; $x++) echo $commonvotes[$x]." ";

		//DEBUG
//		echo "commonwordids ";
		//DEBUG
//		for ($x = 1; $x <= 10; $x++) echo $commonwordids[$x]." ";


		$matchpoint=0;
	
		//DEBUG
//		echo "match votes ";

		for ($x = 1; $x <= 10; $x++) {

			$emo_diff=abs($emo[$x] - $commonvotes[$x]);
			
			if($emo_diff==0){
				$matchpoint+=3;
				//DEBUG
//				echo "+3 ";
			}else if($emo_diff==1){
				$matchpoint+=1;
				//DEBUG
//				echo "+1 ";
			}else{
				//DEBUG
//				echo "+0 ";
			}

		}
		
		//DEBUG
//		echo "match words ";
		
		for($x=1;$x<=10;$x++){
			
			if($emo[$x]==3){ //no word match possible for neutral votes
				//DEBUG
//				echo "na ";
			}else{
				if($wordids[$x]==$commonwordids[$x]){
					//DEBUG
//					echo "+5 ";
					$matchpoint+=5;
				}else{
					//DEBUG
//					echo "+0";
				}
			}
		}

		//DEBUG
//		echo " matchpoint $matchpoint ";


		
		$bonuspoint = 0; //TODO: Set up bonus mechanism
		$endpoint = $time *2;
		$totalpoint = $bonuspoint+$endpoint+$matchpoint;
		
		//DEBUG
//		echo " time $time endpoint $endpoint bonuspoint $bonuspoint totalpoint $totalpoint ";


		echo "<center><br>You <i>could</i> have collected ".$totalpoint." points!<br>";
		echo "End of game points: $endpoint<br>";
		if($bonuspoint!=0) echo "Bonus points: $bonuspoint<br>";
		if($matchid!=-1) echo "Aggreement points: $matchpoint<br>";
		echo "</center>";

	?>
	<a class="button resultscreen_button" href="twitter_redirect.php">Login to continue!</a>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
