$stmt = $db->prepare("SELECT SetID FROM Plays ORDER BY Rand() LIMIT 1;");
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$setid=$row['SetID'];

$commontweetids=array();

$stmt=$db->prepare("SELECT ROUND(AVG(Vote1)) AS Vote1, ROUND(AVG(Vote2)) AS Vote2, ROUND(AVG(Vote3)) AS Vote3, ROUND(AVG(Vote4)) AS Vote4, ROUND(AVG(Vote5)) AS Vote5, ROUND(AVG(Vote6)) AS Vote6, ROUND(AVG(Vote7)) AS Vote7, ROUND(AVG(Vote8)) AS Vote8, ROUND(AVG(Vote9)) AS Vote9, ROUND(AVG(Vote10)) AS Vote10, COUNT(*),SetID FROM Plays WHERE SetID = ".$setid." AND Vote1!=-1 AND Vote2!=-1 AND Vote3!=-1 AND Vote4!=-1 AND Vote5!=-1 AND Vote6!=-1 AND Vote7!=-1 AND Vote8!=-1 AND Vote9!=-1 AND Vote10!=-1;");
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);
for($i=1;$i<=10;$i++){
	$commontweetids[$i]=$row['Vote'.$i];

}

$commonwordids=array();

for($i=1;$i<=10;$i++){
	
	$stmt=$db->prepare("SELECT Word".$i.", COUNT(*) AS magnitude FROM Plays WHERE SetID=".$setid." GROUP BY Word".$i." ORDER BY magnitude DESC LIMIT 1;");
	$stmt->execute();
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	$commonwordids[$i]=$row['Word'.$i];

}

