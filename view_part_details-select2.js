$(document).ready(function(){
//$("#customer").load('get_customer.php');  //load customer list from get_customer.php
$("#viewpart").validate();  //attach validater to form
var pageSize = 20;

$('#drawing').select2({
	placeholder: 'Type Drawing Number',
	allowClear: true,
	minimumInputLength: 2,
    multiple: false,
    width: 'resolve',
    ajax: {
        dataType: "json",
        type:'POST',
        url: 'get_drawing-select2.php',
		data: function (term, page) {
                    return {
                        pageSize: pageSize,
                        pageNum: page,
                        searchTerm: term
                    };
                },
                results: function (data, page) {
                    //Used to determine whether or not there are more results available,
                    //and if requests for more data should be sent in the infinite scrolling                    
                    var more = (page * pageSize) < data.Total; 
                    return { results: data};
                }
}

});










	$('#drawing').click(function(){  //load operation list based on drawing
		var drawingid=$('#drawing').val();

		if(drawingid!='')
		{
		var purl='get_part_details.php?drawingid='+drawingid;
		$('#footer').load(purl);
		var pdrw='show_part_preview.php?drawingid='+drawingid;
		$('#pdrawing').load(pdrw);
		var ourl="get_op_details_for_part.php?drawingid="+drawingid;
		$('#operation').load(ourl);
		}	
  	});






	$(document).on('click', "a", function(event) {
		event.preventDefault();
		window.open(this.href);
		 		
		});

});
