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
<?php include("./navigation.php");?>


  <div class="w-container result_container">
  
	<?php

		//emo1..10 str1..10 time10 elimde.

		//a) emo1..10'u Plays userid setid verip kaydet.
		$userid = $_SESSION['access_token']['user_id'];

		$setid = $_SESSION['setid'];
		unset($_SESSION['setid']);

		if(isset($_SESSION['matchid'])){
			$matchid=$_SESSION['matchid']; 
			unset($_SESSION['matchid']);
		}else{
			$matchid=-1;
		} 

		//DEBUG
		//echo "user $userid set $setid match $matchid ";
		$emo = $_SESSION['emo'];
		$str = $_SESSION['str'];
		$time = $_SESSION['time'][10];
		list($min,$sec) =explode(" : ",$time);
		$time=60*$min+$sec;

		unset($_SESSION['emo']);
		unset($_SESSION['str']);
		unset($_SESSION['time']);

		//DEBUG
		//echo " scores ";
		//for ($x = 1; $x <= 10; $x++) echo $emo[$x].' ';
		//echo " words ";
		//for ($x = 1; $x <= 10; $x++) echo $str[$x].' ';



		//If not finished, mark unsolved questions -1.
		for($x=1;$x<=10;$x++){
			if(!isset($emo[$x])  ){
				$emo[$x]=-1;
			} 
		}

		//echo " scores ";
		//for ($x = 1; $x <= 10; $x++) echo $emo[$x].' ';

		$stmt = $db->prepare("UPDATE Plays SET Vote1=?, Vote2=?, Vote3=?, Vote4=?,
		Vote5=?, Vote6=?, Vote7=?, Vote8=?, Vote9=?, Vote10=? WHERE UserID=? AND SetID=?;");
		$stmt->execute(array(
			$emo[1],
			$emo[2],
			$emo[3],
			$emo[4],
			$emo[5],
			$emo[6],
			$emo[7],
			$emo[8],
			$emo[9],
			$emo[10],
			$userid,
			$setid
			));

		//b) her bir word için: words tablosunda olup olmadığını kontrol et
		//	varsa verilen voteun puanını increment et, wordün idsini çek
		//	yoksa words tablosuna verilen vote=1 diye ekle, idsini getir

		for ($x = 1; $x <= 10; $x++) {
			if($emo[$x]!=3){
				$stmt = $db->prepare("SELECT WordID FROM Words WHERE WordText=?");
				$stmt->execute(array($str[$x]));
				$numrows = $stmt->rowCount();
				if($numrows==0){//Word is going to be added for the first time
					$stmt = $db->prepare("INSERT INTO Words (WordText, Vote".$emo[$x]."Count, CreatedOn, LastVoteOn) VALUES (?,1,NOW(),NOW());");
					$stmt -> execute(array($str[$x]));
					$stmt = $db->prepare("SELECT WordID FROM Words WHERE WordText=?");
					$stmt -> execute(array($str[$x]));
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$wordid[$x] = $row['WordID'];
				}
				else{
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					$wordid[$x] = $row['WordID'];
					$stmt = $db->prepare("UPDATE Words SET Vote".$emo[$x]."Count = Vote".$emo[$x]."Count+1, LastVoteOn = NOW() WHERE WordID = ?;");
					$stmt -> execute(array($wordid[$x]));

				}

			}
		}


		//c) geriye çektiğim word idlerini play tablosunda wordlere ekle

		for ($x = 1; $x <= 10; $x++) if ($wordid[$x]=="") $wordid[$x]=1;

		//DEBUG
		//echo "wordids ";
		//for ($x = 1; $x <= 10; $x++) echo $wordid[$x]." ";

		$stmt = $db->prepare("UPDATE Plays SET Word1=?, Word2=?, Word3=?, Word4=?,
		Word5=?, Word6=?, Word7=?, Word8=?, Word9=?, Word10=? WHERE UserID=? AND SetID=?;");
		$stmt->execute(array(
			$wordid[1],
			$wordid[2],
			$wordid[3],
			$wordid[4],
			$wordid[5],
			$wordid[6],
			$wordid[7],
			$wordid[8],
			$wordid[9],
			$wordid[10],
			$userid,
			$setid
			));


		//d) eğer eşleşme ise:
		//	eşlenilen oyunun puanlarını ve kelimelerini getir.
		//	exact vote match +3pts
		//	close vote match +1pts
		//	if vote!=3 word match +5pts
		//	bunları topla, matchpoint olarak belirle.
		//	Her iki kullanıcının oyununun da matchpoint verisini güncelle
		//	Her iki kullanıcının da weeklypoint ve totalpoint verilerini güncelle

		if($matchid!=-1){

			$stmt=$db->prepare("SELECT * FROM Plays WHERE UserID=? AND SetID=?");
			$stmt->execute(array($matchid,$setid));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			for ($x=1; $x<=10; $x++) $wordid_m[$x]=$row['Word'.$x];
			for ($x=1; $x<=10; $x++) $emo_m[$x]=$row['Vote'.$x];

			//DEBUG
			//echo "match votes ";
			//for ($x = 1; $x <= 10; $x++) echo $emo_m[$x].' ';
			//echo "match words ";
			//for ($x = 1; $x <= 10; $x++) echo $wordid_m[$x].' ';



			$matchpoint=0;
		
			//DEBUG
			//echo "match votes ";

			for ($x = 1; $x <= 10; $x++) {

				$emo_diff=abs($emo[$x] - $emo_m[$x]);
				
				if($emo_diff==0){
					$matchpoint+=3;
					//DEBUG
					//echo "+3 ";
				}else if($emo_diff==1){
					$matchpoint+=1;
					//DEBUG
					//echo "+1 ";
				}else{
					//DEBUG
					//echo "+0 ";
				}

			}
			
			//DEBUG
			//echo "match words ";
			
			for($x=1;$x<=10;$x++){
				
				if($emo[$x]==3){ //no word match possible for neutral votes
					//DEBUG
					//echo "na ";
				}else{
					if($wordid[$x]==$wordid_m[$x]){
						//DEBUG
						//echo "+5 ";
						$matchpoint+=5;
					}else{
						//DEBUG
						//echo "+0";
					}
				}
			}

			//DEBUG
			//echo " matchpoint $matchpoint ";

			//Matchpoint is set at this point.

			//Update Plays.MatchPoint
			$stmt=$db->prepare("UPDATE Plays SET MatchPoint = ? WHERE SetID = ? AND (UserID = ? OR UserID = ?);");
			$stmt->execute(array($matchpoint,$setid,$userid,$matchid));

			//Increment user points by matchpoint
			$stmt=$db->prepare("UPDATE Users SET WeeklyPoint = WeeklyPoint+?, TotalPoint = TotalPoint+? WHERE (UserID = ? OR UserID = ?);");
			$stmt->execute(array($matchpoint,$matchpoint,$userid,$matchid));


		}


		//e) Bonus şimdilik 0, endpoint = kalansüre *2 şeklinde plays tablosunu güncelle
		//	kullanıcının weeklypoint ve totalpoint verilerini de güncelle.

		
		$bonuspoint = 0; //TODO: Set up bonus mechanism
		$endpoint = $time *2;
		$exceptmatchpoint = $bonuspoint+$endpoint;//to be used in sql statement
		$totalpoint = $bonuspoint+$endpoint+$matchpoint;
		
		//DEBUG
		//echo " time $time endpoint $endpoint bonuspoint $bonuspoint exceptmatchpoint $exceptmatchpoint ";
		
		//Update Plays.EndPoint & BonusPoint
		$stmt=$db->prepare("UPDATE Plays SET EndPoint = ?, BonusPoint = ? WHERE SetID = ? AND UserID = ?;");
		$stmt->execute(array($endpoint,$bonuspoint,$setid,$userid));

		//Increment user points by endpoint+bonuspoint
		$stmt=$db->prepare("UPDATE Users SET WeeklyPoint = WeeklyPoint+?, TotalPoint = TotalPoint+? WHERE UserID = ?;");
		$stmt->execute(array($exceptmatchpoint,$exceptmatchpoint,$userid));

		echo "<center><br>You have collected ".$totalpoint." points!<br>";
		echo "End of game points: $endpoint<br>";
		if($bonuspoint!=0) echo "Bonus points: $bonuspoint<br>";
		if($matchid!=-1) echo "Aggreement points: $matchpoint<br>";
		echo "</center>";

	?>
	<a class="button resultscreen_button" href="play.php">Keep Playing!</a>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
