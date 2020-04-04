$(document).ready(function(){

	getPosts();

});

function getPosts()
{
	$("#posts-data-table tbody").empty();

	$.ajax({
		url: "/data/posts",
		method: "GET",
		beforeSend: function(){
			$("#posts-data-table tbody").append("<tr><td colspan='3' class='text-center'><div class='spinner-border' role='status'><span class='sr-only'>Loading</span></div></td></tr>");
		},
		success: function(response){

			$("#posts-data-table tbody").empty();
			
			var posts = JSON.parse(response);

			if(posts){
				for(var i = 0; i < posts.length; i++){
					$("#posts-data-table tbody").append("<tr><td>"+posts[i].title+"</td><td>"+posts[i].content+"</td><td><img src=images/gallery/"+posts[i].image+" width='50px' height='50px'></td></tr>");
				}
			}

		},
		error: function(response){
			console.log('Error while getting posts data.');
		}
	});
}