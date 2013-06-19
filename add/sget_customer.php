<?php
include('dewdb.inc');
if(isSet($_GET['cid'])){$cid=$_GET['cid'];}else{$cid='';}
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('ShopLog',$cxn) or die("error opening db: ".mysql_error());
//mysql_select_db('Process',$cxn) or die("error opening db: ".mysql_error());
//print_r($_POST);
$query="SELECT * FROM Customer;";
print("<label>Select Customer</label>");
print("<td><dyna><select name=\"sCustomer_ID\" id=\"sCustomer_ID\" class=\"required\">");
echo '<option value="">Select Customer</option>';
$resa = mysql_query($query, $cxn) or die(mysql_error($cxn));
while ($row = mysql_fetch_assoc($resa))
{
echo "<option value=".$row['Customer_ID'] ." $sel>";
echo "$row[Customer_Name]</option>";
}
print("</select></dyna></td>");



?>
