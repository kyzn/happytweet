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


$stmt = $db->prepare("SELECT ScreenName,WeeklyPoint,TotalPoint FROM Users WHERE UserID!=0 ORDER BY TotalPoint DESC LIMIT 5");
$stmt->execute();


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
    <table style="width:100%">
	  <tr class="titles">
	    <th>Rank</th>
	    <th>Screen Name</th>
	    <th>Last Week Points</th>
	    <th>Total Points</th>
	  </tr>  
		<?php
		
		   $index = 1;
		   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			   echo "<tr>";
			   echo "<td>".$index."</td>";
			   echo "<td>".$row['ScreenName']."</td>";
			   echo "<td>".$row['WeeklyPoint']."</td>";
			   echo "<td>".$row['TotalPoint']."</td>";
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
