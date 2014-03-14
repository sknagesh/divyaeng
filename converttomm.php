<?php
$edimn=$_GET['edimn'];

if($edimn!='')
{
$mm='';
if(strpos($edimn, '/'))
{
	$v=explode('/',$edimn);
	for($i=0;$i<count($v);$i++)
	{
		$mm.=round($v[$i]/25.4,4).'/';
	}
}else{
$mm=round($edimn/25.4,4);

}




}

print($mm);


?>