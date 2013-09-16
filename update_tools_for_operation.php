<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/drawings/';
//print_r($_POST);

//print_r($_FILES);
$opid=$_POST['Operation_ID'];
$opeid=$_POST['Ope_Tool_ID'];
if(isSet($_POST['Insert_ID_1'])){$insertid1=$_POST['Insert_ID_1'];}else{$insertid1='';}
if(isSet($_POST['Insert_ID_2'])){$insertid2=$_POST['Insert_ID_2'];}else{$insertid2='';}
$toolid2=$_POST['Tool_ID_2'];
$toolid1=$_POST['Tool_ID_1'];
$holderid1=$_POST['Holder_ID_1'];
$holderid2=$_POST['Holder_ID_2'];
if(isSet($_POST['tsl'])){$tsl=$_POST['tsl'];}else{$tsl='';}
$mdesc=$_POST['mdesc'];
if(isSet($_POST['toh'])){$toh=$_POST['toh'];}else{$toh='';}
if(isSet($_POST['tlife'])){$tlife=$_POST['tlife'];}else{$tlife='';}

if(isSet($_POST['del'])){$del=$_POST['del'];}else{$del='';}


$timgfiles=count($_FILES['timg']['name']);

if($timgfiles!='')
{


foreach ($_FILES['timg']['name'] as $key => $name) {
		
if($_FILES['timg']['name'][$key]!='')
{
	$drgfileName = $opid."-".$_FILES['timg']['name'][$key];
	$drgtmpName = $_FILES['timg']['tmp_name'][$key];
	$drgfileSize = $_FILES['timg']['size'][$key];
	$drgfileType = $_FILES['timg']['type'][$key];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	if (!$result) {
						echo "<br>Error uploading Tool Image $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$odrgfileNames[$key] = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}
						}
}


}else{$odrgfileNames='';}



$nooftools=count($toolid1);
//print("<br>no of tools=$nooftools");
for($x=0;$x<$nooftools;$x++)
{
//print("<p>x=$x");
if(isSet($insertid1[$x])){$ins1="Insert_ID_1='$insertid1[$x]',";}else{$ins1="";}
if(isSet($toolid2[$x])){$tid2="Tool_ID_2='$toolid2[$x]',";}else{$tid2="";}
if(isSet($insertid2[$x])){$ins2="Insert_ID_2='$insertid2[$x]',";}else{$ins2="";}
if(isSet($tlife[$x])){$tlif="Ope_Tool_Life='$tlife[$x]',";}else{$tlif="";}
if(isSet($odrgfileNames[$x])){$tpath="Ope_Tool_Image_Path='$odrgfileNames[$x]',";}else{$tpath="";}

$query="UPDATE Ope_Tool SET
			Tool_ID_1='$toolid1[$x]',
			$ins1 $tid2 $ins2 $tlif $tpath
				Holder_ID_1='$holderid1[$x]',
				Holder_ID_2='$holderid2[$x]',
				Ope_Tool_Desc='$mdesc[$x]',
				Ope_Tool_OH='$toh[$x]',
				Storage_Location='$tsl[$x]'
				WHERE Ope_Tool_ID='$opeid[$x]';";

//			print($query);
//			print("<p>");
			$res=mysql_query($query) or die(mysql_error());

			$result=mysql_affected_rows();

}


if($del!='')
{
$delids=count($del);

$delid=array_values($del); //re-order array

	for ($j=0; $j<$delids; $j++)
	{
//delete image is associated with tool
		$qf="SELECT Ope_Tool_Image_Path FROM Ope_Tool WHERE Ope_Tool_ID=$delid[$j];";
		$rf=mysql_query($qf) or die(mysql_error());
		$rfr=mysql_fetch_assoc($rf);
		if($rfr!=''){
		$filepath=$uploadDir.$rfr['Ope_Tool_Image_Path'];
		print($filepath);
		unlink($filepath);
		}	
		$q="DELETE FROM Ope_Tool WHERE Ope_Tool_ID=$delid[$j];";
		$rf=mysql_query($q) or die(mysql_error());
		}
}



?>
