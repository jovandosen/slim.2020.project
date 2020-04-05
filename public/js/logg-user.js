$(document).ready(function(){

	loggUserData();
	setInterval(loggUserData, 55000);

});

function loggUserData()
{
	var uID = $("#user-id-hidden").val();
	var pID = $("#post-id-hidden").val();

	$.ajax({
		url: "/logg/user/data",
		method: "GET",
		data: {uID: uID, pID: pID},
		success: function(response){
			console.log(response);
		},
		error: function(response){
			console.log('Error while logging user data.');
		}
	});
}