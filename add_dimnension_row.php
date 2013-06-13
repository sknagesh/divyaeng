<?php
include('dewdb.inc');
$i=$_GET['filter']-1;
$tl=$_GET['tl'];
$tu=$_GET['tu'];
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


        $seli='';
        $ipd= "<tr><td><input type=\"text\" name=\"baloonno[$i]\" id=\"baloonno[$i]\" class=\"required number\" size=\"5\"/></td>";
		$qdd="select * from Dimn_Desc";
		$res = mysql_query($qdd, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"dimndesc[$i]\" id=\"dimndesc[$i]\" class=\"required\">";
		$ipd.="<option value=\"\">Select Description</option>";
		while ($r = mysql_fetch_assoc($res))
		{
		$ipd.="<option value=\"$r[Desc_ID]\"";
		$ipd.=" >";
		$ipd.="$r[Detailed_Desc]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"text\" name=\"basicdimn[$i]\" id=\"basicdimn[$i]\" class=\"required number\" size=\"7\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tollower[$i]\" id=\"tollower[$i]\" class=\"number\" value=\"$tl\" size=\"5\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tolupper[$i]\" id=\"tolupper[$i]\" class=\"number\" value=\"$tu\" size=\"5\"/></td>";
		$q="select * from Instrument";
		$res = mysql_query($q, $cxn) or die(mysql_error($cxn));
		$ipd.="<td><select name=\"Instrument_ID[$i]\" id=\"Instrument_ID[$i]\" class=\"required\" >";
		while ($r = mysql_fetch_assoc($res))
		{
			if($r[Instrument_ID]==10){$seli='Selected=Selected';}else{$seli='';}
		$ipd.="<option value=\"$r[Instrument_ID]\"";
		 $ipd.=" $seli>\"";
		$ipd.="$r[Instrument_SLNO]-$r[Instrument_Description]</option>";
 		}
		$ipd.="</select></td>";
		$ipd.= "<td><input type=\"radio\" name=\"stagedimn[$i]\" value=\"1\" />Y</input>";
		$ipd.= "<input type=\"radio\" name=\"stagedimn[$i]\" value=\"0\" Checked />N</input></td>";
		$ipd.= "<td><div id=\"textfield$i\"/></div><input type=\"hidden\" name=\"textfield[$i]\"/></td>";
		$ipd.= "<td><input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"1\"/>Y</input>";
		$ipd.= "<input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"0\"  Checked/>N</input></td>";
		$ipd.= "<td><input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"1\"/>Y</input>";
		$ipd.= "<input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"0\"  Checked/>No</input></td></tr>";


	echo( $ipd );
	
	
?>
