$(document).ready(function() {

$('#viewipdimn').validate(); //attach form to validation engine
$('#customer').load("get_customer.php"); //load customer list on to div customer
$('#submit').prop("disabled",true);
$('#customer').click(function() {     //populate drawing list based on customer
	var custid=$('#Customer_ID').val();
	var url="get_drawing.php?custid="+custid;
	if(custid!='')
	{
	$('#drawing').load(url);
	}else{
		$('#drawing').text(' ');
	}
    });


$("#drawing").click(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_ID').val();
	if(drawingid!='')
	{
$('#submit').prop("disabled",false);
	}else{
		$('#submit').prop("disabled",true);
	}

});



$("#submit").click(function(event) {

if($('#viewipdimn').valid())
{
		return true;
}


});



});

