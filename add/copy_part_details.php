<?php
include('dewdb.inc');

$cxn1 = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
$cxn2 = mysql_connect($dewhost,$dewname,$dewpswd,true) or die(mysql_error());
mysql_select_db('Process',$cxn1) or die("error opening db: ".mysql_error());
mysql_select_db('Divyaeng',$cxn2) or die("error opening db: ".mysql_error());


//print_r($_POST);


$sdid=$_POST['sDrawing_ID'];
$ddid=$_POST['dDrawing_ID'];
$q="SELECT * FROM Part WHERE Drawing_ID='$sdid';";
	print("<br>$q");
$res = mysql_query($q, $cxn1) or die(mysql_error($cxn1));
while($row=mysql_fetch_assoc($res))
{

$qd="UPDATE Component
				SET Component_Material='$row[Component_Material]',
				Raw_Material_Size='$row[Cut_Blank]',
				Pre_Machined_Blank_Size='$row[Pre_Machined_Blank]',
				Customer_Drawing='$row[Drawing_File]',
				Process_Sheet='$row[Process_Sheet]',
				Preview_Image='$row[Preview_Image]' WHERE Drawing_ID='$ddid';"; 
print("<br>$qd");
$resd = mysql_query($qd, $cxn2) or die(mysql_error($cxn2));
	
	
}



	
print("<br>updated Part Descriptions");
?>
