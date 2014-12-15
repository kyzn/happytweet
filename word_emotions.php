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


$stmt_1 = $db->prepare("SELECT WordText FROM Words ORDER BY Vote5Count DESC LIMIT 5");
$stmt_1->execute();
$stmt_2 = $db->prepare("SELECT WordText FROM Words ORDER BY Vote4Count DESC LIMIT 5");
$stmt_2->execute();
$stmt_3 = $db->prepare("SELECT WordText FROM Words ORDER BY Vote3Count DESC LIMIT 5");
$stmt_3->execute();
$stmt_4 = $db->prepare("SELECT WordText FROM Words ORDER BY Vote2Count DESC LIMIT 5");
$stmt_4->execute();
$stmt_5 = $db->prepare("SELECT WordText FROM Words ORDER BY Vote1Count DESC LIMIT 5");
$stmt_5->execute();
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

    <table style="width:20%;display:inline-block">
	  <tr class="titles">
	    <th>Top Extremely Positive Words</th>
	  </tr>
	  
	  <?php

	  		   while ($row = $stmt_1->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
			   echo "</tr>";
		   }
		   
		?>
   </table>
       <table style="width:20%;display:inline-block">
	  <tr class="titles">
	    <th>Top Positive Words</th>
	  </tr>
	  
	  <?php
	  		   while ($row = $stmt_2->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
			   echo "</tr>";
		   }
		?>
   </table>
       <table style="width:20%;display:inline-block">
	  <tr class="titles">
	    <th>Top Neutral Words</th>
	  </tr>
	  
	  <?php
	  		   while ($row = $stmt_3->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
			   echo "</tr>";
		   }
		?>
   </table>
       <table style="width:20%;display:inline-block">
	  <tr class="titles">
	    <th>Top Negative Words</th>
	  </tr>
	  
	  <?php
	  		   while ($row = $stmt_4->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
			   echo "</tr>";
		   }
		?>
   </table>
       <table style="width:20%;display:inline-block">
	  <tr class="titles">
	    <th>Top Extremely Negative Words</th>
	  </tr>
	  
	  <?php
	  		   while ($row = $stmt_5->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$row['WordText']."</td>";
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
