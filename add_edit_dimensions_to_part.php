<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
print_r($_POST);
/*
$operationid=$_POST['Operation_ID'];
$baloonno=$_POST['baloonno'];
$basicdimn=$_POST['basicdimn'];
$tollower=$_POST['tollower'];
$tolupper=$_POST['tolupper'];
$instrumentid=$_POST['Instrument_ID'];
$proddimn=$_POST['proddimn'];
$textfield=$_POST['textfield'];
$dimndesc=$_POST['dimndesc'];
$compulsary=$_POST['compulsary'];
$stagedimn=$_POST['stagedimn'];
if(isSet($_POST['Dimension_ID'])){$ipid=$_POST['Dimension_ID'];}else{$ipid="";}
$len=count($instrumentid);
$comm1=$_POST['comm1'];
$comm2=$_POST['comm2'];


$a=0;
$updated=0;
$newdimn=0;
while ($a <= $len-1) {
	
	//if($ipid[$a]!="")
	if(isSet($ipid[$a]))
	{
$qry="UPDATE Dimension SET 	Baloon_NO='$baloonno[$a]',
							Basic_Dimn='$basicdimn[$a]',
							Desc_ID='$dimndesc[$a]',
							Tol_Lower='$tollower[$a]',
							Tol_Upper='$tolupper[$a]',
							Instrument_ID='$instrumentid[$a]',
							Prod_Dimn='$proddimn[$a]',
							Text_Field='$textfield[$a]',
							Comment_1='$comm1[$a]',
							Comment_2='$comm2[$a]',
							Operation_ID='$operationid',
							Compulsary_Dimn='$compulsary[$a]',
							Stage_Dimension='$stagedimn[$a]'
		WHERE Dimension_ID='$ipid[$a]'";
//print("$qry");
$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$result=mysql_affected_rows($cxn);
if($result!=0){$updated+=1;}
	}else 	//if($ipid[$a]=="")
	{
$qry="INSERT INTO Dimension (Baloon_NO,Basic_Dimn,Desc_ID,Tol_Lower,Tol_Upper,Instrument_ID,Prod_Dimn,Text_Field,Operation_ID,Compulsary_Dimn,Comment_1,Comment_2,Stage_Dimension) ";
$qry.="VALUES('$baloonno[$a]','$basicdimn[$a]','$dimndesc[$a]','$tollower[$a]','$tolupper[$a]','$instrumentid[$a]','$proddimn[$a]','$textfield[$a]','$operationid','$compulsary[$a]','$comm1[$a]','$comm2[$a]','$stagedimn[$a]');";
print("$qry");
$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$result=mysql_affected_rows($cxn);
if($result!=0){$newdimn+=1;}
	}
		
$a++;	
}
print(" $updated Dimensions Updated and $newdimn New Dimensions Added");
*/
?>