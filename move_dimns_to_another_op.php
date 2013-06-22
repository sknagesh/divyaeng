<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);

$sopid=$_POST['Operation_IDS'];
$dopid=$_POST['Operation_IDD'];
$ipid=$_POST['Dimension_ID'];
$movedimn=$_POST['movedimn'];
$action=$_POST['move'];
$len=count($ipid);



if($action=='move')
{
$a=0;
$moved=0;

while ($a <= $len-1) {
	
	//if($ipid[$a]!="")
	if(isSet($ipid[$a]))
	{
		if(isSet($movedimn[$a]))
		{
$qry="UPDATE Dimension SET 	Operation_ID='$dopid' WHERE Dimension_ID='$ipid[$a]'";
//print("$qry");
$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$moved++;			
		}
	}
		
$a++;	
}

print(" $moved Dimensions Moved");


}else{
	
$a=0;
$copied=0;

while ($a <= $len-1) {
	
	//if($ipid[$a]!="")
	if(isSet($ipid[$a]))
	{
		if(isSet($movedimn[$a]))
		{
			
			$qc="SELECT * FROM Dimension WHERE Dimension_ID='$ipid[$a]';";
			$resc = mysql_query($qc, $cxn) or die(mysql_error($cxn));
		while($rowc=mysql_fetch_assoc($resc))
			{
				$qsc="INSERT INTO Dimension 
							(Operation_ID,
							Baloon_NO,
							Desc_ID,
							Basic_Dimn,
							Tol_Lower,
							Tol_Upper,
							Instrument_ID,
							Stage_Dimension,
							Text_Field,
							Compulsary_Dimn)

					VALUES('$dopid',
							'$rowc[Baloon_NO]',
							'$rowc[Desc_ID]',
							'$rowc[Basic_Dimn]',
							'$rowc[Tol_Lower]',
							'$rowc[Tol_Upper]',
							'$rowc[Instrument_ID]',
							'$rowc[Stage_Dimension]',
							'$rowc[Text_Field]',
							'$rowc[Compulsary_Dimn]');";

//	print("<br>$qsc<br>");
			$rescc = mysql_query($qsc, $cxn) or die(mysql_error($cxn));
			$copied++;			

			}						

		}
	}
		
$a++;	
}

print(" $copied Dimensions Copied");


	
	
	
}
?>