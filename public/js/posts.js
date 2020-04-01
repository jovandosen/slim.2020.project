$(document).ready(function(){

	$("#delete-post").on("click", function(){
		$("#confirmDelete").modal('toggle');
		var form = $("#form-for-submit").val();
		$("#" + form).submit(); 
	});

});

function getForm(id)
{
	$("#form-for-submit").val('delete-post-form-' + id);
}