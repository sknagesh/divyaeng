<?php
include('dewdb.inc');
require ("php-excel.class.php");
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawinglist=$_POST['dlist'];

$dids=explode(',', $drawinglist);

$xldata[0]=array('Drawing No','Component Name');
$x=1;
for($i=0;$i<count($dids)-1;$i++)
{

	$q1="SELECT Drawing_NO,Component_Name from Component WHERE Drawing_ID='".$dids[$i]."';";
	$result1=mysql_query($q1) or die(mysql_error());
	$r1=mysql_fetch_assoc($result1);
	$t=array($r1['Drawing_NO'],$r1['Component_Name']);
	$xldata[$x]=$t;
	$x++;

	$q2="SELECT * FROM Operation WHERE Drawing_ID='".$dids[$i]."' AND In_Tool_List=1 ORDER BY Operation_Desc ASC;";

	$rr=mysql_query($q2) or die(mysql_error());
	$noofop=mysql_affected_rows();
	$pn=0;
	while($row=mysql_fetch_assoc($rr))
	{
		
//	array_push($xldata[$x],'Operation Description',$row[Operation_Desc],'Tool Part No and Description','Tool Holder','Tool Overhang','Work Description');
		$tt=array('Operation Description',$row['Operation_Desc']);
		$xldata[$x]=$tt;
		$x++;
		$tttt=array('Slno','Tool Part No and Description','Tool Holder','Tool Overhang','Work Description');
		$xldata[$x]=$tttt;
	$x++;


		$q3='SELECT Ope_Tool_ID,Ope_Tool_OH,Ope_Tool_Desc,Tool_ID_1,Ope_Tool_Image_Path,Storage_Location,
		(SELECT CONCAT(Tool_Desc," ( ",Tool_Part_NO," )") FROM Tool WHERE Tool_ID=ot.Tool_ID_1) as td1, 
		(SELECT CONCAT(Tool_Desc," ( ",Tool_Part_NO," )") FROM Tool WHERE Tool_ID=ot.Tool_ID_2)as td2, 
		(SELECT CONCAT(Insert_Part_NO," ",Insert_Description) FROM Inserts WHERE Insert_ID=ot.Insert_ID_1) as i1,
		(SELECT CONCAT(Insert_Part_NO," ",Insert_Description) FROM Inserts WHERE Insert_ID=ot.Insert_ID_2) as i2,
		(SELECT Brand_Description FROM Tool_Brand as tb INNER JOIN Tool as t1 ON t1.Brand_ID=tb.Brand_ID WHERE t1.Tool_ID=ot.Tool_ID_1) as tb1,
		(SELECT Brand_Description FROM Tool_Brand as tb2 INNER JOIN Tool as t2 ON t2.Brand_ID=tb2.Brand_ID WHERE t2.Tool_ID=ot.Tool_ID_2) as tb2, 
		(SELECT Holder_Description FROM Holder as h1 WHERE Holder_ID=ot.Holder_ID_1) as hd1,
		(SELECT Holder_Description FROM Holder as h2 WHERE Holder_ID=ot.Holder_ID_2) as hd2
		FROM Ope_Tool AS ot WHERE ot.Operation_ID="'.$row[Operation_ID].'";';

		$rr3=mysql_query($q3) or die(mysql_error());
		
		$n=1;
			while($rowi=mysql_fetch_assoc($rr3))
			{

			$ttt=array($n,$rowi['td1'],$rowi['hd1'],$rowi['Ope_Tool_OH'],$rowi['Ope_Tool_Desc']);
			$xldata[$x]=$ttt;
			$n+=1;
			$x++;
		
			}


	
	
	}






}

//print_r($xldata);
$xls = new Excel_XML;
$xls->addArray ( $xldata );
$xls->generateXML ('toolmatrix.xls');


?>