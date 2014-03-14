<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/processrev/';
print_r($_POST);
$opeid=$_POST['Operation_ID'];
$operatorid=$_POST['Operator_ID'];
if(isSet($_POST['cdatedb'])){$cdatedb=$_POST['cdatedb'];}else{$cdatedb="";}
if(isSet($_POST['creason'])){$creason=$_POST['creason'];}else{$creason="";}
if(isSet($_POST['changes'])){$changes=$_POST['changes'];}else{$changes="";}
if(isSet($_POST['remarks'])){$remarks=$_POST['remarks'];}else{$remarks="";}

$oimgfiles=count($_FILES['oimg']['name']);

if($oimgfiles!=0)
{
	foreach ($_FILES['oimg']['name'] as $key => $name) {
		if($_FILES['oimg']['name'][$key]!='')
{
	$drgfileName = $opeid."-".$_FILES['oimg']['name'][$key];
	$drgtmpName = $_FILES['oimg']['tmp_name'][$key];
	$drgfileSize = $_FILES['oimg']['size'][$key];
	$drgfileType = $_FILES['oimg']['type'][$key];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	if (!$result) {
						echo "<br>Error uploading File $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileNames[$key] = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}
						}
}
}else{$drgfileNames='';}


$query="INSERT INTO Process_Revision (Operation_ID,
								Operator_ID,
								Change_Date,
								Revision_Reason,
								Process_Changes,
								Remarks) 
	 						VALUES('$opeid',
									'$operatorid',
									'$cdatedb',
									'$creason',
									'$changes',
									'$remarks');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
	$oid=mysql_insert_id();
	if($oimgfiles!=0)
		for ($i=0; $i < $oimgfiles; $i++) { 
			$quef="INSERT INTO PRev_Image (Process_Revision_ID,Image_Path) VALUES( $oid,'$drgfileNames[$i]');";
			print($quef);
			$resf=mysql_query($quef) or die(mysql_error());
	}
	
	
print('<p style="font-size:12px;color:green">Recorded Process Change</p>');	
	
}else
	{
		print('<p style="font-size:12px;color:red">Error Recording Change</p>');
	}


?>