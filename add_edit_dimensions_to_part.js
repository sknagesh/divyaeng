$(document).ready(function() {
	$('#add_ip_dimn').validate();

	$('#customer').load("get_customer.php"); //load customer list on to div customer

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
		var url="get_operations.php?drawingid="+drawingid;
		$('#operation').load(url);
		}else{
		$('#operation').text('');
		}
});

$("#operation").click(function() {     //show inprocess dimensions based on operation no
	var opid=$('#Operation_ID').val();
	if(opid!='')
	{
	var url="update_ip_dimns_ajax_comments.php?opid="+opid;
	$('#ipdimns').load(url);
	}else{
		$('#ipdimns').text('');
	}
});

$("form#add_ip_dimn").on("submit",function(event) {
	event.preventDefault();
	var $this = $(this);
	if($('#add_ip_dimn').valid()&&validateipdimn()){
	
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


$(document).on("click", '*[id^="dimndesc"]', function(event){
	
	
	 var value=$(this).val();
	 	tid=this.id;
	 	var j=tid.substring(9);
	 	var i=parseInt(j);
	 	console.log("j="+j+"i="+i+"id="+tid);
	 		
	 	var tfid='textfield['+i+']';
	$.ajax({
      					type: "GET",
      					url: "get_comments.php?id="+i+"&value="+value,
      					async:false,
      					success: function(html) {
//						console.log("back from ajax call html="+html);

						$( '[name*="textfield[' + i + ']"]' ).val(html);
//						console.log("text field="+$( '[name*="textfield[' + i + ']"]' ).val());
						if(html==0){
							$('#textfield'+i).text("Yes");
					$( '[name*="basicdimn[' + i + ']"]' ).addClass("required number");
							}else{
							$('#textfield'+i).text("No");
					$( '[name*="basicdimn[' + i + ']"]' ).removeClass("required number");
							}
										}
    							});
	 	
	 	


});


});  ///end of document load function




function addrow()
{
	var noofrows=$('#inprocesstble tr').size();
	var nor=$('#inprocesstble tr').size()-2;
	var baloonno="baloonno["+nor+"]";
	var balval=document.getElementById(baloonno).value;
if(balval!=""){

	var tollower="tollower["+nor+"]";
	var tolupper="tolupper["+nor+"]";
	var tollower=document.getElementById(tollower).value;
	var tolupper=document.getElementById(tolupper).value;
    $.ajax({
      data: noofrows,
      dataType: "html",
      type: "GET",
      url: "add_dimnension_row.php"+ "?filter="+noofrows+"&tl="+tollower+"&tu="+tolupper,
      success: function(html) {
	$('#inprocesstble').append(html);
						      }
    });
 }else{
 	alert("There is already and Empty Row");
 }
}


function validateipdimn()
{
var fields=$("form#add_ip_dimn").serializeArray();
var j=$('#inprocesstble tr').size();
for(var i=0;i<j-1;i++){

	var tollower="tollower["+i+"]";
	var tolupper="tolupper["+i+"]";
	var tollower=document.getElementById(tollower).value;
	var tolupper=document.getElementById(tolupper).value;

var tdif=tolupper-tollower;
if(tdif<0){alert("Lower Tolerance is more then Upper Tolerance: "+tollower+"::"+tolupper);return 0;}

					}
	return 1;
	
}


function delrow()
{
	console.log("inside delrow");
    $.ajax({
      data: $("form#add_ip_dimn").serializeArray(),
      dataType: "html",
      type: "POST",
      url: "del_dimension.php",
      success: function(html) {
			document.getElementById("footer").innerHTML=html;
							$('#ipdimns').empty();			
						      }
    });
}
