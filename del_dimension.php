<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);	
$baloonno=$_POST['baloonno'];
$inprocessid=$_POST['Dimension_ID'];
$deldimn=$_POST['deldimn'];
$len=count($inprocessid);
$a=0;
$deleted=0;
$mdel=0;
print("Length is $len");
while ($a <= $len-1) {
	print("processing del din");
	if(isset($deldimn[$a]))
	{
		$q1="SELECT * FROM Measured_Dimn WHERE Dimension_ID='$inprocessid[$a]';";
		$res1 = mysql_query($q1, $cxn) or die(mysql_error($cxn));
		$nr=mysql_affected_rows($cxn);
		if($nr==0){
		print("No measured dimensions found for this dimension, so deleting");
		$query="DELETE FROM Dimension WHERE Dimension_ID=$inprocessid[$a];";		
		//print("$query");		
		$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
		$deleted+=1;
		}else{
		print("Measured dimensions found for this dimension, so marking as deleted");
		$query="UPDATE Dimension SET Deleted='1' WHERE Dimension_ID=$inprocessid[$a];";		
		//print("$query");		
		$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
		$mdel+=1;
		}
		print("<p>$deleted dimensions deleted and $mdel Dimensions marked as deleted<p>");
	}
$a++;	
}

	
?>