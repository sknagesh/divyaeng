<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$toolid=$_POST['Tool_ID_1'];
$qty=$_POST['toolqty'];
$action=$_POST['adddrw'];
$ttype=$_POST['resha'];

$ok=0;
$query="SELECT Qty_New,Qty_Resharpened,Qty_New_SF,Qty_Resharpened_SF FROM Tool_Qty WHERE Tool_ID='$toolid';";
$msg="";
//print($query);

$res=mysql_query($query) or die(mysql_error());
$row=mysql_fetch_assoc($res);

if($action=='withdraw')
{

	if($ttype=='newtool')
	{

		if($qty>$row['Qty_New'])
				{
				if($row['Qty_New']!=0){$n=$row['Qty_New'];}else{$n=0;}
			 $msg="$n tools are in stock, Please enter correct quantity";
			 $ok=1;
			 	}		
	}else
		if($ttype=='resharpened')
		{
				if($qty>$row['Qty_Resharpened'])
				{
				if($row['Qty_Resharpened']!=0){$n=$row['Qty_Resharpened'];}else{$n=0;}
				$msg="$n resharpened tools are in stock, Please enter correct quantity";
				$ok=1;
				}
		}
		
	
}else
if($action=='remove')
{

	if($ttype=='newtool')
	{

		if($qty>$row['Qty_New_SF'])
				{
				if($row['Qty_New_SF']!=0){$n=$row['Qty_New_SF'];}else{$n=0;}
			 $msg="$n tools are in shop flor, Please enter correct quantity";
			 $ok=1;
			 	}		
	}else
		if($ttype=='resharpened')
		{
				if($qty>$row['Qty_Resharpened_SF'])
				{
					if($row['Qty_Resharpened_SF']!=0){$n=$row['Qty_Resharpened_SF'];}else{$n=0;}
				$msg="$n resharpened tools are in shop floor, Please enter correct quantity";
				$ok=1;
				}
		}
		
	
}else
{	
	if($action=='add')
	{

	if($ttype=='newtool')
	{
		if($row['Qty_New']!=0){$n=$row['Qty_New'];}else{$n=0;}
		$msg="$n New tools are in stock";
		
	}else
		if($ttype=='resharpened')
		{
		if($row['Qty_Resharpened']!=0){$n=$row['Qty_Resharpened'];}else{$n=0;}
			$msg="$n Resharpened tools are in stock";
		}
		
		
	}
}
$textmessag=$msg."<|>".$ok;
print($textmessag);
?>