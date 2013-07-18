<?php
include('dewdb.inc');
require ("php-excel.class.php");

//print_r($_POST);


$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_POST['Drawing_ID'];

	
	

$j="SELECT Component_Name, Customer_Name, Drawing_NO,Drawing_Rev FROM Component as comp 
			INNER JOIN Customer as cust ON cust.Customer_ID=comp.Customer_ID WHERE Drawing_ID='$drawingid';";
	$rr = mysql_query($j, $cxn) or die(mysql_error($cxn));
while($rrs=mysql_fetch_assoc($rr))
{
	$cname=$rrs['Component_Name'];
	$partno=$rrs['Drawing_NO']."/".$rrs['Drawing_Rev'];
	$custname=$rrs['Customer_Name'];
}
$name=$partno;
$pd=array($cname,$partno,$custname);
$xls = new Excel_XML;
$xls->addArray($pd);

	$qry="SELECT dimn.Operation_ID, Basic_Dimn,dimn.Desc_ID,Least_Count,op.Operation_Desc,
	TRIM(TRAILING '0' FROM Tol_Lower) as tl, TRIM(TRAILING '0' FROM Tol_Upper) as tu,
	dimn.Instrument_ID,Instrument_Description, 
			Baloon_NO,Dimn_Desc FROM Dimension as dimn 
			INNER JOIN Operation AS ope ON ope.Operation_ID=dimn.Operation_ID
			INNER JOIN Component as comp ON comp.Drawing_ID=ope.Drawing_ID
			INNER JOIN Instrument AS inst ON inst.Instrument_ID=dimn.Instrument_ID 
			INNER JOIN Operation AS op ON dimn.Operation_ID=op.Operation_ID
			INNER JOIN Dimn_Desc as dd ON dd.Desc_ID=dimn.Desc_ID  
			WHERE comp.Drawing_ID='$drawingid' ORDER BY Operation_ID, Baloon_NO ASC;";
			
//print("<br>$qry");

	$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
	$j=0;	
	while ($row = mysql_fetch_assoc($resa))  //get all dimensions for thi operation and store them in an array
        		{
	$dimn[$j]=array($row['Operation_Desc'],$row['Dimn_Desc'],$row['Baloon_NO'],$row['Basic_Dimn'],$row['tu'],$row['tl'],$row['Instrument_Description'],$row['Least_Count']);		
	$j++;
		        }


$xls->addArray($dimn);
$xls->generateXML ($name);

?>