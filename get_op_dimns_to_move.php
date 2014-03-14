<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$filter = $_GET['opid'];
$ipd="";
if($filter!=0)
{

	$qry="SELECT * FROM Dimension WHERE Operation_ID=$filter AND Deleted=0;";

$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$noofdimns=mysql_num_rows($resa);		




	if($noofdimns==0) //if there are no dimns add fields so that we can add dimensions
	{
	$ipd="No Dimensions Added For This Operation";

	}
//else show the dimensions already in the database
	else {
	$ipd="<table border=\"1\" cellspacing=\"1\" id=\"inprocesstble\">";
	$ipd.= "<tr><th>Dimension ID</th><th>Baloon No</th><th>Dimn. Desc</th><th>Basic dimn</th><th>Move</th></tr>";

			$i=0;
	while ($row = mysql_fetch_assoc($resa))
        		{
        	
		$ipd.="<tr><td>$row[Dimension_ID]</td>";
        $ipd.= "<td><input type=\"text\" name=\"baloonno[$i]\" id=\"baloonno[$i]\" value=\"$row[Baloon_NO]\" class=\"required number\"size=\"5\" /></td>";
		$qdd="select * from Dimn_Desc";
		$res = mysql_query($qdd, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"dimndesc[$i]\" id=\"dimndesc[$i]\" class=\"required\">";
		while ($r = mysql_fetch_assoc($res))
		{if($r['Desc_ID']==$row['Desc_ID']){$sel="Selected=Selected";}else{$sel='';}
		$ipd.="<option value=\"$r[Desc_ID]\"";
		$ipd.="$sel >";
		$ipd.="$r[Detailed_Desc]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"text\" name=\"basicdimn[$i]\" id=\"basicdimn[$i]\" value=\"$row[Basic_Dimn]\" size=\"7\"/></td>";
		$ipd.= "<td><input type=\"checkbox\" name=\"movedimn[$i]\" id=\"movedimn[$i]\" value=\"1\" /></input></td></tr>";
		$ipd.="<input type=\"hidden\" name=\"Dimension_ID[$i]\" id=\"Dimension_ID[$i]\" value=\"$row[Dimension_ID]\"/>";
		$i++;
		        }
		$ipd.='</table>';
		$ipd.="<table border=\"1px\" cellspacing=\"1px\" id=\"bottomtable\">";
		$ipd.="<tr><td><input type=\"submit\" id=\"submit\"/></input></td>";
		$ipd.="</table>";
		$ipd.="</form>";

}
}
	echo( $ipd );
	
	
?>
