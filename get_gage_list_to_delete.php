<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$filter = $_GET['gid'];
$ipd="";
if($filter!=0)
{
	$ipd="<table border=\"1\" cellspacing=\"1\" id=\"inprocesstble\">";
	$ipd.= "<tr><th>Delete?</th><th>Gage Description</th><th>Gage Sl No</th><th>Gate Pass No</th><th>Date Received</th><th>Gage Type</th></tr>";

//	$qry="SELECT * FROM Dimension WHERE Operation_ID=$filter AND Deleted=0;";

$qry="SELECT gsl.Gage_ID,Gage_SlNo_ID,Gage_Desc,Gate_Pass_No,DATE_FORMAT(Date_Received,'%d-%m-%Y') as d, Gage_Type,Gage_No FROM Gage_SlNo as gsl
		INNER JOIN Gage AS g ON g.Gage_ID=gsl.Gage_ID WHERE gsl.Gage_ID=$filter;";

$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$noofdimns=mysql_num_rows($resa);		

//print($qry);


	if($noofdimns==0) //if there are no dimns add fields so that we can add dimensions
	{
		$ipd=("No Gages found");
	}
//else show the dimensions already in the database
	else {
			$i=0;
	while ($row = mysql_fetch_assoc($resa))
        		{

		$ipd.= "<tr><td><input type=\"checkbox\" name=\"delgage[$i]\" id=\"delgage[$i]\" value=\"1\" /></input></td>";
		$ipd.="<input type=\"hidden\" name=\"Gage_SlNo_ID[$i]\" id=\"Gage_SlNo_ID[$i]\" value=\"$row[Gage_SlNo_ID]\"/>";
        $ipd.= "<td>$row[Gage_Desc]</td>";
		$ipd.= "<td>$row[Gage_No]</td>";
		$ipd.= "<td>$row[Gate_Pass_No]</td>";
		$ipd.= "<td>$row[d]</td>";
		$ipd.= "<td>$row[Gage_Type]</td></tr>";
		

		$i++;
		        }
		$ipd.='</table>';
}
}
	echo( $ipd );
	
	
?>
