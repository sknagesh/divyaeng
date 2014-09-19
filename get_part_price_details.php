<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];
//print_r($_POST);
$query="SELECT Part_Price FROM Component WHERE Drawing_ID='$drawingid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

$row = mysql_fetch_assoc($resa);

	$pprice=$row['Part_Price'];
	if($pprice!='')
  {

print("
   <p>
     <label>Part Price</label>
     <input id=\"price\" name=\"price\" size=\"25\"  class=\"required number\" value=\"$pprice\"/>
   </p>");

}else{
 print("<p>
     <label>Part Price</label>
     <input id=\"price\" name=\"price\" size=\"25\"  class=\"required number\"/>
   </p>");

}

?>