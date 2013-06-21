  $(document).ready(function(){
	var opeid="";
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
		opeid=$('#Operation_ID').val();
		if(opid!='')
		{	
			$('#tooltype').load('get_tool_types.php');
				var urlo="get_tools_for_ope_for_editing.php?opid="+opid;
				$('#edittools').load(urlo);
				$('#save').show();
		}
		
	});

var options = {  
        target:        '#footer',   // target div id to update result of submit 

success: function(html) {
//				var urlo="get_tools_for_operation.php?opid="+opeid;
//		  		$("#footer2").load(urlo)
		  		
      							},
        clearForm: true,        // clear all form fields after successful submit 
        resetForm: true        // reset the form after successful submit 
    };

	$("#savechange").click(function(event) {
	 if($("#toolsoperation").valid())
  	{
	  	event.preventDefault();  ///we are preventing the form submitting as we are using ajax to dynamically submitting
	    $("#toolsoperation").ajaxSubmit(options);


  	}

		});



  });


