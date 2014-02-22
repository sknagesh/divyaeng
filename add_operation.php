<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
print_r($_POST);
$drawid=$_POST['Drawing_ID'];
$opedesc=$_POST['opdesc'];
$itl=$_POST['itl'];
$rco=$_POST['rco'];
if(isSet($_POST['stime'])){$stime=$_POST['stime'];}else{$stime="";}
if(isSet($_POST['ctime'])){$ctime=$_POST['ctime'];}else{$ctime="";}
if(isSet($_POST['mtime'])){$mtime=$_POST['mtime'];}else{$mtime="";}
if(isSet($_POST['fixtno'])){$fixtno=$_POST['fixtno'];}else{$fixtno="";}
if(isSet($_POST['progno'])){$progno=$_POST['progno'];}else{$progno="";}
if(isSet($_POST['ppath'])){$ppath=$_POST['ppath'];}else{$ppath="";}
if(isSet($_POST['onote'])){$onote=$_POST['onote'];}else{$onote="";}

if($fixtno!='')
{
$fxtno=explode(',', $fixtno);
}

$oimgfiles=count($_FILES['oimg']['name']);

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


////operation images
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

///stage drawing
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


///pin and gage list

if((isSet($_FILES['gage']['name']))&&($_FILES['gage']['name']!=''))
{
	$gagefileName = $drawid."-gage-".$_FILES['gage']['name'];
	$gagetmpName = $_FILES['gage']['tmp_name'];
	$gagefileSize = $_FILES['gage']['size'];
	$gagefileType = $_FILES['gage']['type'];
	$gagefilePath = $uploadDir . $gagefileName;
	$oresult = move_uploaded_file($gagetmpName, $gagefilePath);
	if (!$oresult) {
						echo "<br>Error uploading Operation Drawing $odrgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$gagefileName = addslashes($gagefileName);
						$gagefilePath = addslashes($gagefilePath);
						}

}else{$gagefileName='';}




$query="INSERT INTO Operation (Drawing_ID,
								Operation_Desc,
								Setup_Time,
								Clamping_Time,
								Machining_Time,
								Program_NO,
								NC_Prog_Path,
								Operation_Notes,
								Stage_Drawing_Path,
								In_Tool_List,
								Only_In_Route_card,
								Gage_List) 
	 						VALUES('$drawid',
									'$opedesc',
									'$sltime',
									'$cltime',
									'$mctime',
									'$progno',
									'$ppath',
									'$onote',
									'$odrgfileName',
									'$itl',
									'$rco',
									'$gagefileName');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
	$oid=mysql_insert_id();
	if($fixtno!='')
	{
		$j=count($fxtno);
		for ($i=0; $i < $j; $i++) { 
		$que="INSERT INTO Ope_Fixt_Map (Operation_ID,Fixture_NO) VALUES( $oid,'$fxtno[$i]');";
	//		print($que);
			$resf=mysql_query($que) or die(mysql_error());
		}
	}	

		for ($i=0; $i < $oimgfiles; $i++) { 
			$quef="INSERT INTO Operation_Image (Operation_ID,Operation_Image_Path) VALUES( $oid,'$drgfileNames[$i]');";
//			print($quef);
			$resf=mysql_query($quef) or die(mysql_error());
	}
	
	
	
print("Added new Operaton $opedesc");	
	
}else
	{
		print("Error Adding Operation");
	}


?>