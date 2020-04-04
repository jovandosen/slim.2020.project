$(document).ready(function(){

	getPosts();

});

function getPosts()
{
	$("#posts-data-table tbody").empty();

	$.ajax({
		url: "/data/posts",
		method: "GET",
		success: function(response){
			
			var posts = JSON.parse(response);

			if(posts){
				for(var i = 0; i < posts.length; i++){
					$("#posts-data-table tbody").append("<tr><td>"+posts[i].title+"</td><td>"+posts[i].content+"</td><td>"+posts[i].image+"</td></tr>");
				}
			}

		},
		error: function(response){
			console.log('Error while getting posts data.');
		}
	});
}