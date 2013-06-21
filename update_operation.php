<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';

$drawid=$_POST['Drawing_ID'];
$opid=$_POST['Operation_ID'];
$opedesc=$_POST['opdesc'];
$itl=$_POST['itl'];
if(isSet($_POST['stime'])){$stime=$_POST['stime'];}else{$stime="";}
if(isSet($_POST['ctime'])){$ctime=$_POST['ctime'];}else{$ctime="";}
if(isSet($_POST['mtime'])){$mtime=$_POST['mtime'];}else{$mtime="";}
if(isSet($_POST['fixtno'])){$fixtno=$_POST['fixtno'];}else{$fixtno="";}
if(isSet($_POST['progno'])){$progno=$_POST['progno'];}else{$progno="";}
if(isSet($_POST['ppath'])){$ppath=$_POST['ppath'];}else{$ppath="";}
if(isSet($_POST['onote'])){$onote=$_POST['onote'];}else{$onote="";}
if(isSet($_POST['simg'])){$simg=$_POST['simg'];}else{$simg="";}

$fxtno=explode(',', $fixtno);

if($stime!="")
{
	$t=secs2hms($stime*60);
	$sltime=$t[0].':'.$t[1].':'.$t[2];
}


if($ctime!="")
{
	$t=secs2hms($ctime*60);
	$cltime=$t[0].':'.$t[1].':'.$t[2];
}



if($mtime!="")
{
	$t=secs2hms($mtime*60);
	$mctime=$t[0].':'.$t[1].':'.$t[2];

}

if(isSet($_FILES['oimg']))
{
$oimgfiles=count($_FILES['oimg']['name']);
}else{
	$oimgfiles='';
}

if($oimgfiles!=0)
{
	foreach ($_FILES['oimg']['name'] as $key => $name) {
		


	$drgfileName = $drawid."-".$_FILES['oimg']['name'][$key];
	$drgtmpName = $_FILES['oimg']['tmp_name'][$key];
	$drgfileSize = $_FILES['oimg']['size'][$key];
	$drgfileType = $_FILES['oimg']['type'][$key];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	if (!$result) {
						echo "<br>Error uploading Drawing $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileNames[$key] = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}
						}

}else{$drgfileNames='';}


if((isSet($_FILES['odwg']['name']))&&($_FILES['odwg']['name']!=''))
{
	$odrgfileName = $drawid."-".$_FILES['odwg']['name'];
	$odrgtmpName = $_FILES['odwg']['tmp_name'];
	$odrgfileSize = $_FILES['odwg']['size'];
	$odrgfileType = $_FILES['odwg']['type'];
	$odrgfilePath = $uploadDir . $odrgfileName;
	$oresult = move_uploaded_file($odrgtmpName, $odrgfilePath);
	if (!$oresult) {
						echo "<br>Error uploading Operation Drawing $odrgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$odrgfileName = addslashes($odrgfileName);
						$odrgfilePath = addslashes($odrgfilePath);
						}

}else{$odrgfileName='';}

if($odrgfileName!='')
{
	
	$stagedrg=",Stage_Drawing_Path='$odrgfileName'";
}else{
	$stagedrg='';
}

$query="UPDATE Operation 
			SET Operation_Desc='$opedesc',
				Setup_Time='$sltime',
				Clamping_Time='$cltime',
				Machining_Time='$mctime',
				Program_NO='$progno',
				NC_Prog_Path='$ppath',
				In_tool_List='$itl',
				Operation_Notes='$onote'
				$stagedrg
			WHERE Operation_ID=$opid;";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();

//add path of new images for this operation to database

if($oimgfiles!=0)
{


	for ($i=0; $i < $oimgfiles; $i++) { 
			$quef="INSERT INTO Operation_Image (Operation_ID,Operation_Image_Path) VALUES( $opid,'$drgfileNames[$i]');";
		print($quef);
		$resf=mysql_query($quef) or die(mysql_error());
		}
}	
//delete any old image selected to be deleted
if($simg!='')
{
$simages=count($simg);

$simg=array_values($simg); //re-order array

	for ($j=0; $j<$simages; $j++)
	{
//select file name and delete them from HDD and path from database
		$qf="SELECT Operation_Image_Path FROM Operation_Image WHERE OP_Image_ID=$simg[$j];";
		$rf=mysql_query($qf) or die(mysql_error());
		$rfr=mysql_fetch_assoc($rf);
		$filepath=$uploadDir.$rfr['Operation_Image_Path'];
//		print($filepath);
		unlink($filepath);	
		$q="DELETE FROM Operation_Image WHERE OP_Image_ID=$simg[$j];";
		$rf=mysql_query($q) or die(mysql_error());
		}
}



	if($fixtno!='')
	{
		$j=count($fxtno);
  	//just being lazy and deleting all previous fixture entries for this operation
					$qfixt="DELETE FROM Ope_Fixt_Map WHERE Operation_ID=$opid;";
			$resf=mysql_query($qfixt) or die(mysql_error());
	//add new fixtures for this operation
		for ($i=0; $i < $j; $i++) {
		$que="INSERT INTO Ope_Fixt_Map (Operation_ID,Fixture_NO) VALUES( $opid,'$fxtno[$i]');";
			print($que);
			$resf=mysql_query($que) or die(mysql_error());
		}
	}	


?>