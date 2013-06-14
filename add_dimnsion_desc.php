<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

if(isSet($_POST['ddesc'])){$ddesc=$_POST['ddesc'];}else{$ddesc='';}
$rdesc=$_POST['rdesc'];
if(isSet($_POST['dcomm'])){$dcomm=$_POST['dcomm'];}else{$dcomm='';}
if(isSet($_POST['Desc_ID'])){$did=$_POST['Desc_ID'];}else{$did='';}
if(isSet($_POST['cid'])){$cid=$_POST['cid'];}else{$cid='';}
if(isSet($_POST['com'])){$com=$_POST['com'];}else{$com='';}

$atype=$_POST['atype'];

if($atype=='add')
{

$query="INSERT INTO Dimn_Desc (Dimn_Desc,Detailed_Desc) ";
$query.="VALUES('$rdesc','$ddesc');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
if($dcomm!='')
{
	$iid=mysql_insert_id();
	$d=explode('/',$dcomm);
	foreach ($d as &$value) {
	$qi="INSERT INTO Dimn_Comment (Desc_ID,Comment) values('$iid','$value');";
//	print($qi);
	$ri=mysql_query($qi) or die(mysql_error());
	}
	
}
print("Added New Description $rdesc");	
	
}else
	{
		print("Error Adding Description");
	}
}else
	if($atype=='edit')
	{
	$qu="UPDATE Dimn_Desc SET Dimn_Desc='$rdesc', Detailed_Desc='$ddesc' WHERE Desc_ID='$did';";
//	print($qu);
	$ru=mysql_query($qu) or die(mysql_error());

if($did!='')
{

for($j=0;$j<count($cid);$j++)
{
	$qi="UPDATE Dimn_Comment SET Comment='$com[$j]' WHERE Comment_ID='$cid[$j]';";
//	print($qi);
	$ri=mysql_query($qi) or die(mysql_error());

}
	
		
	
	
}

		
	}


?>