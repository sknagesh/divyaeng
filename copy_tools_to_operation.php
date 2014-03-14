<?php
include('dewdb.inc');

$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


//print_r($_POST);


$soid=$_POST['Operation_IDs'];
$doid=$_POST['Operation_IDd'];
$tcopy=$_POST['tcopy'];

$q="SELECT * FROM Ope_Tool WHERE Operation_ID='$soid';";
//print("<br>$q");
$res = mysql_query($q, $cxn) or die(mysql_error($cxn));
$n=0;
$nr=mysql_num_rows($res);
if($nr!=0)
{
	$i=0;
	while($row=mysql_fetch_assoc($res))
	{

			if(isSet($tcopy[$i]))
			{
				$qd="INSERT INTO Ope_Tool 
					(Operation_ID,
					Tool_ID_1,Insert_ID_1,Holder_ID_1,
					Tool_ID_2,Insert_ID_2,Holder_ID_2,
					Ope_Tool_Desc,Ope_Tool_OH,Ope_Tool_Life,
					Storage_Location,Ope_Tool_Image_Path)
					VALUES ('$doid',
							'$row[Tool_ID_1]','$row[Insert_ID_1]','$row[Holder_ID_1]',
							'$row[Tool_ID_2]','$row[Insert_ID_2]','$row[Holder_ID_2]',
							'$row[Ope_Tool_Desc]','$row[Ope_Tool_OH]','$row[Ope_Tool_Life]',
							'$row[Storage_Location]','$row[Ope_Tool_Image_Path]');";
			//print("<br>$qd");
				$resd = mysql_query($qd, $cxn) or die(mysql_error($cxn));
			$n++;
		}
		$i++;
	}
}else{
	print("<br>no tools added for this operation");
}


print("<br>Copied $n tools");


?>