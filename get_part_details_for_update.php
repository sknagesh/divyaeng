<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
$drawingid=$_GET['drawingid'];
//print_r($_POST);
$query="SELECT * FROM Component WHERE Drawing_ID='$drawingid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));

$row = mysql_fetch_assoc($resa);

	$dpath=$row['Customer_Drawing'];
	$ppath=$row['Process_Sheet'];
	$preview=$row['Preview_Image'];
	$pname=$row['Component_Name'];
	$dno=$row['Drawing_NO'];
	$cmaterial=$row['Component_Material'];
	$cutblank=$row['Raw_Material_Size'];
	$premblank=$row['Pre_Machined_Blank_Size'];
	$finisize=$row['Finish_Size'];
	$drgid=$row['Drawing_ID'];
	$drgrev=$row['Drawing_Rev'];
	$swt=$row['Scrap_Weight'];

print("
   <p>
     <label>Drawing Number</label>
     <input id=\"drawingno\" name=\"drawingno\" size=\"25\"  class=\"required\" value=\"$dno\"/>
   </p>

   <p>
     <label>Revision Number</label>
     <input id=\"revno\" name=\"revno\" size=\"25\" value=\"$drgrev\"/>
   </p>

   <p>
     <label>Component Name</label>
     <input id=\"componentname\" name=\"componentname\" size=\"25\" class=\"required\" value=\"$pname\"/>
   </p>
   <p>
     <label>Component Material</label>
     <input id=\"mspec\" name=\"mspec\" size=\"25\"  value=\"$cmaterial\" />
   </p>

   <p>
     <label>Raw Material Size</label>
     <input id=\"cblank\" name=\"cblank\" size=\"25\"  value=\"$cutblank\" />
   </p>

   <p>
     <label>Pre Machined Blank Size</label>
     <input id=\"pmblank\" name=\"pmblank\" size=\"25\"  value=\"$premblank\" />
   </p>

   <p>
     <label>Finish Size</label>
     <input id=\"fsize\" name=\"fsize\" size=\"25\"  value=\"$finisize\" />
   </p>

   <p>
     <label>Scrap Weight</label>
     <input id=\"sweight\" name=\"sweight\" size=\"25\"  class=\"number\" value=\"$swt\"/>
   </p>


   <p>
     <label>Select Drawing </label>
     <input id=\"drg\" name=\"drg\" type=\"file\" />$dpath
   </p>

   <p>
     <label>Select Process Sheet </label>
     <input type=\"file\" id=\"process\" name=\"process\" />$ppath
   </p>

   <p>
     <label>Select Pre View Image </label>
     <input type=\"file\" id=\"preview\" name=\"preview\" />$preview
   </p>");



print("<input id=\"drgno\" name=\"Drawing_ID\" type=\"hidden\"  value=\"$drgid\" />");

?>