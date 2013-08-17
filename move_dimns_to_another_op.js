$(document).ready(function() {
	$('#add_ip_dimn').validate();
	$('#customer').load("get_customer.php"); //load customer list on to div customer
	$('#act').hide();
	var action='';
	$('input[type="radio"]').click(function(){
		
			action=$(this).val();
		$('#sdrawing').text(' ');
		$('#ddrawing').text(' ');
		$('#soperation').text(' ');
		$('#doperation').text(' ');
		$('#act').show();		


	});


$('#customer').click(function() {     //populate drawing list based on customer
	var custid=$('#Customer_ID').val();
	if(custid!=''){
		var urls="get_drawing.php?custid="+custid+"&id=S";
		var urld="get_drawing.php?custid="+custid+"&id=D";
		$('#sdrawing').load(urls);	
		if(action=='copy')
		{
		$('#ddrawing').load(urld);	
		$('#ddrawing').show();
		}else{
			$('#ddrawing').hide();
		}
		
	}else
		{
		$('#sdrawing').text('');
		$('#ddrawing').text('');
		}
    });

$("#sdrawing").click(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_IDS').val();
	if(drawingid!='')
		{
		var urls="get_operations.php?drawingid="+drawingid+"&id=S"+'&iol=1';
		var urld="get_operations.php?drawingid="+drawingid+"&id=D"+'&iol=1';
			if(action=='move')
			{
			$('#soperation').load(urls);
			$('#doperation').load(urld);
			}else{
				$('#soperation').load(urls);
			}
			
		}else{
			$('#soperation').text('');
			$('#doperation').text('');
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


$("#ddrawing").click(function() {      //populate operation list based on drawing no
	var drawingid=$('#Drawing_IDD').val();
	if(drawingid!='')
		{
		var urld="get_operations.php?drawingid="+drawingid+"&id=D"+'&iol=1';
		$('#doperation').load(urld);
		}else{
			$('#doperation').text('');
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
							$('#sdrawing').text(' ');
							$('#ddrawing').text(' ');
							$('#soperation').text(' ');
							$('#doperation').text(' ');
							
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
