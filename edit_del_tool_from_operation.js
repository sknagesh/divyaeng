  $(document).ready(function(){
	$('#toolsoperation').validate();
	$('#save').hide();
  	$("#customer").load('get_customer.php');  //load customer list from get_customer.php
	$('#customer').click(function(){
		var custid=$('#Customer_ID').val();
		
		if(custid!='')
		{	url='get_drawing.php?custid='+custid;
			$('#drawing').load(url);
		}
		
	});
		$('#drawing').click(function(){
		var drawingid=$('#Drawing_ID').val();
		
		if(drawingid!='')
		{	url='get_operations.php?drawingid='+drawingid;
			$('#operation').load(url);
		}
		
	});

	$('#operation').click(function(){
		var opid=$('#Operation_ID').val();
		opeid=$('#Operation_ID').val();
		if(opid!='')
		{	
			$('#tooltype').load('get_tool_types.php');
				var urlo="get_tools_for_ope_for_editing.php?opid="+opid;
				$('#edittools').load(urlo);
				$('#save').show();
		}
		
	});


	$("#submit").click(function(event) {
	 if($("#toolsoperation").valid())
  	{
  		event.preventDefault();
		$.ajax({
      					data: $('#toolsoperation').serializeArray(),
      					success: function(html) {
				document.getElementById("footer").innerHTML=html;
      							}
    							});
  	}
		});



  });


