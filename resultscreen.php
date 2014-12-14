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
  
	<p><?php echo $_SESSION['emo1'].'<br>'.$_SESSION['str1'].'<br>'.$_SESSION['time1'].'<hr>'
				 .$_SESSION['emo2'].'<br>'.$_SESSION['str2'].'<br>'.$_SESSION['time2'].'<hr>'
				 .$_SESSION['emo3'].'<br>'.$_SESSION['str3'].'<br>'.$_SESSION['time3'].'<hr>'
				 .$_SESSION['emo4'].'<br>'.$_SESSION['str4'].'<br>'.$_SESSION['time4'].'<hr>'
				 .$_SESSION['emo5'].'<br>'.$_SESSION['str5'].'<br>'.$_SESSION['time5'].'<hr>'
				 .$_SESSION['emo6'].'<br>'.$_SESSION['str6'].'<br>'.$_SESSION['time6'].'<hr>'
				 .$_SESSION['emo7'].'<br>'.$_SESSION['str7'].'<br>'.$_SESSION['time7'].'<hr>'
				 .$_SESSION['emo8'].'<br>'.$_SESSION['str8'].'<br>'.$_SESSION['time8'].'<hr>'
				 .$_SESSION['emo9'].'<br>'.$_SESSION['str9'].'<br>'.$_SESSION['time9'].'<hr>'
				 .$_SESSION['emo10'].'<br>'.$_SESSION['str10'].'<br>'.$_SESSION['time10'].'<hr>'	?></p>
				 
    <p class="style"></p><a class="button resultscreen_button" href="play.html">Keep Playing!</a>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
