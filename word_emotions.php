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


$stmt_1 = $db->prepare("SELECT WordText, ROUND((Vote1Count*(1)+Vote2Count*(2)+Vote3Count*(3)+Vote4Count*(4)+Vote5Count*(5))/(Vote1Count+Vote2Count+Vote3Count+Vote4Count+Vote5Count),2) AS Grade FROM Words WHERE WordID !=1 ORDER BY Grade DESC LIMIT 5;");
$stmt_1->execute();
$stmt_2 = $db->prepare("SELECT WordText, ROUND((Vote1Count*(1)+Vote2Count*(2)+Vote3Count*(3)+Vote4Count*(4)+Vote5Count*(5))/(Vote1Count+Vote2Count+Vote3Count+Vote4Count+Vote5Count),2) AS Grade FROM Words WHERE WordID !=1 ORDER BY Grade ASC LIMIT 5;");
$stmt_2->execute();
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

</head>
<body>

		
<?php include("./navigation.php");?>

  <div class="w-container mygames_container">

    <table style="margin-left:20%;width:40%;display:inline-block">
	  <tr class="titles">
	    <th>Happy Words</th>
	  </tr>
	  
	  <?php

	  		   while ($row = $stmt_1->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
			   echo "<td>".$row['Grade']."</td>";
			   echo "</tr>";
		   }
		   
		?>
   </table>
       <table style="width:20%;display:inline-block">
	  <tr class="titles">
	    <th>Sad Words</th>
	  </tr>
	  
	  <?php
	  		   while ($row = $stmt_2->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
			   echo "<td>".$row['Grade']."</td>";
			   echo "</tr>";
		   }
		?>
   </table>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
  <!--[if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif]-->
</body>
</html>
