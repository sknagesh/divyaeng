<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$sopid=$_POST['Operation_IDS'];
$dopid=$_POST['Operation_IDD'];
$ipid=$_POST['Dimension_ID'];
$movedimn=$_POST['movedimn'];

$len=count($ipid);


$a=0;
$moved=0;

while ($a <= $len-1) {
	
	//if($ipid[$a]!="")
	if(isSet($ipid[$a]))
	{
		if(isSet($movedimn[$a]))
		{
$qry="UPDATE Dimension SET 	Operation_ID='$dopid' WHERE Dimension_ID='$ipid[$a]'";
//print("$qry");
$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$moved++;			
		}
	}
		
$a++;	
}

print(" $moved Dimensions Moved");

?>