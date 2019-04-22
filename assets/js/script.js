document.addEventListener('DOMContentLoaded', function(){
	console.log('loaded');
	var links = document.querySelectorAll(".delete");
	
	for (i = 0; i < links.length; i++) {
		links[i].addEventListener('click', function(click){
			if (!confirm("Do you want to delete?")) {
				click.preventDefault();
			}
		});
	}
	
});



document.addEventListener('DOMContentLoaded', function(){
	console.log('loaded');
	var links = document.querySelectorAll(".editData");
	
	for (i = 0; i < links.length; i++) {
		links[i].addEventListener('click', function(click){
			if (!confirm("Do you want to edit?")) {
				click.preventDefault();
			}
		});
	}
	
});
