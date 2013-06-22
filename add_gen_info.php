<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$uploadDir = '/home/www/geninfo/';
//print_r($_POST);
//print_r($_FILES);
$bdesc=$_POST['bdesc'];


if((isSet($_FILES['info']['name']))&&$_FILES['info']['name']!='')
{
	$drgfileName = $_FILES['info']['name'];
	$drgtmpName = $_FILES['info']['tmp_name'];
	$drgfileSize = $_FILES['info']['size'];
	$drgfileType = $_FILES['info']['type'];
	$drgfilePath = $uploadDir . $drgfileName;
	$result = move_uploaded_file($drgtmpName, $drgfilePath);
	chmod($drgfilePath, 777);
	if (!$result) {
						echo "<br>Error Uploading Information Document $drgfileName";
						exit;
						}

	if(!get_magic_quotes_gpc())
						{
						$drgfileName = addslashes($drgfileName);
						$drgfilePath = addslashes($drgfilePath);
						}

}else{$drgfileName='';}


$query="INSERT INTO Gen_Info (Info_Description,Info_Path) VALUES('$bdesc','$drgfileName');";

//print($query);

$res=mysql_query($query) or die(mysql_error());

$result=mysql_affected_rows();
if($result!=0)
{
print("Added new Information $bdesc - $drgfileName");	
	
}else
	{
		print("Error Adding");
	}


?>