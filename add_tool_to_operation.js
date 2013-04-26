  $(document).ready(function(){
	var opeid='';
	$('#toolsoperation').validate();
  	$("#customer").load('get_customer.php');  //load customer list from get_customer.php
	$('#toolselection').hide();
	$('#tooldia').hide();
	$('#addbutton').hide();
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
				var urlo="get_tools_for_operation.php?opid="+opid;
				$('#footer2').load(urlo);			
		}
		
	});

	$('#tooltype').click(function(){
		var type=$('Tool_Type_ID').val();
		if(type!='')
		{	
					$('#tooldia').show();
					$('#addbutton').show();
			
		}
		
	});

	$('#toolsoperation').on("click",'#Tool_ID_1',function(){
		var tid=$('#Tool_ID_1').val();
		if(tid!='')
		{	var urli="get_insert_for_tool.php?tid="+tid+'&iid=1';
					$('#insert1').load(urli);
			
		}
		
	});

	$('#toolsoperation').on("click",'#Tool_ID_2',function(){
		var tid=$('#Tool_ID_2').val();
		if(tid!='')
		{	var urli="get_insert_for_tool.php?tid="+tid+'&iid=2';
					$('#insert2').load(urli);
			
		}
		
	});


	$('#tdia').keyup(function(){
		var tooldiameter=$('#tdia').val();
		var ttype=$('#Tool_Type_ID').val();
		if(tooldiameter!='')
		{	
			
			url='get_tool_of_type_dia.php?ttype='+ttype+'&tdia='+tooldiameter;
			$('#tool').load(url);
		
		}
		
	});

$('#add').click(function(){
	if($('#toolsoperation').valid())
	{

	var opid=$('#Operation_ID').val();
    $("#toolsoperation").ajaxSubmit(options);
    						
  	}
		});
			
var options = {  ///options for ajaxSubnit function 
			method: "post",
			url: "add_tool_to_operation.php",

        target:        '#footer2',   // target div id to update result of submit 
      					success: function(html) {

				$('#tooltype').text(' ');
				$('#tool').text(' ');
				$('#tdia').val();
				$('#tooldia').hide();
				$('#addbutton').hide();
				document.getElementById("footer").innerHTML=html;
			console.log(opeid);															
				var urlo="get_tools_for_operation.php?opid="+opeid;
				$('#footer2').load(urlo);
      							}
 


    };
	
	
		
	
	



  });


/*		var toolid1=$('#Tool_ID_1').val();
		var toolid2=$('#Tool_ID_2').val();
		var holderid=$('#Holder_ID').val();
		var mdesc=$('#tdesc').val();
		var toh=$('#toh').val();
		var tlife=$('#tlife').val();
	var turl="add_tool_to_operation.php?toolid1="+toolid1+'&toolid2='+toolid2+'&holderid='+holderid+'&mdesc='+mdesc+'&toh='+toh+'&tlife='+tlife;*/
