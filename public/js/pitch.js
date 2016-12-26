$(document).ready(function(){

$('#frmPitchDetails').on('submit', function(){
	event.preventDefault();
	var m_data = $("#frmPitchDetails").serialize();


	$.ajax({
	  url: '/pitch/store',
	  data: m_data,
	  success: pitchUpdateSuccess,
	  method : 'POST',
	  dataType: 'json',
	  processData: false

	});
});
 
$("#tbl_avail").on(  "click", '.available', function () {
   $(this).addClass("allocate");
   $(this).removeClass("available");
   $(this).find('span').text('allocate');
});
$( '#tbl_avail' ).on( "click",'.allocate', function() {
	$(this).find('span').text('available');
   $(this).addClass("available");
   $(this).removeClass("allocate");
});


function pitchUpdateSuccess(pitch, status, xhr){
	console.log(pitch, status, xhr);
    if(pitch.status === true) {
    	alert('Hello');
    	$( "li" ).each(function( index ) {
			  console.log( index + ": " + $( this ).text() );
		});
	    showMsg("success", 'Success', 'Pitch has been saved successfully');
        window.location = "/templates";
    } else {
     
    }
}


	
});
