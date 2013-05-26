<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$i=$_GET['id'];
if(isSet($_GET['cid'])){$did=$_GET['did'];}else{$did='';}
$query="SELECT * FROM Dimn_Comment;";
print("<select name=\"Comment[$i]\" id=\"comment[$i]\" class=\"required\">");
echo '<option value="">Select Comment</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
	if($row['Comment_ID']==$did){$sel="selected=\"selected\"";}else{$sel="";}
echo "<option value=".$row['Comment_ID']." $sel >";
echo "$row[Comment_Type]</option>";
}
print("</select>");

?>