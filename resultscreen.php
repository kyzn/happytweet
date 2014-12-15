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
'
</head>
<body>
<?php include("./navigation.php");?>


  <div class="w-container result_container">
  
	<p><?php

		//emo1..10 str1..10 time10 elimde.

		//a) emo1..10'u Plays userid setid verip kaydet.
		$userid = $_SESSION['access_token']['user_id'];
		$setid = $_SESSION['setid'];
		if(isset($_SESSION['matchid'])) $matchid=$_SESSION['matchid'];

		//DEBUG
		echo "user $userid set $setid match $matchid scores "; 
		for ($x = 1; $x <= 10; $x++) echo $_SESSION['emo'.$x].' ';
		echo " words ";
		for ($x = 1; $x <= 10; $x++) echo $_SESSION['str'.$x].' ';


		$stmt = $db->prepare("UPDATE Plays SET Vote1=?, Vote2=?, Vote3=?, Vote4=?,
		Vote5=?, Vote6=?, Vote7=?, Vote8=?, Vote9=?, Vote10=? WHERE UserID=? AND SetID=?;");
		$stmt->execute(array(
			$_SESSION['emo1'],
			$_SESSION['emo2'],
			$_SESSION['emo3'],
			$_SESSION['emo4'],
			$_SESSION['emo5'],
			$_SESSION['emo6'],
			$_SESSION['emo7'],
			$_SESSION['emo8'],
			$_SESSION['emo9'],
			$_SESSION['emo10'],
			$userid,
			$setid
			));

		//b) her bir word için: words tablosunda olup olmadığını kontrol et
		//	varsa verilen voteun puanını increment et, wordün idsini çek
		//	yoksa words tablosuna verilen vote=1 diye ekle, idsini getir

		for ($x = 1; $x <= 10; $x++) {
			if($_SESSION['emo'.$x]!=3){
				$stmt = $db->prepare("SELECT WordID FROM Words WHERE WordText=?");
				$stmt->execute(array($_SESSION['str'.$x]));
				




			}
		}


		//c) geriye çektiğim word idlerini play tablosunda wordlere ekle

		//d) eğer eşleşme ise:
		//	eşlenilen oyunun puanlarını ve kelimelerini getir.
		//	exact vote match +3pts
		//	close vote match +1pts
		//	if vote!=3 word match +5pts
		//	bunları topla, matchpoint olarak belirle.
		//	Her iki kullanıcının oyununun da matchpoint verisini güncelle
		//	Her iki kullanıcının da weeklypoint ve totalpoint verilerini güncelle

		//e) Bonus şimdilik 0, endpoint = kalansüre *2 şeklinde plays tablosunu güncelle
		//	kullanıcının weeklypoint ve totalpoint verilerini de güncelle.


	
			for ($x = 1; $x <= 10; $x++) {
			
				if (isset($_SESSION['emo'.$x]))
				{
					echo $_SESSION['emo'.$x].'<br>'.$_SESSION['str'.$x].'<br>'.$_SESSION['time'.$x].'<hr>';
					unset($_SESSION['emo'.$x]);
					unset($_SESSION['str'.$x]);
					unset($_SESSION['time'.$x]);
				}
				else{
					echo $x." is not set<br>";
				}
			}
	?></p>
				 
    <p class="style"></p><a class="button resultscreen_button" href="play.php">Keep Playing!</a>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
