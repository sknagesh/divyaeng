<?php
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());
//print_r($_GET);
$custid=$_GET['custid'];


$query="SELECT * FROM Customer WHERE Customer_ID='$custid';";
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
$row = mysql_fetch_assoc($resa);

print("<fieldset>
	<legend>Add New Customer's Details</legend>
   <p>
     <label>Customer's Name</label>
     <input id=\"cname\" name=\"name\" size=\"25\" class=\"required\" value=\"$row[Customer_Name]\"/>
   </p>

   <p>
     <label>Customer's Short Name</label>
     <input id=\"csname\" name=\"sname\" size=\"25\" class=\"required\" value=\"$row[Customer_Name_Short]\"/>
   </p>

   <p>
     <label>Contact Person's Name</label>
     <input id=\"cper\" name=\"cper\" size=\"25\" value=\"$row[Contact_Person]\"/>
   </p>

   <p>
     <label>Address Line 1</label>
     <input id=\"addl1\" name=\"addl1\" size=\"25\"  class=\"required\" value=\"$row[Address_L1]\" />
   </p>
   <p>
     <label>Address Line 2</label>
     <input id=\"addl2\" name=\"addl2\" size=\"25\" value=\"$row[Address_L2]\" />
   </p>
   <p>
     <label>Phone No</label>
     <input id=\"phone\" name=\"phone\" size=\"25\" value=\"$row[Phone_NO]\" />
   </p>
   <p>
     <label>TIN No</label>
     <input id=\"tinno\" name=\"tinno\" size=\"25\" class=\"required\" value=\"$row[TIN_NO]\"/>
   </p>
   <p>
     <label>PAN No</label>
     <input id=\"panno\" name=\"panno\" size=\"25\" value=\"$row[PAN_NO]\"/>
   </p>

   <p>
     <label>Excise Details</label>
     <input id=\"excise\" name=\"excise\" size=\"25\" value=\"$row[Excise_NO]\" />
   </p>

 </fieldset>");


?>