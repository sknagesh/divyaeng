<?php
include('dewdb.inc');
$i=$_GET['filter']-1;
$tl=$_GET['tl'];
$tu=$_GET['tu'];
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());


        $seli='';
        $ipd= "<tr><td><input type=\"text\" name=\"baloonno[$i]\" id=\"baloonno[$i]\" size=\"7\" class=\"required number\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"dimndesc[$i]\" id=\"dimndesc[$i]\" size=\"7\"/ class=\"required\"></td>";
		$ipd.= "<td><input type=\"text\" name=\"basicdimn[$i]\" id=\"basicdimn[$i]\" size=\"7\" class=\"required number\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tollower[$i]\" id=\"tollower[$i]\" size=\"7\"class=\"number\" value=\"$tl\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"tolupper[$i]\" id=\"tolupper[$i]\" size=\"7\" class=\"number\" value=\"$tu\"/></td>";
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
		$ipd.= "<td><input type=\"radio\" name=\"textfield[$i]\" id=\"textfield[$i]\" value=\"1\" Checked/>Yes</input>";
		$ipd.= "<input type=\"radio\" name=\"textfield[$i]\" id=\"textfield[$i]\" value=\"0\" />No</input></td>";
		$ipd.= "<td><input type=\"text\" name=\"comm1[$i]\" id=\"comm1[$i]\" size=\"7\"/></td>";
		$ipd.= "<td><input type=\"text\" name=\"comm2[$i]\" id=\"comm2[$i]\" size=\"7\"/></td>";
		$ipd.= "<td><input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"1\"/>Yes</input>";
		$ipd.= "<input type=\"radio\" name=\"proddimn[$i]\" id=\"proddimn[$i]\" value=\"0\"  Checked/>No</input></td>";
		$ipd.= "<td><input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"1\"/>Yes</input>";
		$ipd.= "<input type=\"radio\" name=\"compulsary[$i]\" id=\"compulsary[$i]\" value=\"0\"  Checked/>No</input></td></tr>";


	echo( $ipd );
	
	
?>