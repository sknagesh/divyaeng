<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);	
$gageslnoid=$_POST['Gage_SlNo_ID'];
$delslno=$_POST['delgage'];
$len=count($gageslnoid);
$a=0;
$deleted=0;
$mdel=0;
while ($a <= $len-1) {
	if(isset($delslno[$a]))
	{
		$query="DELETE FROM Gage_SlNo WHERE Gage_SlNo_ID=$gageslnoid[$a];";		
//		print("<p>$query");		
		$res = mysql_query($query, $cxn) or die(mysql_error($cxn));
		$deleted+=1;
	}
$a++;	
}

	print("Deleted $deleted Gages");
?>