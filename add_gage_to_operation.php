<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
if(isSet($_POST['Gage_ID'])){$gid=$_POST['Gage_ID'];}else{$gid="";}
if(isSet($_POST['Operation_ID'])){$opid=$_POST['Operation_ID'];}else{$opid="";}



		$que="INSERT INTO Operation_Gage (Gage_ID,Operation_ID) VALUES( $gid,$opid);";
	
	//		print($que);
			$resf=mysql_query($que) or die(mysql_error());
		
		



?>