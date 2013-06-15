$(document).ready(function() {
	$('#add_ip_dimn').validate();
	$('#customer').load("get_customer.php"); //load customer list on to div customer
	$('#op').hide();
$('#customer').click(function() {     //populate drawing list based on customer
	var custid=$('#Customer_ID').val();
	if(custid!=''){
		var url="get_drawing.php?custid="+custid;
		$('#drawing').load(url);	
	}else
		{
		$('#drawing').text('');
		}
    });

$("#drawing").click(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_ID').val();
	if(drawingid!='')
		{
		var urls="get_operations.php?drawingid="+drawingid+"&id=S";
		var urld="get_operations.php?drawingid="+drawingid+"&id=D";
		$('#soperation').load(urls);
		$('#doperation').load(urld);
		$('#op').show();
		}else{
		$('#soperation').text('');
		$('#doperation').text('');
		$('#op').hide();
		}
});

$("#soperation").click(function() {     //show inprocess dimensions based on operation no
	var opid=$('#Operation_IDS').val();
	if(opid!='')
	{
	var url="get_op_dimns_to_move.php?opid="+opid;
	$('#ipdimns').load(url);
	}else{
		$('#ipdimns').text('');
	}
});

$("form#add_ip_dimn").on("submit",function(event) {
	event.preventDefault();
	var $this = $(this);
	if($('#add_ip_dimn').valid()&&checkmove()){

		$.ajax({data: $this.serializeArray(),
   			dataType: "html",
   			type: $this.attr("method"),
   			url: $this.attr("action"),
   			success: function(html) {
							document.getElementById('footer').innerHTML=html;
							$('#ipdimns').empty();
      							}
			});
		}
	});


});  ///end of document load function

function checkmove(){
	
	if (!$('*[id^="movedimn"]').is(':checked')){
		alert("Please Select At Least One Dimension To Move");
		return false;
	}else{
		return true;
	}
	
}
