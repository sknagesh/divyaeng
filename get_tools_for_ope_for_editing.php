<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$opid=$_GET['opid'];

$query="SELECT * FROM Ope_Tool WHERE Operation_ID='$opid';";

//print($query);

$res=mysql_query($query) or die(mysql_error());
$r=mysql_num_rows($res);
if($r!=0)
{
echo "<table border=\"1\" cellspacing=\"1\" bgcolor=\"#7FFFD4\">";
echo "<tr><th>Delete Tool</th><th>Preferred Tool</th><th>Alternate Tool</th><th>Holder 1</th><th>Holder 2</th>
		<th>Work Description</th><th>Tool Overhang</th><th>Tool Life</th><th>Storage Location</th><th>Tool Image</th></tr>";
$i=0;

while($row=mysql_fetch_assoc($res))
{

echo '<tr><td><input type="checkbox" name="del['.$i.']" id="del['.$i.']" value="'.$row[Ope_Tool_ID].'"></td>';

echo '<td><input type="hidden" name="Ope_Tool_ID['.$i.']" value='.$row[Ope_Tool_ID].' >';



//preferred tool
$qtype="SELECT Tool_Type_ID,Tool_Dia From Tool Where Tool_ID='$row[Tool_ID_1]';";

$rtyper=mysql_query($qtype) or die(mysql_error());
$rtype=mysql_fetch_assoc($rtyper);


$qtool="SELECT Tool_ID,Tool_Part_NO,Tool_Desc,Brand_Description FROM Tool as t 
		INNER JOIN Tool_Brand as tb ON tb.Brand_ID=t.Brand_ID WHERE Tool_Type_ID='$rtype[Tool_Type_ID]' AND Tool_Dia='$rtype[Tool_Dia]';";
$rtool=mysql_query($qtool) or die(mysql_error());

echo '<select name="Tool_ID_1['.$i.']" id="Tool_ID_1['.$i.']" class="required">
	<option value="">Select Tool</option>';

while ($tlist=mysql_fetch_assoc($rtool))
{
if($tlist[Tool_ID]==$row[Tool_ID_1]){$sel="selected=selected";}else{$sel="";}

echo '<option value="'.$tlist[Tool_ID].'"'. $sel.'>';
echo $tlist[Brand_Description].' Make '.$tlist[Tool_Desc].'</option>';
}
echo '</select>';
if(($rtype['Tool_Type_ID']=='5')||($rtype['Tool_Type_ID']=='6'))
{
$qins1="SELECT * FROM Inserts WHERE Tool_ID='$row[Tool_ID_1]';";
$rin1=mysql_query($qins1) or die(mysql_error());

echo '<select name="Insert_ID_1['.$i.']" id="Insert_ID_1['.$i.']" class="required">
	<option value="">Select Insert</option>';

while ($ins1=mysql_fetch_assoc($rin1))
{
if($ins1[Insert_ID]==$row[Insert_ID_1]){$sel="selected=selected";}else{$sel="";}

echo '<option value="'.$ins1[Insert_ID].'"'. $sel.'>';
echo $ins1[Insert_Part_NO].'</option>';
}
echo '</select>';
}
echo '</td>';




//alternate tool

$qtypea="SELECT Tool_Type_ID,Tool_Dia From Tool Where Tool_ID='$row[Tool_ID_1]';";
$rtypear=mysql_query($qtypea) or die(mysql_error());
$rtypea=mysql_fetch_assoc($rtypear);


$qtoola="SELECT Tool_ID,Tool_Part_NO,Tool_Desc,Brand_Description FROM Tool as t 
		INNER JOIN Tool_Brand as tb ON tb.Brand_ID=t.Brand_ID WHERE Tool_Type_ID='$rtypea[Tool_Type_ID]' AND Tool_Dia='$rtypea[Tool_Dia]';";
$rtoola=mysql_query($qtoola) or die(mysql_error());

echo '<td><select name="Tool_ID_2['.$i.']" id="Tool_ID_2['.$i.']">
	<option value="">Select Tool</option>';

while ($tlista=mysql_fetch_assoc($rtoola))
{

if($tlista[Tool_ID]==$row[Tool_ID_2]){$sel="selected=selected";}else{$sel="";}
echo '<option value="'.$tlista[Tool_ID].'" '.$sel.'>';
echo $tlista[Brand_Description].' Make '.$tlista[Tool_Desc].'</option>';
}
echo '</select>';


if(($rtype['Tool_Type_ID']=='5')||($rtype['Tool_Type_ID']=='6'))
{
$qins2="SELECT * FROM Inserts WHERE Tool_ID='$row[Tool_ID_2]';";
$rin2=mysql_query($qins2) or die(mysql_error());

echo '<select name="Insert_ID_2['.$i.']" id="Insert_ID_2['.$i.']" >
	<option value="">Select Insert</option>';

while ($ins2=mysql_fetch_assoc($rin2))
{
if($ins2[Insert_ID]==$row[Insert_ID_2]){$sel="selected=selected";}else{$sel="";}

echo '<option value="'.$ins2[Insert_ID].'"'. $sel.'>';
echo $ins2[Insert_Part_NO].'</option>';
}
echo '</select>';
}
echo '</td>';


//preferred holder
$qhol="SELECT * FROM Holder;";
$rhol=mysql_query($qhol) or die(mysql_error());

echo '<td style=width:30px><select name="Holder_ID_1['.$i.']" id="Holder_ID_1" class="required">';
echo '<option value="">Select Holder</option>';
while ($rowh = mysql_fetch_assoc($rhol))
{
if($rowh[Holder_ID]==$row[Holder_ID_1]){$sel="selected=selected";}else{$sel="";}

echo '<option value='.$rowh[Holder_ID].' '.$sel.' >'.$rowh[Holder_Description].'</option>';
}
echo '</select></td>';


//alternate holder
$qhol="SELECT * FROM Holder;";
$rhol=mysql_query($qhol) or die(mysql_error());

echo '<td><select name="Holder_ID_2['.$i.']" id="Holder_ID_2">';
echo '<option value="">Select Holder</option>';
while ($rowh = mysql_fetch_assoc($rhol))
{
if($rowh[Holder_ID]==$row[Holder_ID_2]){$sel="selected=selected";}else{$sel="";}

echo '<option value='.$rowh[Holder_ID].' '.$sel.' >'.$rowh[Holder_Description].'</option>';
}
echo '</select></td>';


echo '<td><input type="text" name="mdesc['.$i.']" id="mdesc['.$i.']" value="'.$row[Ope_Tool_Desc].'"></td>';

echo '<td><input type="text" name="toh['.$i.']" id="toh['.$i.']" size="10" value="'.$row[Ope_Tool_OH].'"></td>';
echo '<td><input type="text" name="tlife['.$i.']" id="tlife['.$i.']" size="10" value="'.$row[Ope_Tool_Life].'"></td>';
echo '<td><input type="text" name="tsl['.$i.']" id="tsl['.$i.']" size="10" value="'.$row[Storage_Location].'"></td>';
echo '<td><input type="file" name="timg['.$i.']"  size="10" id="timg['.$i.']" >'.$row[Ope_Tool_Image_Path].'</td></tr>';

$i++;

}
echo "</table>";


}
else {
	echo "No Tools Added For this Drawing Yet";
}


?>

