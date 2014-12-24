<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
if(isSet($_POST['slno'])){$slno=$_POST['slno'];}else{$slno="";}
if(isSet($_POST['Gage_ID'])){$gid=$_POST['Gage_ID'];}else{$gid="";}
if(isSet($_POST['recdatedb'])){$ddb=$_POST['recdatedb'];}else{$ddb="";}
if(isSet($_POST['type'])){$type=$_POST['type'];}else{$type="";}
if(isSet($_POST['gpno'])){$gpno=$_POST['gpno'];}else{$gpno="";}



		$que="INSERT INTO Gage_SlNo (Gage_ID,Gage_No,Date_Received,Gage_Type,Gate_Pass_No) VALUES( $gid,'$slno','$ddb','$type','$gpno');";
	
	//		print($que);
			$resf=mysql_query($que) or die(mysql_error());
		
		



?>