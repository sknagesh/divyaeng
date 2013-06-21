<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);

$toolid=$_POST['Tool_ID_1'];
$qty=$_POST['toolqty'];
$action=$_POST['adddrw'];
$ttype=$_POST['resha'];

$istheretool="SELECT * FROM Tool_Qty WHERE Tool_ID='$toolid';";
$res=mysql_query($istheretool) or die(mysql_error());
$t=mysql_affected_rows();
print("$t<br>");
if($t==0)
{

	if($action=='add')
	{

		if($ttype=='newtool')
		{
			$query="Insert INTO Tool_Qty (Qty_New,Tool_ID) VALUES($qty,$toolid);";
		
		}else
		if($ttype=='resharpened')
		{
			$query="Insert INTO Tool_Qty (Qty_Resharpened,Tool_ID) VALUES($qty,$toolid);";

		}
		
		
	}

}else{
$row=mysql_fetch_assoc($res);
	if($action=='withdraw')
		{

		if($ttype=='newtool')
			{
				$storeq=$row['Qty_New']-$qty;
				$shopq=$row['Qty_New_SF']+$qty;
				$query="UPDATE Tool_Qty SET Qty_New=$storeq, Qty_New_SF=$shopq WHERE Tool_ID=$toolid;";		
		 	}else
		if($ttype=='resharpened')
			{
				$storeq=$row['Qty_Resharpened']-$qty;
				$shopq=$row['Qty_Resharpened_SF']+$qty;
				$query="UPDATE Tool_Qty SET Qty_Resharpened=$storeq, Qty_Resharpened_SF=$shopq WHERE Tool_ID=$toolid;";
			}
	
 		}else
	if($action=='remove')
		{

		if($ttype=='newtool')
			{
				$shopq=$row['Qty_New_SF']-$qty;
				$reshq=$row['Qty_Tool_Tray']+$qty;
				$query="UPDATE Tool_Qty SET Qty_New_SF=$shopq,Qty_Tool_Tray=$reshq WHERE Tool_ID=$toolid;";		
		 	}else
		if($ttype=='resharpened')
			{
				$shopq=$row['Qty_Resharpened_SF']-$qty;
				$reshq=$row['Qty_Tool_Tray']+$qty;
				$query="UPDATE Tool_Qty SET Qty_Resharpened_SF=$shopq, Qty_Tool_Tray=$reshq WHERE Tool_ID=$toolid;";
			}
	
 		}else
	
	if($action=='add')
	{

		if($ttype=='newtool')
			{
				$storeq=$row['Qty_New']+$qty;
				$query="UPDATE Tool_Qty SET Qty_New=$storeq WHERE Tool_ID=$toolid;";
		
			}else
		if($ttype=='resharpened')
			{
				$storeq=$row['Qty_Resharpened']+$qty;
				$query="UPDATE Tool_Qty SET Qty_Resharpened=$storeq WHERE Tool_ID=$toolid;";
			}
		
		
	}

	}



print($query);

$result=mysql_query($query) or die(mysql_error());
$rowres=mysql_affected_rows();

if($rowres!=0)
{
	print("Successfully updated Database");
}

?>