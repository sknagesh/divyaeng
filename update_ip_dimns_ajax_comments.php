<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$filter = $_GET['opid'];
$ipd="";
if($filter!=0)
{
	$ipd="<table border=\"1\" cellspacing=\"1\" id=\"inprocesstble\">";
	$ipd.= "<tr><th>Baloon No</th><th>Dimn. Desc</th><th>Basic dimn</th><th>Tol. Lower</th><th>Tol Upper</th>
			<th>Instrument ID</th><th>Stage Dimn?</th><th>Text Field?</th><th>Comment</th>
			<th>Production Dimn?</th><th>Compulsary Dimn?</th><th>Delete Dimn?</th></tr>";

	$qry="SELECT * FROM Dimension WHERE Operation_ID=$filter AND Deleted=0;";

$resa = mysql_query($qry, $cxn) or die(mysql_error($cxn));
$noofdimns=mysql_num_rows($resa);		




	if($noofdimns==0) //if there are no dimns add fields so that we can add dimensions
	{
	$i=0;
        $ipd.= "<tr><td><input type=\"text\" name=\"baloonno[$i]\" id=\"baloonno[$i]\" class=\"required number\" size=\"5\"/></td>";
		$qdd="select * from Dimn_Desc";
		$res = mysql_query($qdd, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"dimndesc[$i]\" id=\"dimndesc[$i]\" class=\"required\">";
		$ipd.="<option value=\"\">Select Description</option>";
		while ($r = mysql_fetch_assoc($res))
		{
		$ipd.="<option value=\"$r[Desc_ID]\"";
		$ipd.=" >";
		$ipd.="$r[Dimn_Desc]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"text\" name=\"basicdimn[$i]\" id=\"basicdimn[$i]\" class=\"required number\" size=\"7\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tollower[$i]\" id=\"tollower[$i]\" size=\"5\" class=\"number\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tolupper[$i]\" id=\"tolupper[$i]\" size=\"5\" class=\"number\"/></td>";
		$q="select * from Instrument";
		$res = mysql_query($q, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"Instrument_ID[$i]\" id=\"Instrument_ID[$i]\" class=\"required\">";
		while ($r = mysql_fetch_assoc($res))
		{if($r[Instrument_ID]==10){$seli='Selected=Selected';}else{$seli='';}
		$ipd.="<option value=\"$r[Instrument_ID]\"";
		$ipd.=" $seli>\"";
		$ipd.="$r[Instrument_SLNO]-$r[Instrument_Description]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"radio\" name=\"stagedimn[$i]\" value=\"1\" />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"stagedimn[$i]\" value=\"0\" Checked/>N</input></td>";
		$ipd.= "<td><input type=\"radio\" name=\"textfield[$i]\" id=\"textfield[$i]\" value=\"1\" Checked />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"textfield[$i]\" id=\"textfield[$i]\" value=\"0\" />N</input></td>";
		$ipd.= "<td><div id=\"comm$i\"/> </div></td>";
		$ipd.= "<td><input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"1\" />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"0\" Checked/>N</input></td>";
		$ipd.= "<td><input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"1\" />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"0\" Checked />N</input></td></tr>";
		$ipd.='</table>';
		$ipd.="<table border=\"1px\" cellspacing=\"1px\" id=\"bottomtable\">";
		$ipd.="<tr><td><input type=\"submit\" id=\"submit\"/></input></td>";
    	$ipd.="<td><input type=\"button\" id=\"Add\" class=\"new-button\" name=\"Add\" value=\"Add\" onClick=\"addrow()\"/></input>";
    	$ipd.="<td><input type=\"button\" id=\"Del\" class=\"new-button\" name=\"Delete\" value=\"Delete\" onClick=\"delrow()\"/></input></td></tr></table></form>";
		$ipd.="</table>";
		$ipd.="</form>";
	}
//else show the dimensions already in the database
	else {
			$i=0;
	while ($row = mysql_fetch_assoc($resa))
        		{
        	$tf1="";$tf0="";$pd1="";$pd0="";$cd1="";$cd0="";$sd1='';$sd0='';
        	if($row['Text_Field']==1){$tf1="Checked";} else{$tf0="Checked";};
			if($row['Prod_Dimn']==1){$pd1="Checked";} else{$pd0="Checked";};
			if($row['Compulsary_Dimn']==1){$cd1="Checked";} else{$cd0="Checked";};
			if($row['Stage_Dimension']==1){$sd1="Checked";} else{$sd0="Checked";};
        $ipd.= "<tr><td><input type=\"text\" name=\"baloonno[$i]\" id=\"baloonno[$i]\" value=\"$row[Baloon_NO]\" class=\"required number\"size=\"5\" /></td>";
		$qdd="select * from Dimn_Desc";
		$res = mysql_query($qdd, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"dimndesc[$i]\" id=\"dimndesc[$i]\" class=\"required\">";
		while ($r = mysql_fetch_assoc($res))
		{if($r['Desc_ID']==$row['Desc_ID']){$ipd.="Selected=Selected";}
		$ipd.="<option value=\"$r[Desc_ID]\"";
		$ipd.=" >";
		$ipd.="$r[Dimn_Desc]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"text\" name=\"basicdimn[$i]\" id=\"basicdimn[$i]\" value=\"$row[Basic_Dimn]\" class=\"required number\"size=\"7\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tollower[$i]\" id=\"tollower[$i]\" value=\"$row[Tol_Lower]\" class=\"number\" size=\"6\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tolupper[$i]\" id=\"tolupper[$i]\" value=\"$row[Tol_Upper]\" class=\"number\" size=\"6\"/></td>";
		$q="select * from Instrument";
		$res = mysql_query($q, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"Instrument_ID[$i]\" id=\"Instrument_ID[$i]\" class=\"required\" >";
		while ($r = mysql_fetch_assoc($res))
		{
		$ipd.="<option value=\"$r[Instrument_ID]\"";
		 if($r['Instrument_ID']==$row['Instrument_ID']){$ipd.="Selected=Selected";}
		 $ipd.=" >\"";
		$ipd.="$r[Instrument_SLNO]-$r[Instrument_Description]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"radio\" name=\"stagedimn[$i]\" value=\"1\" $sd1/>Y</input>";
		$ipd.= "<input type=\"radio\" name=\"stagedimn[$i]\" value=\"0\" $sd0 />N</input></td>";
		$ipd.= "<td><input type=\"radio\" name=\"textfield[$i]\" id=\"textfield[$i]\" value=\"1\" $tf1 />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"textfield[$i]\" id=\"textfield[$i]\" value=\"0\" $tf0 />N</input></td>";
		$ipd.= "<td><div id=\"comm$i\"/> </div></td>";
		$ipd.= "<td><input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"1\" $pd1 />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"0\" $pd0 />N</input></td>";
		$ipd.= "<td><input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"1\" $cd1 />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"0\" $cd0 />N</input></td>";
		$ipd.= "<td><input type=\"checkbox\" name=\"deldimn[$i]\" id=\"deldimn[$i]\" value=\"1\" /></input></td></tr>";
		$ipd.="<input type=\"hidden\" name=\"Dimension_ID[$i]\" id=\"Dimension_ID[$i]\" value=\"$row[Dimension_ID]\"/>";
		$i++;
		        }
		$ipd.='</table>';
		$ipd.="<table border=\"1px\" cellspacing=\"1px\" id=\"bottomtable\">";
		$ipd.="<tr><td><input type=\"submit\" id=\"submit\"/></input></td>";
    	$ipd.="<td><input type=\"button\" id=\"Add\" class=\"new-button\" name=\"Add\" value=\"Add\" onClick=\"addrow()\"/></input>";
    	$ipd.="<td><input type=\"button\" id=\"Del\" class=\"new-button\" name=\"Delete\" value=\"Delete\" onClick=\"delrow()\"/></input></td></tr></table></form>";
		$ipd.="</table>";
		$ipd.="</form>";

}
}
	echo( $ipd );
	
	
?>