<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
//print_r($_FILES);
$drgid=$_POST['Drawing_ID'];
$price=$_POST['price'];


$query="UPDATE Component SET Part_Price='$price' WHERE Drawing_ID='$drgid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{

$q2="SELECT SUM(TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time))) as Total_Time FROM Operation WHERE Drawing_ID=$drgid AND In_Tool_List=1 AND In_Op_List=0;";

$r2=mysql_query($q2) or die(mysql_error());
$res2=mysql_fetch_assoc($r2);
$tt=$res2[Total_Time]/60;
print("<p>Total Time=$tt mins");
$ppm=$price/$tt;
print("<p>Price Per Min=$ppm");
$q3="SELECT TIME_TO_SEC(ADDTIME(Clamping_Time,Machining_Time)) as ott,Operation_ID,Operation_Desc FROM Operation WHERE Drawing_ID=$drgid AND In_Tool_List=1 AND In_Op_List=0;";
$r3=mysql_query($q3) or die(mysql_error());
$x=0;
while($res3=mysql_fetch_assoc($r3))
{
$opp=$ppm*($res3['ott']/60);
$q4="UPDATE Operation SET P_Of_Op=$opp WHERE Operation_ID=$res3[Operation_ID];";
$r4=mysql_query($q4) or die(mysql_error());	
print("<p>$res3[Operation_Desc] Price is Rs $opp");
$x++;
}


if($x!=0)
{
print("<p>Updated Component price to $price and $x Operations price Updated");	
}	
}else
	{
		print("<br>Error Updating");
	}





?>