<?
include('dewdb.inc');
$cxn = mysql_connect($dewhost,$dewname,$dewpswd) or die(mysql_error());
mysql_select_db('Divyaeng',$cxn) or die("error opening db: ".mysql_error());

$query="SELECT * FROM Material_Outward;";



$res=mysql_query($query, $cxn) or die(mysql_error($cxn));

// Initializes a container array for all of the calendar events
$jsonArray = array();

while($row = mysql_fetch_array($res))
{

 $sdt = $row['Date'];
 $id=$row['Material_Outward_ID'];
 // Stores each database record to an array
 $buildjson = array('title' => "$row[DC_NO]",'id' => "$id", 'start' => "$sdt",'allday' => false,'backgroundColor' => "green");

 // Adds each array into the container array
 array_push($jsonArray, $buildjson);
}


// Output the json formatted data so that the jQuery call can read it
echo json_encode($jsonArray);
?>